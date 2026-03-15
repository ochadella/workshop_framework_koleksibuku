@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Detail Transaksi</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Riwayat Transaksi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Transaksi</h4>

                <div class="mb-3">
                    <p><strong>ID Transaksi:</strong> {{ $transaksi->id }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i:s') }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi->details as $item)
                                <tr>
                                    <td>{{ $item->barang_id }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Detail transaksi tidak ada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mt-3">
                    Kembali
                </a>

                <a href="{{ route('transaksi.struk', $transaksi->id) }}" target="_blank" class="btn btn-danger mt-3">
                    Cetak Struk PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection