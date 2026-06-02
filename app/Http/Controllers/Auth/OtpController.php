<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $otpRecord = EmailOtp::where('user_id', $userId)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah expired. Minta kode baru.']);
        }

        // Tandai OTP sudah dipakai
        $otpRecord->update(['used' => true]);

        // Verifikasi email user
        $user = $otpRecord->user;
        $user->markEmailAsVerified();

        // Hapus session otp
        session()->forget('otp_user_id');

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi! Silakan login.');
    }

    public function resend()
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtp::create([
            'user_id'    => $userId,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, $user->name));

        return back()->with('status', 'Kode OTP baru telah dikirim ke email kamu.');
    }
}