<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Aplikasi Koleksi Buku</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('purple/assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('purple/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
  <!-- endinject -->

  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">

  <!-- CSS kamu -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
</head>

<body>
  <div class="container-scroller">

    <!-- NAVBAR -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo" href="#">
          <img src="{{ asset('images/logo.svg') }}" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="#">
          <img src="{{ asset('images/logo-mini.svg') }}" alt="logo" />
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item me-2">
            <a href="{{ route('login') }}" class="btn btn-primary px-4">
              Login
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('register') }}" class="btn btn-success px-4">
              Register
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel w-100">
        <div class="content-wrapper d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 120px);">

          <div class="text-center">

            <!-- Icon bulat pakai class yg sama dari dashboard -->
            <div class="mb-4">
              <span class="page-title-icon bg-gradient-primary text-white me-2" style="width:72px; height:72px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">
                <i class="mdi mdi-book-open" style="font-size:2rem;"></i>
              </span>
            </div>

            <!-- Judul -->
            <h1 class="font-weight-bold mb-3" style="font-size:2.8rem; color:#2d2d3a;">
              Aplikasi Koleksi Buku
            </h1>

            <!-- Subtitle -->
            <p class="text-muted mb-5" style="font-size:1.1rem;">
              Silakan login untuk mengakses data buku dan kategori
            </p>

            <!-- Tombol Login & Register -->
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 me-3">
              Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-success btn-lg px-5">
              Register
            </a>

          </div>

        </div>

        <!-- FOOTER sama persis seperti dashboard -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright &copy; {{ date('Y') }} Aplikasi Koleksi Buku. All rights reserved.
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
              Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i>
            </span>
          </div>
        </footer>

      </div>
    </div>

  </div>

  <!-- plugins:js -->
  <script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->

  <script src="{{ asset('purple/assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('purple/assets/js/misc.js') }}"></script>
</body>
</html>