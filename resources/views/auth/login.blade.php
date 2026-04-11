@extends('layouts.guest')

@section('title', 'Login — Sanctuari')

@section('content')
<div class="auth-card" style="padding-top: 1rem;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #0F172A;">Welcome back</h2>
        <p style="color: #64748B; margin-top: 0.25rem;">Log in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group" style="margin-bottom: 1.5rem;">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="input-group" style="margin-bottom: 1.5rem;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" onclick="togglePassword('password')">
                <i class="fa-regular fa-eye"></i>
            </span>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #475569;">
                <input type="checkbox" name="remember" style="accent-color: #F59E0B;"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #F59E0B; text-decoration: none;">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="auth-btn">Log in</button>
    </form>

    <p class="auth-footer" style="margin-top: 2rem;">
        Don't have an account?
        <a href="{{ route('register') }}">Sign up</a>
    </p>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');
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