<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Register</title>

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

                        <h4>New here?</h4>
                        <h6 class="font-weight-light">
                            Signing up is easy. It only takes a few steps
                        </h6>

                        <form method="POST" action="{{ route('register') }}" class="pt-3">
                            @csrf

                            {{-- Name / Username --}}
                            <div class="form-group">
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       placeholder="Username"
                                       required autofocus>

                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       placeholder="Email"
                                       required>

                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
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
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="form-group">
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control form-control-lg"
                                       placeholder="Confirm Password"
                                       required>
                            </div>

                            {{-- Submit --}}
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    SIGN UP
                                </button>
                            </div>

                            {{-- Link Login --}}
                            <div class="text-center mt-4 font-weight-light">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary">Login</a>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
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