<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Kode OTP</title>

    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            font-family: Arial, sans-serif;
        }
        .email-wrapper {
            padding: 40px 16px;
        }
        .card {
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12);
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 18px;
        }
        .brand-logo img {
            max-width: 140px;
            height: auto;
        }
        .title {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            text-align: center;
        }
        .subtitle {
            margin: 0 0 18px 0;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .otp-box {
            text-align: center;
            margin: 18px 0 10px 0;
            padding: 18px 14px;
            background: #f3f4f6;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        .otp-code {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #111827;
            line-height: 1.2;
        }
        .note {
            margin: 12px 0 0 0;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .link {
            margin-top: 16px;
            font-size: 13px;
            text-align: center;
        }
        .link a {
            color: #6a11cb;
            text-decoration: none;
        }
        .footer {
            margin-top: 18px;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="email-wrapper">
    <div class="card">

        <div class="brand-logo">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </div>

        <h1 class="title">Kode OTP Login</h1>
        <p class="subtitle">Masukkan kode OTP ini di halaman verifikasi OTP kamu.</p>

        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p class="note">Kode berlaku 5 menit. Jangan bagikan kode ini ke siapa pun.</p>

        <div class="link">
            Buka halaman OTP: <a href="{{ url('/otp') }}">{{ url('/otp') }}</a>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }} — Email otomatis, mohon tidak membalas.
        </div>

    </div>
</div>
</body>
</html>