@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Riwayat Transaksi</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Riwayat Transaksi</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Transaksi</h4>
                <p class="card-description">
                    Menampilkan semua transaksi yang sudah disimpan dari halaman POS.
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $index => $trx)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d-m-Y H:i:s') }}</td>
                                    <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($trx->status_pembayaran == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($trx->status_pembayaran == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($trx->status_pembayaran == 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $trx->status_pembayaran ?? 'Belum Ada Status' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transaksi.show', $trx->id) }}" class="btn btn-info btn-sm">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada transaksi.</td>
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