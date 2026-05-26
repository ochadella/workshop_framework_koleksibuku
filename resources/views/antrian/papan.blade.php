{{-- resources/views/antrian/papan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #0d1b2a;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }
        .header-bar {
            background: #1a3a5c;
            padding: 12px 24px;
            border-bottom: 3px solid #00aaff;
        }
        .poli-card {
            background: #112233;
            border: 1px solid #1e4a7a;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.4s ease;
            min-height: 160px;
        }
        .poli-card.aktif {
            background: #003366;
            border: 2px solid #00aaff;
            box-shadow: 0 0 20px rgba(0, 170, 255, 0.4);
            animation: pulse 1.5s ease-in-out 3;
        }
        @keyframes pulse {
            0%   { box-shadow: 0 0 10px rgba(0,170,255,0.3); }
            50%  { box-shadow: 0 0 40px rgba(0,170,255,0.9); }
            100% { box-shadow: 0 0 10px rgba(0,170,255,0.3); }
        }
        .nomor-antrian {
            font-size: 3.5rem;
            font-weight: 900;
            color: #00aaff;
            letter-spacing: 3px;
            line-height: 1;
        }
        .nama-pasien {
            font-size: 1.2rem;
            color: #cce6ff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .nama-poli-label {
            font-size: 0.85rem;
            color: #7fb3d3;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .badge-menunggu {
            background: #1a4a6a;
            color: #aaddff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .kosong-text {
            color: #3a5a7a;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 3px;
        }
        .clock {
            font-size: 1.4rem;
            color: #aaddff;
            font-weight: 600;
        }
        .sse-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #00ff88;
            display: inline-block;
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.2; }
        }
        /* Tombol aktivasi suara (wajib klik sekali dulu) */
        #overlay-suara {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
    </style>
</head>
<body>

{{-- Overlay aktivasi suara (wajib sekali klik) --}}
<div id="overlay-suara">
    <div class="text-center">
        <i class="fas fa-volume-up text-info mb-3" style="font-size:4rem;"></i>
        <h3 class="text-white mb-2">Papan Antrian</h3>
        <p class="text-secondary mb-4">Klik untuk mengaktifkan tampilan & suara</p>
        <button class="btn btn-info btn-lg px-5" onclick="aktivasiPapan()">
            <i class="fas fa-play me-2"></i>Mulai
        </button>
    </div>
</div>

{{-- Header --}}
<div class="header-bar d-flex justify-content-between align-items-center">
    <div>
        <i class="fas fa-hospital me-2 text-info"></i>
        <strong class="fs-5">Sistem Antrian Digital</strong>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="clock" id="jam">--:--:--</span>
        <span>
            <span class="sse-dot" id="sse-dot"></span>
            <small class="ms-1 text-info" id="sse-label">Live</small>
        </span>
    </div>
</div>

{{-- Grid Poli --}}
<div class="container-fluid p-4">
    <div class="row g-3" id="grid-poli">
        @foreach ($daftarPoli as $kode => $nama)
        <div class="col-md-6 col-lg-3">
            <div class="poli-card" id="card-poli-{{ $kode }}">
                <div class="nama-poli-label mb-2">
                    <i class="fas fa-stethoscope me-1"></i>{{ $nama }}
                </div>
                <div class="nomor-antrian kosong-text" id="nomor-{{ $kode }}">---</div>
                <div class="nama-pasien mt-1" id="nama-{{ $kode }}">Menunggu...</div>
                <div class="mt-2">
                    <span class="badge-menunggu" id="tunggu-{{ $kode }}">
                        <i class="fas fa-users me-1"></i><span>0</span> menunggu
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Audio ting-tong (siapkan file public/audio/dingdong.mp3) --}}
<audio id="audio-dingdong" src="{{ asset('audio/dingdong.mp3') }}" preload="auto"></audio>

<script>
    // ================================================
    // JAM REAL-TIME
    // ================================================
    function updateJam() {
        const now = new Date();
        document.getElementById('jam').textContent =
            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    setInterval(updateJam, 1000);
    updateJam();

    // ================================================
    // AKTIVASI PAPAN (wajib klik dulu untuk unlock suara)
    // ================================================
    function aktivasiPapan() {
        document.getElementById('overlay-suara').style.display = 'none';
        mulaiSSE();
    }

    // ================================================
    // SSE LISTENER
    // ================================================
    let stateSaatIni = {};

    function mulaiSSE() {
        const source = new EventSource("{{ route('antrian.stream') }}");

        source.addEventListener('queue-update', function (e) {
            const state = JSON.parse(e.data);
            prosesUpdate(state);
        });

        source.onerror = function () {
            document.getElementById('sse-dot').style.background = '#ff4444';
            document.getElementById('sse-label').textContent = 'Offline';
        };

        source.onopen = function () {
            document.getElementById('sse-dot').style.background = '#00ff88';
            document.getElementById('sse-label').textContent = 'Live';
        };
    }

    // ================================================
    // PROSES UPDATE STATE — Cek poli mana yang berubah
    // ================================================
    function prosesUpdate(state) {
        for (const kode in state) {
            const poli    = state[kode];
            const sebelum = stateSaatIni[kode];

            const nomorBaru    = poli.dipanggil ? poli.dipanggil.nomor : null;
            const nomorSebelum = sebelum && sebelum.dipanggil ? sebelum.dipanggil.nomor : null;

            // Update UI
            updateKartuPoli(kode, poli);

            // Bunyikan suara jika nomor berubah
            if (nomorBaru && nomorBaru !== nomorSebelum) {
                bunyikanPanggilan(poli);
            }
        }
        stateSaatIni = JSON.parse(JSON.stringify(state)); // deep copy
    }

    // ================================================
    // UPDATE TAMPILAN KARTU POLI
    // ================================================
    function updateKartuPoli(kode, poli) {
        const card  = document.getElementById('card-poli-' + kode);
        const elNomor = document.getElementById('nomor-' + kode);
        const elNama  = document.getElementById('nama-' + kode);
        const elTunggu = document.getElementById('tunggu-' + kode).querySelector('span');

        elTunggu.textContent = poli.menunggu;

        if (poli.dipanggil) {
            card.classList.add('aktif');
            elNomor.className = 'nomor-antrian';
            elNomor.textContent = poli.dipanggil.nomor;
            elNama.textContent  = poli.dipanggil.nama;

            // Hilangkan efek aktif setelah 5 detik
            setTimeout(() => card.classList.remove('aktif'), 5000);
        } else {
            card.classList.remove('aktif');
            elNomor.className   = 'nomor-antrian kosong-text';
            elNomor.textContent = '---';
            elNama.textContent  = 'Menunggu...';
        }
    }

    // ================================================
    // BUNYIKAN PANGGILAN: dingdong + TTS
    // ================================================
    function bunyikanPanggilan(poli) {
        const audio = document.getElementById('audio-dingdong');
        const teks  = `Nomor antrian ${poli.dipanggil.nomor}. ${poli.dipanggil.nama}. Silakan masuk ke ${poli.nama_poli}.`;

        audio.currentTime = 0;
        audio.play().then(() => {
            audio.onended = function () {
                bacaTeks(teks);
            };
        }).catch(() => {
            // Jika audio gagal, langsung TTS
            bacaTeks(teks);
        });
    }

    function bacaTeks(teks) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();

        const ucap    = new SpeechSynthesisUtterance(teks);
        ucap.lang     = 'id-ID';
        ucap.rate     = 0.85;
        ucap.pitch    = 1.0;
        ucap.volume   = 1.0;
        window.speechSynthesis.speak(ucap);
    }
</script>
</body>
</html>