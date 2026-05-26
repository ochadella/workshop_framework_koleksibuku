@extends('layouts.app')

@section('title', 'Scanner NFC')

@push('styles')
<style>

    .nfc-card {
        border-radius: 20px;
        overflow: hidden;
    }

    .nfc-header {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: white;
        padding: 50px 20px;
        text-align: center;
    }

    .nfc-icon {
        font-size: 80px;
        margin-bottom: 20px;
    }

    .status-box {
        font-size: 16px;
        margin-top: 20px;
        font-weight: bold;
    }

    .hasil-card {
        border-radius: 15px;
    }

    .hasil-item {
        padding: 15px;
        border-radius: 10px;
        background: #f5f7ff;
        margin-bottom: 15px;
    }

    .hasil-label {
        font-size: 13px;
        color: #777;
        margin-bottom: 5px;
    }

    .hasil-value {
        font-size: 18px;
        font-weight: bold;
        word-break: break-word;
    }

</style>
@endpush

@section('content')

<div class="page-header">

    <h3 class="page-title">
        Scanner NFC
    </h3>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                NFC
            </li>

        </ol>
    </nav>

</div>

<div class="row">

    <div class="col-md-12 grid-margin">

        <div class="card nfc-card">

            <div class="nfc-header">

                <div class="nfc-icon">
                    📶
                </div>

                <h2>
                    Web NFC Scanner
                </h2>

                <p class="mt-2">
                    Tempelkan kartu NFC ke bagian belakang HP Android
                </p>

                <button
                    onclick="startScan()"
                    class="btn btn-light btn-lg mt-3"
                >
                    Aktifkan NFC
                </button>

                <div id="status" class="status-box">
                    NFC belum aktif
                </div>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-12 grid-margin">

        <div class="card hasil-card">

            <div class="card-body">

                <h4 class="card-title">
                    Hasil Scan NFC
                </h4>

                <div class="hasil-item">

                    <div class="hasil-label">
                        Serial Number NFC
                    </div>

                    <div id="serialNumber" class="hasil-value">
                        -
                    </div>

                </div>

                <div class="hasil-item">

                    <div class="hasil-label">
                        Isi NFC
                    </div>

                    <div id="isiNfc" class="hasil-value">
                        -
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

async function startScan() {

    const statusNfc = document.getElementById('status');

    const serialNumberEl = document.getElementById('serialNumber');

    const isiNfcEl = document.getElementById('isiNfc');

    if (!('NDEFReader' in window)) {

        statusNfc.innerHTML =
            '❌ Browser tidak mendukung Web NFC';

        return;
    }

    try {

        const ndef = new NDEFReader();

        await ndef.scan();

        statusNfc.innerHTML =
            '✅ NFC aktif. Dekatkan kartu NFC';

        ndef.addEventListener('reading', async ({ serialNumber, message }) => {

            let isi = '';

            for (const record of message.records) {

                isi += new TextDecoder().decode(record.data);

            }

            console.log('Serial Number:', serialNumber);

            console.log('Isi NFC:', isi);

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN HASIL
            |--------------------------------------------------------------------------
            */

            serialNumberEl.innerHTML =
                serialNumber || '-';

            isiNfcEl.innerHTML =
                isi || '(Kosong)';

            statusNfc.innerHTML =
                '✅ NFC berhasil dibaca';

            /*
            |--------------------------------------------------------------------------
            | AUTO SAVE KE DATABASE
            |--------------------------------------------------------------------------
            */

            await fetch("{{ route('nfc.store') }}", {

                method: "POST",

                headers: {

                    "Content-Type": "application/json",

                    "X-CSRF-TOKEN": "{{ csrf_token() }}"

                },

                body: JSON.stringify({

                    serial_number: serialNumber,

                    isi_nfc: isi

                })

            });

        });

    } catch(err) {

        console.log(err);

        statusNfc.innerHTML =
            '❌ Error: ' + err;

    }

}

</script>

@endpush