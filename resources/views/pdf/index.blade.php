@extends('layouts.app')

@section('title', 'Dokumen PDF')

@section('content')

<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-file-pdf-box"></i>
    </span>
    Dokumen PDF
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Unduh <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>

<div class="row">

  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">PDF Sertifikat</h4>
        <p class="card-description">
          Klik tombol di bawah untuk membuka / mengunduh PDF Sertifikat.
        </p>

        <a href="{{ route('pdf.sertifikat') }}" class="btn btn-gradient-primary">
          <i class="mdi mdi-certificate"></i> Download Sertifikat
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">PDF Undangan</h4>
        <p class="card-description">
          Klik tombol di bawah untuk membuka / mengunduh PDF Undangan.
        </p>

        <a href="{{ route('pdf.undangan') }}" class="btn btn-gradient-info">
          <i class="mdi mdi-email-outline"></i> Download Undangan
        </a>
      </div>
    </div>
  </div>

</div>

@endsection