@extends('layouts.guest')

@section('title', 'Login — Jaya Abadi')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h2>Selamat Datang Kembali</h2>
        <p>Masuk untuk melanjutkan ke Jaya Abadi</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group">
            <label for="email">Email <span class="required">*</span></label>
            <div class="input-wrapper">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       required autofocus placeholder="nama@email.com"
                       class="@error('email') input-error @enderror">
            </div>
            @error('email') 
                <div class="input-error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="input-group">
            <label for="password">Password <span class="required">*</span></label>
            <div class="input-wrapper">
                <span class="input-icon"><i class="fa-regular fa-lock"></i></span>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="@error('password') input-error @enderror">
                <button type="button" onclick="togglePassword()" class="toggle-password" aria-label="Toggle password visibility">
                    <i id="toggleIcon" class="fa-regular fa-eye"></i>
                </button>
            </div>
            @error('password') 
                <div class="input-error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="form-options">
            <label class="remember-me">
                <input type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="auth-btn">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk ke Akun
        </button>
    </form>

    <div class="divider">
        <span>atau</span>
    </div>

    <div class="social-btns">
        <button type="button" class="social-btn">
            <i class="fa-brands fa-google" style="color: #DB4437;"></i> Google
        </button>
        <button type="button" class="social-btn">
            <i class="fa-brands fa-facebook" style="color: #1877F2;"></i> Facebook
        </button>
    </div>

    <p class="auth-footer">
        Belum punya akun? 
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </p>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection