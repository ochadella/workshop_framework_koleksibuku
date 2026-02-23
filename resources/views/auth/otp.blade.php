<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP</title>
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
    <style>
        body { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); }
    </style>
</head>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo text-center">
                            <img src="{{ asset('images/logo.svg') }}" alt="logo">
                        </div>
                        <h4>Verifikasi OTP</h4>
                        <h6 class="font-weight-light">Masukkan kode OTP yang dikirim ke email kamu.</h6>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <form method="POST" action="{{ route('otp.verify') }}" class="pt-3">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="otp"
                                       class="form-control form-control-lg text-center @error('otp') is-invalid @enderror"
                                       placeholder="Masukkan 6 digit OTP"
                                       maxlength="6" required autofocus>
                                @error('otp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    VERIFIKASI
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('purple/assets/js/misc.js') }}"></script>
</body>
</html>
