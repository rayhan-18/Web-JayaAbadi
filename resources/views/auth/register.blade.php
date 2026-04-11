@extends('layouts.guest')

@section('title', 'Register — Sanctuari')

@section('content')
<div class="auth-card" style="padding-top: 1rem;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 600; color: #0F172A;">Create account</h2>
        <p style="color: #64748B; margin-top: 0.25rem;">Join Sanctuari family</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group" style="margin-bottom: 1.5rem;">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="input-group" style="margin-bottom: 1.5rem;">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="input-group" style="margin-bottom: 1.5rem;">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required oninput="updateStrength(this.value)">
            <span class="toggle-password" onclick="togglePassword('password')">
                <i class="fa-regular fa-eye"></i>
            </span>
            <div class="strength-bars">
                <div class="strength-bar" id="sb1"></div>
                <div class="strength-bar" id="sb2"></div>
                <div class="strength-bar" id="sb3"></div>
                <div class="strength-bar" id="sb4"></div>
            </div>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="input-group" style="margin-bottom: 2rem;">
            <label for="password_confirmation">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                <i class="fa-regular fa-eye"></i>
            </span>
        </div>

        <button type="submit" class="auth-btn">Create account</button>
    </form>

    <p class="auth-footer" style="margin-top: 2rem;">
        Already have an account?
        <a href="{{ route('login') }}">Log in</a>
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

    function updateStrength(val) {
        const bars = ['sb1','sb2','sb3','sb4'].map(id => document.getElementById(id));
        bars.forEach(b => b.style.background = '#E2E8F0');
        if (!val) return;

        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = { 1: '#EF4444', 2: '#F59E0B', 3: '#F59E0B', 4: '#10B981' };
        const color  = colors[score] || '#E2E8F0';

        for (let i = 0; i < score; i++) {
            bars[i].style.background = color;
        }
    }
</script>
@endsection