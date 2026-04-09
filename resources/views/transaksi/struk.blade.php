<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
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

        .barcode {
            text-align: center;
            margin: 8px 0 6px 0;
        }

        .barcode img {
            width: 160px;
            height: 40px;
        }
    </style>
</head>
<body>

    <div class="text-center">
        <h3 style="margin:0;">TOKO BUKU</h3>
        <p style="margin:2px 0;">Struk Transaksi</p>
    </div>

    <div class="line"></div>

    <div class="barcode">
        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
    </div>

    <p style="margin:2px 0;"><strong>ID:</strong> {{ $transaksi->id }}</p>
    <p style="margin:2px 0;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i:s') }}</p>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Barang</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $item)
                <tr>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="right">{{ $item->jumlah }}</td>
                    <td class="right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">TOTAL</td>
            <td class="right bold">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <p class="text-center" style="margin-top:10px;">
        Terima kasih<br>
        Sudah berbelanja
    </p>

</body>
</html>