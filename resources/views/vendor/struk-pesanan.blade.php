<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pesanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            margin: 10px;
        }

        .text-center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            font-size: 10px;
            padding: 3px 0;
            text-align: left;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .qrcode {
            text-align: center;
            margin: 8px 0 8px 0;
        }

        .qrcode img {
            width: 90px;
            height: 90px;
        }
    </style>
</head>
<body>

    <div class="text-center">
        <h3 style="margin:0;">KANTIN</h3>
        <p style="margin:2px 0;">Struk Pesanan</p>
    </div>

    <div class="line"></div>

    <div class="qrcode">
        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code">
    </div>

    <p style="margin:2px 0;"><strong>ID Pesanan:</strong> {{ $pesanan->idpesanan }}</p>
    <p style="margin:2px 0;">
        <strong>Nama Customer:</strong>
        {{ $pesanan->customer->nama_customer ?? $pesanan->nama }}
    </p>

    @if($pesanan->customer)
        <p style="margin:2px 0;"><strong>ID Customer:</strong> {{ $pesanan->customer->id }}</p>

        @if($pesanan->customer->email)
            <p style="margin:2px 0;"><strong>Email:</strong> {{ $pesanan->customer->email }}</p>
        @endif

        @if($pesanan->customer->no_hp)
            <p style="margin:2px 0;"><strong>No HP:</strong> {{ $pesanan->customer->no_hp }}</p>
        @endif
    @endif

    <p style="margin:2px 0;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d-m-Y H:i:s') }}</p>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan->detailPesanan as $item)
                @if($item->menu && $item->menu->vendor_id == auth()->user()->vendor->id)
                    <tr>
                        <td>{{ $item->menu->nama_menu }}</td>
                        <td class="right">{{ $item->jumlah }}</td>
                        <td class="right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">TOTAL</td>
            <td class="right bold">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <p class="text-center" style="margin-top:10px;">
        Terima kasih<br>
        Pesanan diproses
    </p>

</body>
</html>