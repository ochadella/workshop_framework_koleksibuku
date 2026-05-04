@extends('layouts.app')

@section('title', 'Scan QR Code Pesanan')
@section('no-sidebar', '1')
@section('no-sidebar-class', 'full-page-wrapper')

@section('content')
<div class="container">
    <h3>Scan QR Code Pesanan</h3>

    <div class="card p-4">
        <p>Arahkan kamera ke QR code customer</p>

        <div id="reader" style="width: 100%; max-width: 400px;"></div>

        <audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}"></audio>

        <div id="hasil" class="mt-4">
            <div class="alert alert-info">
                Menunggu scan QR Code...
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    const html5QrCode = new Html5Qrcode("reader");
    const beepSound = document.getElementById("beepSound");

    function onScanSuccess(decodedText) {
        beepSound.play();
        html5QrCode.stop();

        fetch("{{ url('/vendor/cari-pesanan') }}/" + decodedText)
            .then(res => res.json())
            .then(result => {
                let hasil = document.getElementById("hasil");

                if (result.status) {
                    let menuHtml = "";

                    result.data.menu.forEach(item => {
                        menuHtml += `
                            <tr>
                                <td>${item.nama_menu}</td>
                                <td>${item.jumlah}</td>
                                <td>Rp ${item.subtotal}</td>
                            </tr>
                        `;
                    });

                    hasil.innerHTML = `
                        <div class="alert alert-success">
                            QR Code berhasil dibaca
                        </div>

                        <p><b>ID Pesanan:</b> ${result.data.idpesanan}</p>
                        <p><b>Customer:</b> ${result.data.nama_customer}</p>
                        <p><b>Status:</b> ${result.data.status_bayar}</p>

                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${menuHtml}
                            </tbody>
                        </table>
                    `;
                } else {
                    hasil.innerHTML = `
                        <div class="alert alert-danger">
                            ${result.message}
                        </div>
                    `;
                }
            });
    }

    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
        onScanSuccess
    );
</script>
@endsection