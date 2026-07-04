<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end   = Carbon::parse($month . '-01')->endOfMonth();

        // Tetap mengambil transaksi dengan status 'delivered' dari database
        $orders = Order::with(['user', 'items'])
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->latest()
            ->get();

        $revenue     = $orders->sum('total_amount');
        $ordersCount = $orders->count();
        $aov         = $ordersCount > 0 ? $revenue / $ordersCount : 0;
        $items       = $orders->sum(fn($o) => $o->items->sum('quantity'));

        // Trend vs bulan lalu
        $prevStart   = $start->copy()->subMonth()->startOfMonth();
        $prevEnd     = $start->copy()->subMonth()->endOfMonth();
        
        // Perbandingan bulan lalu juga pakai 'delivered'
        $prevRevenue = Order::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('status', 'delivered')
            ->sum('total_amount');
            
        $trendRevenue = $prevRevenue > 0
            ? (($revenue - $prevRevenue) / $prevRevenue) * 100
            : 0;

        $stats = [
            'revenue'       => $revenue,
            'orders'        => $ordersCount,
            'aov'           => $aov,
            'items'         => $items,
            'trend_revenue' => $trendRevenue,
        ];

        // Chart data harian (~12 titik)
        $chartData   = [];
        $dailyGroups = $orders->groupBy(fn($o) => $o->created_at->format('j'));
        $daysInMonth = $end->day;
        $maxRevenue  = collect($dailyGroups)->map->sum('total_amount')->max() ?: 1;
        $step        = max(1, (int) ceil($daysInMonth / 12));

        for ($d = 1; $d <= $daysInMonth; $d += $step) {
            $dayRevenue = isset($dailyGroups[$d])
                ? $dailyGroups[$d]->sum('total_amount')
                : 0;
            $yPixel = 170 - (($dayRevenue / $maxRevenue) * 140);
            $chartData[] = ['tgl' => $d, 'y_pixel' => round($yPixel)];
        }

        $recentSales = $orders;

        return view('admin.report.sales', compact('stats', 'chartData', 'recentSales'));
    }

    public function export(Request $request)
    {
        $month  = $request->get('month', now()->format('Y-m'));
        $format = $request->get('format', 'csv');
        $start  = Carbon::parse($month . '-01')->startOfMonth();
        $end    = Carbon::parse($month . '-01')->endOfMonth();

        $orders = Order::with(['user', 'items'])
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status', ['cancelled'])
            ->latest()
            ->get();

        $filename = 'laporan-penjualan-' . $month;

        if ($format === 'csv') {
            return $this->exportCsv($orders, $filename);
        }

        return $this->exportPdf($orders, $filename, $month);
    }

    private function exportCsv($orders, $filename)
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Order ID', 'Pelanggan', 'Qty', 'Total', 'Status']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->created_at->format('d/m/Y'),
                    $order->order_number,
                    $order->user->name ?? 'Guest',
                    $order->items->sum('quantity'),
                    $order->total_amount,
                    $order->status,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($orders, $filename, $month)
    {
        $html = view('admin.report.sales-pdf', compact('orders', 'month'))->render();
        $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        // stream() = buka preview di browser, download() = langsung download
        return $pdf->stream("{$filename}.pdf");
    }
}