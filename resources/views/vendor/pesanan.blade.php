@extends('layouts.app')

@section('title', 'Pesanan Vendor')
@section('no-sidebar', '1')
@section('no-sidebar-class', 'full-page-wrapper')

@section('content')
<div class="page-header">
    <h3 class="page-title">Pesanan Vendor</h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
        </ul>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="mb-3">
    <a href="{{ route('vendor.index') }}" class="btn btn-light btn-sm">
        Kembali
    </a>

    <a href="{{ route('vendor.pesanan') }}" class="btn btn-primary btn-sm">
        Semua
    </a>

    <a href="{{ route('vendor.pesanan', ['status' => 'lunas']) }}" class="btn btn-success btn-sm">
        Lunas
    </a>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Pesanan</h4>

        @if($pesanan->count())
            @foreach($pesanan as $item)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $item->nama }}</strong><br>
                            <small class="text-muted">
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i') : '-' }}
                            </small>
                        </div>

                        <div class="text-end">
                            <span class="badge {{ $item->status_bayar ? 'badge-success' : 'badge-warning' }}">
                                {{ $item->status_bayar ? 'Lunas' : 'Pending' }}
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Menu</th>
                                <th width="120">Harga</th>
                                <th width="100">Jumlah</th>
                                <th width="150">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item->detailPesanan as $detail)
                                @if($detail->menu && $detail->menu->vendor_id == auth()->user()->vendor->id)
                                    <tr>
                                        <td>{{ $detail->menu->nama_menu }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <strong>Total: Rp {{ number_format($item->total, 0, ',', '.') }}</strong>

                        <div>
                            <a href="{{ route('vendor.pesanan.show', $item->idpesanan) }}" class="btn btn-primary btn-sm">
                                Detail
                            </a>

                            @if(!$item->status_bayar)
                                <form action="{{ route('vendor.pesanan.lunas', $item->idpesanan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Tandai Lunas
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning mb-0">
                Belum ada pesanan untuk vendor ini.
            </div>
        @endif
    </div>
</div>
@endsection