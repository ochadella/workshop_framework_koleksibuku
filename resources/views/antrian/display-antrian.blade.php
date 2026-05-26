@extends('layouts.app')

@section('title', 'Display Antrian')
@section('no-sidebar', '1')
@section('no-sidebar-class', 'full-page-wrapper')

@section('content')
<div class="content-wrapper text-center">

    <h1 class="mb-4">DISPLAY ANTRIAN</h1>

    <div class="card p-5">
        <h3>Nomor Antrian</h3>

        <h1 id="nomor" style="font-size:100px; font-weight:bold;">
            -
        </h1>

        <h4 id="nama">
            Menunggu...
        </h4>

        {{-- ✅ TOMBOL SELESAI --}}
        <div class="mt-4" id="tombol-selesai" style="display:none;">
            <a href="{{ route('vendor.pesanan') }}" class="btn btn-success btn-lg">
                ✅ Selesai
            </a>
        </div>
    </div>

</div>

<script>

// 🔥 BACA DARI URL PARAMS (bukan localStorage)
function getParam(key) {
    const params = new URLSearchParams(window.location.search);
    return params.get(key);
}

function tampilkanAntrian(nomor, nama) {
    document.getElementById('nomor').innerText = nomor;
    document.getElementById('nama').innerText = nama;

    // ✅ Tampilkan tombol selesai
    document.getElementById('tombol-selesai').style.display = 'block';

    let text = "Nomor antrian " + nomor + " atas nama " + nama + ", silakan menuju loket";

    // ✅ Tunggu sebentar biar speech synthesis siap
    setTimeout(() => {
        let speech = new SpeechSynthesisUtterance(text);
        speech.lang = "id-ID";
        window.speechSynthesis.speak(speech);
    }, 500);
}

// 🔥 LANGSUNG BACA DARI URL
window.onload = function () {
    let nomor = getParam('nomor');
    let nama = getParam('nama');

    if (nomor && nama) {
        tampilkanAntrian(nomor, decodeURIComponent(nama));
    }
};

</script>

@endsection