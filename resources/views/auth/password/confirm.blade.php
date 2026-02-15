<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Confirm Password</title>

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

                        <h4>Confirm Password</h4>
                        <h6 class="font-weight-light">
                            Please confirm your password before continuing.
                        </h6>

                        <form method="POST" action="{{ route('password.confirm') }}" class="pt-3">
                            @csrf

                            {{-- Password --}}
                            <div class="form-group">
                                <input id="password"
                                       type="password"
                                       name="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       placeholder="Password"
                                       required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    CONFIRM PASSWORD
                                </button>
                            </div>

                            {{-- Forgot password --}}
                            @if (Route::has('password.request'))
                                <div class="text-center mt-4 font-weight-light">
                                    <a href="{{ route('password.request') }}" class="text-primary">
                                        Forgot your password?
                                    </a>
                                </div>
                            @endif

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