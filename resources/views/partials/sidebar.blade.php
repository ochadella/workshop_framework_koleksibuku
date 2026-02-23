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

    @php
      $isDashboard  = request()->routeIs('dashboard');
      $isKategori   = request()->routeIs('kategori.*') || request()->is('dashboard/kategori*');
      $isBuku       = request()->routeIs('buku.*') || request()->is('dashboard/buku*');
      $isSertifikat = request()->routeIs('pdf.sertifikat');
      $isUndangan   = request()->routeIs('pdf.undangan');
    @endphp

    <!-- DASHBOARD -->
    <li class="nav-item {{ $isDashboard ? 'active' : '' }}">
      <a class="nav-link {{ $isDashboard ? 'active' : '' }}" href="{{ route('dashboard') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <!-- KATEGORI -->
    <li class="nav-item {{ $isKategori ? 'active' : '' }}">
      <a class="nav-link {{ $isKategori ? 'active' : '' }}" href="{{ route('kategori.index') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-tag menu-icon"></i>
      </a>
    </li>

    <!-- BUKU -->
    <li class="nav-item {{ $isBuku ? 'active' : '' }}">
      <a class="nav-link {{ $isBuku ? 'active' : '' }}" href="{{ route('buku.index') }}">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <!-- PDF SERTIFIKAT -->
    <li class="nav-item {{ $isSertifikat ? 'active' : '' }}">
      <a class="nav-link {{ $isSertifikat ? 'active' : '' }}" href="{{ route('pdf.sertifikat') }}">
        <span class="menu-title">PDF Sertifikat</span>
        <i class="mdi mdi-certificate menu-icon"></i>
      </a>
    </li>

    <!-- PDF UNDANGAN -->
    <li class="nav-item {{ $isUndangan ? 'active' : '' }}">
      <a class="nav-link {{ $isUndangan ? 'active' : '' }}" href="{{ route('pdf.undangan') }}">
        <span class="menu-title">PDF Undangan</span>
        <i class="mdi mdi-email-outline menu-icon"></i>
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