<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} - Register</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">

                    <div class="auth-form-light text-left p-5">

                        <div class="brand-logo text-center">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
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

                            {{-- Country (optional) --}}
                            <div class="form-group">
                                <select name="country"
                                        class="form-select form-select-lg @error('country') is-invalid @enderror">
                                    <option value="">Country</option>
                                    @php
                                        $countries = [
                                            'United States of America',
                                            'United Kingdom',
                                            'India',
                                            'Germany',
                                            'Argentina',
                                        ];
                                    @endphp

                                    @foreach($countries as $c)
                                        <option value="{{ $c }}" {{ old('country') === $c ? 'selected' : '' }}>
                                            {{ $c }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('country')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
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

                            {{-- Terms --}}
                            <div class="mb-4">
                                <div class="form-check">
                                    <label class="form-check-label text-muted">
                                        <input type="checkbox"
                                               name="terms"
                                               class="form-check-input"
                                               required
                                               {{ old('terms') ? 'checked' : '' }}>
                                        I agree to all Terms & Conditions
                                    </label>
                                </div>

                                @error('terms')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
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

<!-- JS -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

</body>
</html>
