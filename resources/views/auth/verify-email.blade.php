@extends('layouts.guest')

@section('title', 'Verify Email — JayaAbadi')

@section('content')
<div class="auth-card">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #0F172A;">Verifikasi Email</h2>
        <p style="color: #64748B; margin-top: 0.25rem;">
            Terima kasih sudah mendaftar! Silakan cek email lo dan klik link verifikasi yang sudah kami kirim.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background: #D1FAE5; color: #065F46; border-radius: 8px; font-size: 0.875rem;">
            Link verifikasi baru sudah dikirim ke email lo.
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="font-size: 0.875rem; color: #64748B; text-decoration: underline; background: none; border: none; cursor: pointer;">
                Log Out
            </button>
        </form>
    </div>
</div>
@endsection