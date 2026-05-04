<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembelian</title>

    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">

    <style>
        body {
            background: #f5f6fa;
        }

        .card {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4">Riwayat Pembelian</h3>

    <div class="mb-3">
        <a href="{{ route('customer.pesan') }}" class="btn btn-primary">
            Pesan Lagi
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card p-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                    <tr>
                        <td>{{ $p->idpesanan }}</td>
                        <td>{{ $p->tanggal }}</td>
                        <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status_bayar == 1)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pesanan.qrcode', $p->idpesanan) }}" class="btn btn-info btn-sm">
                                Lihat QR
                            </a>

                            @if($p->status_bayar == 1)
                                <a href="{{ route('customer.struk', $p->idpesanan) }}" class="btn btn-success btn-sm">
                                    Cetak Struk
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    Menunggu Lunas
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada riwayat pembelian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>