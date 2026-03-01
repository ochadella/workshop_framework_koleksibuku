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
      $currentRoute = app('router')->currentRouteName();
      $isDashboard  = $currentRoute === 'dashboard';
      $isKategori   = str_starts_with((string)$currentRoute, 'kategori.');
      $isBuku       = str_starts_with((string)$currentRoute, 'buku.');
      $isBarang     = str_starts_with((string)$currentRoute, 'barang.');
      $isPdfDocs    = in_array($currentRoute, ['pdf.index', 'pdf.sertifikat', 'pdf.undangan']);

      $activeColor  = '#7c3aed';
      $inactiveColor = '#9b9b9b';
    @endphp

    <!-- DASHBOARD -->
    <li class="nav-item {{ $isDashboard ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="menu-title" style="color: {{ $isDashboard ? $activeColor : $inactiveColor }}">Dashboard</span>
        <i class="mdi mdi-home menu-icon" style="color: {{ $isDashboard ? $activeColor : $inactiveColor }}"></i>
      </a>
    </li>

    <!-- KATEGORI -->
    <li class="nav-item {{ $isKategori ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kategori.index') }}">
        <span class="menu-title" style="color: {{ $isKategori ? $activeColor : $inactiveColor }}">Kategori</span>
        <i class="mdi mdi-tag menu-icon" style="color: {{ $isKategori ? $activeColor : $inactiveColor }}"></i>
      </a>
    </li>

    <!-- BUKU -->
    <li class="nav-item {{ $isBuku ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('buku.index') }}">
        <span class="menu-title" style="color: {{ $isBuku ? $activeColor : $inactiveColor }}">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon" style="color: {{ $isBuku ? $activeColor : $inactiveColor }}"></i>
      </a>
    </li>

    <!-- BARANG -->
    <li class="nav-item {{ $isBarang ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('barang.index') }}">
        <span class="menu-title" style="color: {{ $isBarang ? $activeColor : $inactiveColor }}">Barang</span>
        <i class="mdi mdi-barcode-scan menu-icon" style="color: {{ $isBarang ? $activeColor : $inactiveColor }}"></i>
      </a>
    </li>

    <!-- DOKUMEN PDF -->
    <li class="nav-item {{ $isPdfDocs ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('pdf.index') }}">
        <span class="menu-title" style="color: {{ $isPdfDocs ? $activeColor : $inactiveColor }}">Dokumen PDF</span>
        <i class="mdi mdi-file-pdf-box menu-icon" style="color: {{ $isPdfDocs ? $activeColor : $inactiveColor }}"></i>
      </a>
    </li>

    <!-- LOGOUT -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="menu-title" style="color: {{ $inactiveColor }}">Logout</span>
        <i class="mdi mdi-logout menu-icon" style="color: {{ $inactiveColor }}"></i>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </li>

  </ul>
</nav>