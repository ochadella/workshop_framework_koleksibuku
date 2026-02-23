<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Login</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
    <!-- End layout styles -->

    <!-- TAMBAHAN CSS KAMU (public/css/style.css) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

    <style>
        /* Hanya background — semua style lain sudah ada di purple/assets/css/style.css */
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }
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

                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light">Sign in to continue.</h6>

                        {{-- Flash Message --}}
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Flash Message Error (opsional, ga ganggu) --}}
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="pt-3">
                            @csrf

                            {{-- Email --}}
                            <div class="form-group">
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       placeholder="Email"
                                       required autofocus>

                                @error('email')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="form-group">
                                <input type="password"
                                       name="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       placeholder="Password"
                                       required>

                                @error('password')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    SIGN IN
                                </button>
                            </div>

                            {{-- Divider (tambah UI aja, ga ganggu logika) --}}
                            <div class="my-3 text-center">
                                <span class="text-muted">or</span>
                            </div>

                            {{-- Login Google (BARU, tidak ganggu login biasa) --}}
                            <div class="d-grid gap-2">
                                <a href="{{ route('google.redirect') }}"
                                   class="btn btn-block btn-outline-danger btn-lg font-weight-medium">
                                    <i class="mdi mdi-google me-2"></i> Sign in with Google
                                </a>
                            </div>

                            {{-- Remember + Forgot --}}
                            <div class="my-2 d-flex justify-content-between align-items-center">

                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox"
                                               name="remember"
                                               class="form-check-input"
                                               {{ old('remember') ? 'checked' : '' }}>
                                        Keep me signed in
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="auth-link text-primary">
                                        Forgot password?
                                    </a>
                                @endif

                            </div>

                            {{-- Register --}}
                            <div class="text-center mt-4 font-weight-light">
                                Don't have an account?
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-primary">
                                        Create
                                    </a>
                                @endif
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <!-- plugins:js -->
    <script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('purple/assets/js/misc.js') }}"></script>
    <script src="{{ asset('purple/assets/js/settings.js') }}"></script>
    <script src="{{ asset('purple/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('purple/assets/js/jquery.cookie.js') }}"></script>
    <!-- endinject -->

</body>
</html>