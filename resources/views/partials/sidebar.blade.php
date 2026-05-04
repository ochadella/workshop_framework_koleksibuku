<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- PROFILE -->
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('images/faces/face1.jpg') }}" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">
            {{ Auth::user()->name ?? 'User' }}
          </span>
          <span class="text-secondary text-small">
            {{ Auth::user()->email ?? '' }}
          </span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>

    <!-- DASHBOARD -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <!-- KATEGORI -->
    <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kategori.index') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-tag menu-icon"></i>
      </a>
    </li>

    <!-- BUKU -->
    <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('buku.index') }}">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <!-- BARANG -->
    <li class="nav-item {{ request()->routeIs('barang.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('barang.index') }}">
        <span class="menu-title">Barang</span>
        <i class="mdi mdi-barcode-scan menu-icon"></i>
      </a>
    </li>

    <!-- SCAN BARCODE BARANG -->
    <li class="nav-item {{ request()->routeIs('barang.scanBarcode') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('barang.scanBarcode') }}">
        <span class="menu-title">Scan Barcode Barang</span>
        <i class="mdi mdi-camera menu-icon"></i>
      </a>
    </li>

    <!-- ✅ CUSTOMER (TAMBAHAN) -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#customerMenu" aria-expanded="false" aria-controls="customerMenu">
        <span class="menu-title">Customer</span>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse" id="customerMenu">
        <ul class="nav flex-column sub-menu">

          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.index') }}">
              Data Customer
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.createBlob') }}">
              Tambah Customer 1 (Blob)
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.createFile') }}">
              Tambah Customer 2 (File)
            </a>
          </li>

        </ul>
      </div>
    </li>

    <!-- SELECT KOTA -->
    <li class="nav-item {{ request()->is('dashboard/select-kota') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/dashboard/select-kota') }}">
        <span class="menu-title">Select Kota</span>
        <i class="mdi mdi-map-marker menu-icon"></i>
      </a>
    </li>

    <!-- WILAYAH AJAX -->
    <li class="nav-item {{ request()->routeIs('wilayah.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('wilayah.index') }}">
        <span class="menu-title">Wilayah AJAX</span>
        <i class="mdi mdi-google-maps menu-icon"></i>
      </a>
    </li>

    <!-- WILAYAH AXIOS -->
    <li class="nav-item {{ request()->routeIs('wilayah.axios') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('wilayah.axios') }}">
        <span class="menu-title">Wilayah Axios</span>
        <i class="mdi mdi-map-plus menu-icon"></i>
      </a>
    </li>

    <!-- POS AJAX -->
    <li class="nav-item {{ request()->routeIs('pos.index') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('pos.index') }}">
        <span class="menu-title">POS AJAX</span>
        <i class="mdi mdi-cash-register menu-icon"></i>
      </a>
    </li>

    <!-- POS AXIOS -->
    <li class="nav-item {{ request()->routeIs('pos.axios') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('pos.axios') }}">
        <span class="menu-title">POS Axios</span>
        <i class="mdi mdi-cash-multiple menu-icon"></i>
      </a>
    </li>

    <!-- VENDOR -->
    <li class="nav-item {{ request()->routeIs('adminvendor.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('adminvendor.index') }}">
        <span class="menu-title">Vendor</span>
        <i class="mdi mdi-history menu-icon"></i>
      </a>
    </li>

    <!-- DOKUMEN PDF -->
    <li class="nav-item {{ request()->routeIs('pdf.*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('pdf.index') }}">
        <span class="menu-title">Dokumen PDF</span>
        <i class="mdi mdi-file-pdf-box menu-icon"></i>
      </a>
    </li>

    <!-- LOGOUT -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="menu-title">Logout</span>
        <i class="mdi mdi-logout menu-icon"></i>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </li>

  </ul>
</nav>