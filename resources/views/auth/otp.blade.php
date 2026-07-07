@extends('layouts.guest')

@section('title', 'Verifikasi OTP — JayaAbadi')

@section('content')
<div class="auth-card" style="padding-top: 1rem;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #0F172A;">Verifikasi Email</h2>
        <p style="color: #64748B; margin-top: 0.25rem;">Masukkan kode 6 digit yang dikirim ke email kamu.</p>
    </div>

    @if (session('status'))
        <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background: #D1FAE5; color: #065F46; border-radius: 8px; font-size: 0.875rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div class="input-group" style="margin-bottom: 1.5rem;">
            <label for="otp">Kode OTP</label>
            <input type="text" id="otp" name="otp" maxlength="6" placeholder="000000"
                   style="letter-spacing: 8px; font-size: 1.5rem; text-align: center;"
                   required autofocus>
            @error('otp') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="auth-btn">Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('otp.resend') }}" style="margin-top: 1rem; text-align: center;">
        @csrf
        <button type="submit" style="font-size: 0.875rem; color: #F59E0B; background: none; border: none; cursor: pointer; text-decoration: underline;">
            Kirim ulang kode OTP
        </button>
    </form>
</div>
@endsection