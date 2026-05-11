@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">Kunjungan Toko</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Input Titik Awal Toko</h4>

            <form action="{{ route('kunjungan.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Kode QR</label>
                    <input type="text" name="barcode" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Accuracy</label>
                    <input type="text" name="accuracy" id="accuracy" class="form-control" required>
                </div>

                <button type="button" class="btn btn-info" onclick="ambilLokasiToko()">
                    Geoloc
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </form>

            <hr>

            <h4 class="card-title">List Toko</h4>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode QR</th>
                        <th>Nama Toko</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Accuracy</th>
                        <th>Cetak QR Code</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tokos as $toko)
                    <tr>
                        <td>{{ $toko->barcode }}</td>
                        <td>{{ $toko->nama_toko }}</td>
                        <td>{{ $toko->latitude }}</td>
                        <td>{{ $toko->longitude }}</td>
                        <td>{{ $toko->accuracy }}</td>
                        <td>
                            <button type="button"
                                    class="btn btn-sm btn-dark"
                                    onclick="cetakQRCode(@js($toko->barcode))">
                                Cetak QR Code
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <h4 class="card-title">Titik Kunjungan</h4>

            <div class="form-group">
                <label>QR Scanner / Input Kode QR</label>
                <input type="text"
                       id="barcode_scan"
                       class="form-control"
                       placeholder="Scan / ketik kode QR toko">
            </div>

            <button type="button" class="btn btn-warning" onclick="mulaiScan()">
                Mulai Scan Kamera
            </button>

            <button type="button" class="btn btn-danger" onclick="stopScan()">
                Stop Scan
            </button>

            <div id="reader" style="width:350px; margin-top:20px; display:none;"></div>

            <br>

            <button type="button" class="btn btn-info" onclick="cariDataToko()">
                Cari Data Toko
            </button>

            <button type="button" class="btn btn-success" onclick="ambilLokasiSales()">
                Ambil Lokasi Sales
            </button>

            <button type="button" class="btn btn-danger" onclick="ujiDitolak()">
                Uji Ditolak
            </button>

            <div class="mt-4 p-3 border rounded">

                <h5>Data dari DB hasil scan QR</h5>
                <p id="data_toko">Belum ada data toko.</p>

                <h5>Data titik kunjungan</h5>
                <p id="data_kunjungan">Belum ada data kunjungan.</p>

                <h4 id="hasil_kunjungan"></h4>

            </div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let barcodeAktif = null;
let html5QrCode = null;

function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);

                    if (bestResult) {
                        resolve(bestResult);
                    } else {
                        reject(new Error("Timeout, tidak dapat posisi"));
                    }
                }
            },
            (error) => reject(error),
            {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: maxWait
            }
        );
    });
}

async function ambilLokasiToko() {
    try {
        const pos = await getAccuratePosition(50);

        document.getElementById('latitude').value = pos.coords.latitude;
        document.getElementById('longitude').value = pos.coords.longitude;
        document.getElementById('accuracy').value = pos.coords.accuracy;

    } catch (error) {
        alert('Gagal mengambil lokasi: ' + error.message);
    }
}

function mulaiScan() {
    document.getElementById('reader').style.display = 'block';

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        function(decodedText) {
            document.getElementById('barcode_scan').value = decodedText;

            stopScan();
            cariDataToko();
        },
        function(errorMessage) {
        }
    ).catch(function(err) {
        alert('Kamera gagal dibuka: ' + err);
    });
}

function stopScan() {
    if (html5QrCode) {
        html5QrCode.stop()
        .then(function() {
            document.getElementById('reader').style.display = 'none';
        })
        .catch(function(err) {
            document.getElementById('reader').style.display = 'none';
        });
    }
}

function cariDataToko() {
    let barcode = document.getElementById('barcode_scan').value;

    if (!barcode) {
        alert('Masukkan kode QR terlebih dahulu.');
        return;
    }

    fetch("{{ url('/dashboard/kunjungan-toko/cari') }}/" + barcode)
        .then(response => response.json())
        .then(result => {
            if (!result.status) {
                document.getElementById('data_toko').innerHTML = result.message;
                barcodeAktif = null;
                return;
            }

            barcodeAktif = result.data.barcode;

            document.getElementById('data_toko').innerHTML =
                'Nama Toko: ' + result.data.nama_toko + '<br>' +
                'Latitude: ' + result.data.latitude + '<br>' +
                'Longitude: ' + result.data.longitude + '<br>' +
                'Accuracy: ' + result.data.accuracy + ' meter';
        });
}

async function ambilLokasiSales() {
    if (!barcodeAktif) {
        alert('Cari data toko terlebih dahulu.');
        return;
    }

    try {
        const pos = await getAccuratePosition(50);

        prosesCekKunjungan(
            pos.coords.latitude,
            pos.coords.longitude,
            pos.coords.accuracy
        );

    } catch (error) {
        alert('Gagal mengambil lokasi sales: ' + error.message);
    }
}

function ujiDitolak() {
    if (!barcodeAktif) {
        alert('Cari data toko terlebih dahulu.');
        return;
    }

    prosesCekKunjungan(
        -7.500000,
        112.900000,
        35
    );
}

function prosesCekKunjungan(latitudeSales, longitudeSales, accuracySales) {
    fetch("{{ route('kunjungan.cek') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            barcode: barcodeAktif,
            latitude_sales: latitudeSales,
            longitude_sales: longitudeSales,
            accuracy_sales: accuracySales
        })
    })
    .then(response => response.json())
    .then(result => {
        if (!result.status) {
            alert(result.message);
            return;
        }

        document.getElementById('data_kunjungan').innerHTML =
            'Latitude Sales: ' + result.latitude_sales + '<br>' +
            'Longitude Sales: ' + result.longitude_sales + '<br>' +
            'Accuracy Sales: ' + result.accuracy_sales + ' meter<br>' +
            'Jarak Aktual: ' + result.jarak_aktual + ' meter<br>' +
            'Threshold: ' + result.threshold + ' meter<br>' +
            'Threshold Efektif: ' + result.threshold_efektif + ' meter';

        document.getElementById('hasil_kunjungan').innerHTML =
            result.hasil === 'DITERIMA'
                ? '<span class="text-success">DITERIMA ✓</span>'
                : '<span class="text-danger">DITOLAK ✗</span>';
    });
}

function cetakQRCode(barcode) {
    let printWindow = window.open('', '_blank', 'width=700,height=700');

    printWindow.document.open();

    printWindow.document.write(`
        <html>
        <head>
            <title>Cetak QR Code</title>

            <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"><\/script>

            <style>
                body{
                    font-family: Arial;
                    text-align:center;
                    padding-top:40px;
                }

                canvas{
                    margin-top:20px;
                }

                button{
                    margin-top:30px;
                    padding:10px 20px;
                    background:#6c63ff;
                    border:none;
                    color:white;
                    cursor:pointer;
                    border-radius:5px;
                }
            </style>
        </head>

        <body>
            <h2>QR Code Toko</h2>

            <canvas id="qrcode"></canvas>

            <h3>${barcode}</h3>

            <p>Gunakan QR Code ini untuk scan/input kunjungan toko.</p>

            <button onclick="window.print()">
                Print QR Code
            </button>

            <button onclick="window.close()">
                Tutup
            </button>

            <script>
                QRCode.toCanvas(
                    document.getElementById('qrcode'),
                    "${barcode}",
                    {
                        width: 250
                    }
                );
            <\/script>
        </body>
        </html>
    `);

    printWindow.document.close();
}
</script>

@endsection