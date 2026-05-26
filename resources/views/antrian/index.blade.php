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

        <button class="btn btn-primary mt-4" onclick="testPanggil()">
            🔊 Test Panggil
        </button>
    </div>

</div>

<script>
function tampilkanAntrian(nomor, nama) {
    document.getElementById('nomor').innerText = nomor;
    document.getElementById('nama').innerText = nama;

    let text = "Nomor antrian " + nomor + " atas nama " + nama + ", silakan menuju loket";

    let speech = new SpeechSynthesisUtterance(text);
    speech.lang = "id-ID";

    window.speechSynthesis.speak(speech);
}


function testPanggil() {
    tampilkanAntrian(1, "Ocha");
}
</script>

@endsection