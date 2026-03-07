@extends('layouts.app')

@section('title', 'Select Kota')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .hasil-box {
    padding: 12px 16px;
    background: #f8f9fc;
    border: 1px solid #ebedf2;
    border-radius: 6px;
    min-height: 44px;
    display: flex;
    align-items: center;
    color: #6c7293;
    font-weight: 500;
  }

  .select2-container {
    width: 100% !important;
  }

  .select2-container .select2-selection--single {
    height: 44px !important;
    border: 1px solid #ebedf2 !important;
    border-radius: 4px !important;
    display: flex !important;
    align-items: center !important;
    padding-left: 12px !important;
    background-color: #fff !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 42px !important;
    padding-left: 0 !important;
    padding-right: 30px !important;
    color: #495057 !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
    right: 10px !important;
  }

  .select2-dropdown {
    border: 1px solid #ebedf2 !important;
    border-radius: 4px !important;
    overflow: hidden;
  }

  .select2-search__field {
    border: 1px solid #ebedf2 !important;
    border-radius: 4px !important;
    padding: 6px 10px !important;
  }
</style>
@endpush

@section('content')
<div class="page-header">
  <h3 class="page-title">Select Kota</h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Select Kota</li>
    </ul>
  </nav>
</div>

<div class="row">
  <!-- CARD 1: SELECT -->
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Select</h4>

        <div class="form-group">
          <label for="inputKotaSelect">Kota</label>
          <input type="text" id="inputKotaSelect" class="form-control" placeholder="Masukkan nama kota">
        </div>

        <div class="form-group">
          <button type="button" id="btnTambahKotaSelect" class="btn btn-primary">Tambahkan</button>
        </div>

        <div class="form-group">
          <label for="selectKota">Select Kota</label>
          <select id="selectKota" class="form-control">
            <option value="">-- Pilih Kota --</option>
          </select>
        </div>

        <div class="form-group mb-0">
          <label>Kota Terpilih</label>
          <div class="hasil-box">
            <span id="hasilKotaSelect">Belum ada kota dipilih</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CARD 2: SELECT 2 -->
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">select 2</h4>

        <div class="form-group">
          <label for="inputKotaSelect2">Kota</label>
          <input type="text" id="inputKotaSelect2" class="form-control" placeholder="Masukkan nama kota">
        </div>

        <div class="form-group">
          <button type="button" id="btnTambahKotaSelect2" class="btn btn-primary">Tambahkan</button>
        </div>

        <div class="form-group">
          <label for="select2Kota">Select Kota</label>
          <select id="select2Kota" class="form-control" style="width: 100%;">
            <option value="">-- Pilih Kota --</option>
          </select>
        </div>

        <div class="form-group mb-0">
          <label>Kota Terpilih</label>
          <div class="hasil-box">
            <span id="hasilKotaSelect2">Belum ada kota dipilih</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/modul4.js') }}"></script>
@endpush