<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? 'Purple Admin' }}</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body>
<div class="container-scroller">

    <!-- NAVBAR -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo"/>
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo"/>
            </a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-stretch">
            <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
            </button>

            <ul class="navbar-nav navbar-nav-right">

                <!-- Profile -->
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="nav-profile-img">
                            <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image">
                            <span class="availability-status online"></span>
                        </div>
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black">
                                {{ Auth::user()->name ?? 'Guest' }}
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-menu navbar-dropdown dropdown-menu-end" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="mdi mdi-account me-2 text-success"></i> Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>

            </ul>
        </div>
    </nav>
    <!-- END NAVBAR -->


    <div class="container-fluid page-body-wrapper">

        <!-- SIDEBAR -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="menu-title">Dashboard</span>
                        <i class="mdi mdi-home menu-icon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('icons.fontawesome') }}">
                        <span class="menu-title">Font Awesome</span>
                        <i class="mdi mdi-star menu-icon"></i>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- END SIDEBAR -->


        <div class="main-panel">
            <div class="content-wrapper">

                <div class="page-header">
                    <h3 class="page-title">Font Awesome</h3>
                </div>

                <div class="row">
                    <div class="col-lg-12 grid-margin">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Font Awesome Icons</h4>

                                <div class="row icons-list">

                                    @php
                                        $icons = [
                                            'fa fa-address-book',
                                            'fa fa-address-card',
                                            'fa fa-ambulance',
                                            'fa fa-android',
                                            'fa fa-apple',
                                            'fa fa-anchor',
                                            'fa fa-bell',
                                            'fa fa-camera',
                                            'fa fa-car',
                                            'fa fa-cloud',
                                            'fa fa-coffee',
                                            'fa fa-cog',
                                            'fa fa-database',
                                            'fa fa-envelope',
                                            'fa fa-heart',
                                            'fa fa-home',
                                            'fa fa-lock',
                                            'fa fa-phone',
                                            'fa fa-star',
                                            'fa fa-user',
                                            'fa fa-users',
                                            'fa fa-wrench',
                                        ];
                                    @endphp

                                    @foreach($icons as $icon)
                                        <div class="col-sm-6 col-md-4 col-lg-3">
                                            <i class="{{ $icon }}"></i> {{ $icon }}
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                        Copyright © {{ date('Y') }}
                        <a href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>.
                        All rights reserved.
                    </span>
                </div>
            </footer>

        </div>
    </div>
</div>

<!-- plugins:js -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

</body>
</html>
