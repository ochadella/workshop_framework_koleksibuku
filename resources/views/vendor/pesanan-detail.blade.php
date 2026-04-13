@extends('layouts.app')

@section('title', 'Detail Pesanan Vendor')
@section('no-sidebar', '1')
@section('no-sidebar-class', 'full-page-wrapper')

@section('content')
<div class="page-header">
    <h3 class="page-title">Detail Pesanan</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
            <li class="breadcrumb-item"><a href="{{ route('vendor.pesanan') }}">Pesanan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Pesanan</h4>

                <div class="mb-3">
                    <p><strong>ID Pesanan:</strong> {{ $pesanan->idpesanan }}</p>

                    <p>
                        <strong>Nama Customer:</strong>
                        {{ $pesanan->customer->nama_customer ?? $pesanan->nama }}
                    </p>

                    @if($pesanan->customer)
                        <p><strong>ID Customer:</strong> {{ $pesanan->customer->id }}</p>

                        @if($pesanan->customer->email)
                            <p><strong>Email:</strong> {{ $pesanan->customer->email }}</p>
                        @endif

                        @if($pesanan->customer->no_hp)
                            <p><strong>No HP:</strong> {{ $pesanan->customer->no_hp }}</p>
                        @endif

                        @if($pesanan->customer->alamat)
                            <p><strong>Alamat:</strong> {{ $pesanan->customer->alamat }}</p>
                        @endif
                    @endif

                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d-m-Y H:i:s') }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                    <p>
                        <strong>Status:</strong>
                        @if($pesanan->status_bayar)
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Menu</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $adaDetail = false; @endphp

                            @foreach($pesanan->detailPesanan as $item)
                                @if($item->menu && $item->menu->vendor_id == auth()->user()->vendor->id)
                                    @php $adaDetail = true; @endphp
                                    <tr>
                                        <td>{{ $item->idmenu }}</td>
                                        <td>{{ $item->menu->nama_menu }}</td>
                                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach

                            @if(!$adaDetail)
                                <tr>
                                    <td colspan="5" class="text-center">Detail pesanan tidak ada.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('vendor.pesanan') }}" class="btn btn-secondary mt-3">
                    Kembali
                </a>

                <a href="{{ route('vendor.pesanan.struk', $pesanan->idpesanan) }}" target="_blank" class="btn btn-danger mt-3">
                    Cetak Struk PDF
                </a>

                @if(!$pesanan->status_bayar)
                    <form action="{{ route('vendor.pesanan.lunas', $pesanan->idpesanan) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success mt-3">
                            Tandai Lunas
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection