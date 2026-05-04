<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Code Pesanan</title>

    <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">

    <style>
        body {
            background: #f5f6fa;
        }
        .card {
            max-width: 400px;
            margin: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5 text-center">
    <h3>QR Code Pesanan</h3>

    <div class="card p-4 mt-4">
        <p><b>ID Pesanan:</b> {{ $pesanan->idpesanan }}</p>

        <p><b>Status:</b>
            <span class="badge {{ $pesanan->status_bayar == 1 ? 'bg-success' : 'bg-warning' }}">
                {{ $pesanan->status_bayar == 1 ? 'LUNAS' : 'BELUM BAYAR' }}
            </span>
        </p>

        <div class="mt-3">
            <img src="data:image/png;base64,{{ $qrcode }}" width="200">
        </div>

        <p class="mt-3 text-muted">
            Screenshot / simpan QR ini untuk ditunjukkan ke vendor.
        </p>

        <a href="{{ route('customer.riwayat') }}" class="btn btn-primary mt-3">
            Kembali ke Riwayat
        </a>
    </div>
</div>

</body>
</html>