@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Scan Barcode Barang</h3>

    <div class="card p-4">
        <p>Arahkan kamera ke barcode pada label barang.</p>

        <div id="reader" style="width: 100%; max-width: 400px;"></div>

        <audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}"></audio>

        <div id="hasil" class="mt-4">
            <div class="alert alert-info">
                Menunggu scan barcode...
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    const html5QrCode = new Html5Qrcode("reader");
    const beepSound = document.getElementById("beepSound");

    function onScanSuccess(decodedText, decodedResult) {
        beepSound.play();

        html5QrCode.stop();

        fetch("{{ url('/dashboard/barang/cari-barcode') }}/" + decodedText)
            .then(response => response.json())
            .then(result => {
                const hasil = document.getElementById("hasil");

                if (result.status) {
                    hasil.innerHTML = `
                        <div class="alert alert-success">
                            Barcode berhasil dibaca.
                        </div>

                        <div class="card p-3">
                            <h5>Data Barang</h5>
                            <p><b>ID Barang:</b> ${result.data.id_barang}</p>
                            <p><b>Nama Barang:</b> ${result.data.nama_barang}</p>
                            <p><b>Harga:</b> Rp ${result.data.harga}</p>
                        </div>
                    `;
                } else {
                    hasil.innerHTML = `
                        <div class="alert alert-danger">
                            ${result.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById("hasil").innerHTML = `
                    <div class="alert alert-danger">
                        Terjadi kesalahan saat membaca data barang.
                    </div>
                `;
            });
    }

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        onScanSuccess
    );
</script>
@endsection