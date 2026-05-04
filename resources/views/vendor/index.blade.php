@extends('layouts.app')

@section('title', 'Halaman Vendor')
@section('no-sidebar', '1')
@section('no-sidebar-class', 'full-page-wrapper')

@section('content')
<div class="page-header">
    <h3 class="page-title">Halaman Vendor</h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Vendor</li>
        </ul>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title mb-3">Informasi Vendor</h4>
        <p class="mb-1"><strong>Nama Vendor:</strong> {{ $vendor->nama_vendor }}</p>
        <p class="mb-0"><strong>Email Login:</strong> {{ auth()->user()->email }}</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h4 class="card-title mb-3">Tambah Menu</h4>

        <form action="{{ route('vendor.menu.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" min="0" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Tambah Menu
            </button>

            <a href="{{ route('vendor.pesanan') }}" class="btn btn-success">
                Lihat Pesanan
            </a>

            <a href="{{ route('vendor.scanPesanan') }}" class="btn btn-warning">
                Scan QR Pesanan
            </a>

        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Daftar Menu Saya</h4>

        @if($menus->count())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="80">No</th>
                        <th>Nama Menu</th>
                        <th width="180">Harga</th>
                        <th width="120">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $i => $menu)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $menu->nama_menu }}</td>
                            <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('vendor.menu.delete', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                Belum ada menu untuk vendor ini.
            </div>
        @endif
    </div>
</div>
@endsection