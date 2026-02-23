@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white me-2">
        <i class="mdi mdi-home"></i>
      </span> Dashboard
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
        </li>
      </ul>
    </nav>
  </div>

  <div class="row">
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body">
          <img src="{{ asset('images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Total Buku <i class="mdi mdi-book-open-page-variant mdi-24px float-end"></i></h4>
          <h2 class="mb-5">{{ $totalBuku ?? 0 }}</h2>
          <h6 class="card-text">Jumlah semua data buku</h6>
        </div>
      </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-info card-img-holder text-white">
        <div class="card-body">
          <img src="{{ asset('images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Total Kategori <i class="mdi mdi-tag-multiple mdi-24px float-end"></i></h4>
          <h2 class="mb-5">{{ $totalKategori ?? 0 }}</h2>
          <h6 class="card-text">Jumlah semua kategori</h6>
        </div>
      </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body">
          <img src="{{ asset('images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
          <h4 class="font-weight-normal mb-3">Buku Baru (7 Hari) <i class="mdi mdi-calendar-plus mdi-24px float-end"></i></h4>
          <h2 class="mb-5">{{ $buku7Hari ?? 0 }}</h2>
          <h6 class="card-text">Ditambahkan 7 hari terakhir</h6>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-7 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="clearfix">
            <h4 class="card-title float-start">Jumlah Buku per Bulan ({{ $tahun ?? '' }})</h4>
            <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-end"></div>
          </div>
          <canvas id="visit-sale-chart" class="mt-4"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-5 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Top Kategori</h4>
          <div class="doughnutjs-wrapper d-flex justify-content-center">
            <canvas id="traffic-chart"></canvas>
          </div>
          <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Recent Books</h4>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th> Kode </th>
                  <th> Judul </th>
                  <th> Pengarang </th>
                  <th> Kategori </th>
                  <th> Dibuat </th>
                </tr>
              </thead>
              <tbody>
                @forelse ($recentBooks as $buku)
                  <tr>
                    <td>{{ $buku->kode }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->pengarang }}</td>
                    <td>{{ optional($buku->kategori)->nama_kategori ?? '-' }}</td>
                    <td>{{ optional($buku->created_at)->format('d M Y') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center">Belum ada data buku.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    window.dashboardData = {
      bukuPerBulan: @json(array_values($bukuPerBulan ?? [])),
      kategoriLabels: @json($kategoriLabels ?? []),
      kategoriTotals: @json($kategoriTotals ?? [])
    };
  </script>

  <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush