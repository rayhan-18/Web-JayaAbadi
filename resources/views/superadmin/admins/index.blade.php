@extends('layouts.admin')

@section('title', 'Manajemen Admin')

@section('styles')
<style>
    .page-header { margin-bottom: 28px; }
    .page-title h1 { font-size: 24px; font-weight: 700; margin: 0; }
    .two-col-layout { display: grid; grid-template-columns: 350px 1fr; gap: 24px; align-items: start; }
    .card { background: #ffffff; border-radius: 20px; border: 1px solid rgba(15, 23, 42, 0.06); box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.05); overflow: hidden; }
    .card-header { padding: 20px 24px; border-bottom: 1px solid rgba(15, 23, 42, 0.06); display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 16px; }
    .form-body { padding: 24px; display: flex; flex-direction: column; gap: 16px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .form-control { width: 100%; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 13.5px; outline: none; background: #f8fafc; }
    .btn-submit { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    th { text-align: left; padding: 16px 24px; background: rgba(0,0,0,0.015); border-bottom: 1px solid rgba(15, 23, 42, 0.06); font-size: 12px; color: #475569; }
    td { padding: 16px 24px; border-bottom: 1px solid rgba(15, 23, 42, 0.06); }
    .acc-active { background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .acc-inactive { background: #fef2f2; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .btn-toggle { padding: 6px 14px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; border: none; color: white; }
    .btn-danger { background: #dc2626; } .btn-success { background: #059669; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Manajemen Admin</h1>
        <p>Tambah, pantau, dan kontrol akses akun staf toko.</p>
    </div>
</div>

@if(session('success')) <div style="padding:15px; background:#ecfdf5; color:#059669; border-radius:10px; margin-bottom:20px;">{{ session('success') }}</div> @endif
@if(session('error')) <div style="padding:15px; background:#fef2f2; color:#dc2626; border-radius:10px; margin-bottom:20px;">{{ session('error') }}</div> @endif

<div class="two-col-layout">
    <div class="card">
        <div class="card-header"><i class="ti ti-user-plus" style="color: #2563eb;"></i> Tambah Admin Baru</div>
        <form action="{{ route('superadmin.admins.store') }}" method="POST" class="form-body">
            @csrf
            <div class="form-group"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-group"><label>Nomor Telepon (Opsional)</label><input type="text" name="phone" class="form-control"></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn-submit">Daftarkan Admin</button>
        </form>
    </div>

    <div class="card">
        <div class="card-header"><i class="ti ti-users" style="color: #2563eb;"></i> Daftar Admin Terdaftar</div>
        <div style="overflow-x: auto;">
            <table>
                <thead><tr><th>Identitas Admin</th><th>Status</th><th style="text-align: right;">Aksi</th></tr></thead>
                <tbody>
                    @forelse($admins ?? [] as $admin)
                    <tr>
                        <td><b>{{ $admin->name }}</b><br><span style="font-size:12px;color:gray;">{{ $admin->email }}</span></td>
                        <td><span class="{{ $admin->is_active ? 'acc-active' : 'acc-inactive' }}">{{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td style="text-align: right;">
                            <form action="{{ route('superadmin.admins.toggle', $admin->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-toggle {{ $admin->is_active ? 'btn-danger' : 'btn-success' }}">{{ $admin->is_active ? 'Cabut Akses' : 'Pulihkan' }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center; padding:32px; color:gray;">Belum ada admin lain.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection