<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Purple Admin')</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body>
<div class="container-scroller">

    {{-- ================= NAVBAR ================= --}}
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-stretch">

            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-menu"></span>
            </button>

            {{-- Search --}}
            <div class="search-field d-none d-md-block">
                <form class="d-flex align-items-center h-100" method="GET" action="{{ route('search') }}">
                    <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                            <i class="input-group-text border-0 mdi mdi-magnify"></i>
                        </div>
                        <input type="text" name="q" class="form-control bg-transparent border-0"
                               placeholder="Search..." value="{{ request('q') }}">
                    </div>
                </form>
            </div>

            {{-- Right Navbar --}}
            <ul class="navbar-nav navbar-nav-right">

                {{-- Profile --}}
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="nav-profile-img">
                            <img src="{{ Auth::user()->photo ?? asset('assets/images/faces/face1.jpg') }}">
                            <span class="availability-status online"></span>
                        </div>
                        <div class="nav-profile-text">
                            <p class="mb-1 text-black">
                                {{ Auth::user()->name ?? 'Guest' }}
                            </p>
                        </div>
                    </a>

                    <div class="dropdown-menu navbar-dropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="mdi mdi-account me-2 text-success"></i> Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>

            </ul>

        </div>
    </nav>
    {{-- ================= END NAVBAR ================= --}}

    <div class="container-fluid page-body-wrapper">

        {{-- ================= SIDEBAR ================= --}}
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <span class="menu-title">Dashboard</span>
                        <i class="mdi mdi-home menu-icon"></i>
                    </a>
                </li>

                {{-- Dynamic Menu Example --}}
                @php
                    $menus = [
                        ['name' => 'Users', 'route' => 'users.index', 'icon' => 'mdi-account-multiple'],
                        ['name' => 'Products', 'route' => 'products.index', 'icon' => 'mdi-cube'],
                        ['name' => 'Orders', 'route' => 'orders.index', 'icon' => 'mdi-cart'],
                    ];
                @endphp

                @foreach($menus as $menu)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}"
                           href="{{ route($menu['route']) }}">
                            <span class="menu-title">{{ $menu['name'] }}</span>
                            <i class="mdi {{ $menu['icon'] }} menu-icon"></i>
                        </a>
                    </li>
                @endforeach

            </ul>
        </nav>
        {{-- ================= END SIDEBAR ================= --}}

        {{-- ================= MAIN PANEL ================= --}}
        <div class="main-panel">
            <div class="content-wrapper">

                {{-- Flash Message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Page Content --}}
                @yield('content')

            </div>

            {{-- Footer --}}
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                        Copyright © {{ date('Y') }}
                        <a href="#">Your Company</a>. All rights reserved.
                    </span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
                        Made with <i class="mdi mdi-heart text-danger"></i>
                    </span>
                </div>
            </footer>

        </div>
        {{-- ================= END MAIN PANEL ================= --}}

    </div>
</div>

{{-- JS --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

@stack('scripts')

</body>
</html>
