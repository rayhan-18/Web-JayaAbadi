<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f8f8; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 40px auto; background: white; border-radius: 12px; padding: 40px; }
        .brand { font-size: 24px; font-weight: 600; color: #0F172A; margin-bottom: 24px; }
        .otp-box { background: #FEF3C7; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0; }
        .otp-code { font-size: 36px; font-weight: 700; letter-spacing: 8px; color: #B45309; }
        .expired { color: #64748B; font-size: 14px; margin-top: 8px; }
        p { color: #475569; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand">JayaAbadi</div>
        <p>Halo <strong>{{ $name }}</strong>,</p>
        <p>Gunakan kode OTP berikut untuk verifikasi email akun JayaAbadi kamu:</p>
        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
            <div class="expired">Berlaku selama 5 menit</div>
        </div>
        <p>Jika kamu tidak merasa mendaftar, abaikan email ini.</p>
        <p style="color: #94A3B8; font-size: 12px; margin-top: 32px;">© 2025 JayaAbadi. All rights reserved.</p>
    </div>
</body>
</html>