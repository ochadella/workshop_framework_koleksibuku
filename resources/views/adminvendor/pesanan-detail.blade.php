@extends('layouts.app')

@section('title', 'Detail Pesanan Vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">Detail Pesanan Vendor</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('adminvendor.index') }}">Data Vendor</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('adminvendor.pesanan', $vendor->id) }}">Pesanan {{ $vendor->nama_vendor }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Pesanan</h4>

                <div class="mb-3">
                    <p><strong>ID Pesanan:</strong> {{ $pesanan->idpesanan }}</p>
                    <p><strong>Nama:</strong> {{ $pesanan->nama }}</p>
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
                                @if($item->menu && $item->menu->vendor_id == $vendor->id)
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

                <a href="{{ route('adminvendor.pesanan', $vendor->id) }}" class="btn btn-secondary mt-3">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection