{{-- register.blade.php --}}
@extends('layouts.guest')

@section('title', 'Register — Jaya Abadi')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <h2>Bergabung dengan Jaya Abadi</h2>
        <p>Mulai perjalanan desain impian Anda</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group">
            <label for="name">Nama Lengkap <span class="required">*</span></label>
            <div class="input-wrapper">
                <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       required autofocus placeholder="John Doe"
                       class="@error('name') input-error @enderror">
            </div>
            @error('name') 
                <div class="input-error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="input-group">
            <label for="email">Email <span class="required">*</span></label>
            <div class="input-wrapper">
                <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       required placeholder="nama@email.com"
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
                <input type="password" id="password" name="password" required 
                       placeholder="Buat password kuat" oninput="updateStrength(this.value)"
                       class="@error('password') input-error @enderror">
                <button type="button" onclick="togglePassword('password')" class="toggle-password" aria-label="Toggle password visibility">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            <div class="strength-container">
                <div class="strength-bars">
                    <div class="strength-bar" id="sb1"></div>
                    <div class="strength-bar" id="sb2"></div>
                    <div class="strength-bar" id="sb3"></div>
                    <div class="strength-bar" id="sb4"></div>
                </div>
                <div class="strength-text" id="strengthText">Minimal 8 karakter dengan huruf besar, angka, dan simbol</div>
            </div>
            @error('password') 
                <div class="input-error-text"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div> 
            @enderror
        </div>

        <div class="input-group">
            <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
            <div class="input-wrapper">
                <span class="input-icon"><i class="fa-regular fa-check-circle"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       required placeholder="Ulangi password">
                <button type="button" onclick="togglePassword('password_confirmation')" class="toggle-password" aria-label="Toggle password visibility">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="auth-btn">
            <i class="fa-solid fa-user-plus"></i> Buat Akun
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
        Sudah punya akun? 
        <a href="{{ route('login') }}">Masuk</a>
    </p>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.parentElement.querySelector('.toggle-password i');
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
        const text = document.getElementById('strengthText');
        
        bars.forEach(b => b.style.background = '#E2E8F0');
        
        if (!val) {
            text.textContent = 'Minimal 8 karakter dengan huruf besar, angka, dan simbol';
            text.style.color = '#94A3B8';
            return;
        }

        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const configs = {
            1: { color: '#EF4444', label: 'Lemah — tambahkan huruf besar, angka, atau simbol' },
            2: { color: '#F59E0B', label: 'Sedang — tambahkan lebih banyak variasi' },
            3: { color: '#F59E0B', label: 'Kuat — hampir sempurna!' },
            4: { color: '#10B981', label: 'Sangat Kuat — password yang aman!' }
        };

        const config = configs[score] || { color: '#E2E8F0', label: '' };
        
        for (let i = 0; i < score; i++) {
            bars[i].style.background = config.color;
        }

        text.textContent = config.label || '';
        text.style.color = config.color || '#94A3B8';
    }
</script>
@endsection