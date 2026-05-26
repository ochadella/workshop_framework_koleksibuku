@extends('layouts.app')

@section('title', 'Admin Antrian')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fas fa-stethoscope me-2 text-primary"></i>Panel Admin Antrian</h3>
        <span class="badge bg-success" id="sse-status">
            <i class="fas fa-circle me-1" style="font-size:0.6rem;"></i>Live
        </span>
    </div>

    {{-- Tab Poli --}}
    <ul class="nav nav-tabs mb-4" id="poliTab">
        @foreach ($daftarPoli as $kode => $nama)
            <li class="nav-item">
                <a class="nav-link {{ $poliAktif == $kode ? 'active' : '' }}"
                   href="{{ route('antrian.admin', ['poli' => $kode]) }}">
                    <strong>{{ $kode }}</strong> — {{ $nama }}
                    <span class="badge bg-secondary ms-1 badge-count-{{ $kode }}">...</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="row">

        {{-- Kolom Kiri: Panel Kontrol --}}
        <div class="col-md-4">

            {{-- Sedang Dipanggil --}}
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-bullhorn me-2"></i>Sedang Dipanggil — Poli {{ $poliAktif }}
                </div>
                <div class="card-body text-center py-4" id="card-dipanggil">
                    @if ($sedangDipanggil)
                        <div class="display-4 fw-bold text-primary">{{ $sedangDipanggil->nomor_antrian }}</div>
                        <h5>{{ $sedangDipanggil->nama }}</h5>
                    @else
                        <p class="text-muted mb-0">Belum ada yang dipanggil</p>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-grid gap-2 mb-3">
                <button class="btn btn-success btn-lg" id="btn-panggil" onclick="panggilBerikutnya()">
                    <i class="fas fa-forward me-2"></i>Panggil Berikutnya
                </button>
                <button class="btn btn-outline-secondary" onclick="selesaikan()">
                    <i class="fas fa-check me-2"></i>Selesai / Pasien Masuk
                </button>
            </div>

            {{-- Info Statistik --}}
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="fs-3 fw-bold text-warning" id="stat-menunggu">
                                {{ $antrians->where('status', 'menunggu')->count() }}
                            </div>
                            <small class="text-muted">Menunggu</small>
                        </div>
                        <div class="col-6">
                            <div class="fs-3 fw-bold text-danger" id="stat-terlambat">
                                {{ $terlambat->count() }}
                            </div>
                            <small class="text-muted">Terlambat</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Daftar Antrian --}}
        <div class="col-md-8">

            {{-- Daftar Menunggu --}}
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <i class="fas fa-list me-2"></i>Antrian Menunggu — {{ $daftarPoli[$poliAktif] }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0" id="tabel-menunggu">
                        <thead class="table-light">
                            <tr>
                                <th width="120">No. Antrian</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th width="80">Urutan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($antrians as $item)
                                <tr class="{{ $item->status == 'dipanggil' ? 'table-success' : '' }}">
                                    <td><strong>{{ $item->nomor_antrian }}</strong></td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        @if ($item->status == 'dipanggil')
                                            <span class="badge bg-success">Dipanggil</span>
                                        @else
                                            <span class="badge bg-secondary">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->urutan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Tidak ada antrian
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Daftar Terlambat --}}
            <div class="card">
                <div class="card-header bg-warning bg-opacity-25">
                    <i class="fas fa-clock me-2 text-warning"></i>Antrian Terlambat
                    <small class="text-muted ms-2">(double klik untuk panggil ulang)</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0" id="tabel-terlambat">
                        <thead class="table-light">
                            <tr>
                                <th width="120">No. Antrian</th>
                                <th>Nama</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($terlambat as $item)
                                <tr>
                                    <td><strong>{{ $item->nomor_antrian }}</strong></td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning"
                                                onclick="panggilTerlambat({{ $item->id }}, '{{ $item->nomor_antrian }}', '{{ $item->nama }}')">
                                            <i class="fas fa-redo me-1"></i>Panggil Ulang
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Tidak ada antrian terlambat
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Data PHP disimpan di HTML attribute, bukan const JS supaya tidak bentrok --}}
<div id="antrian-data"
     data-poli="{{ $poliAktif }}"
     data-csrf="{{ csrf_token() }}"
     data-sse-url="/vendor/sse/antrian"
     data-panggil-url="{{ route('antrian.panggil') }}"
     data-terlambat-url="{{ route('antrian.panggilTerlambat') }}"
     data-selesai-url="{{ route('antrian.selesai') }}"
     style="display:none;"></div>

@push('scripts')
<script>
(function () {
    // Ambil semua URL & data dari HTML attribute — tidak pakai const/let global
    // supaya tidak bentrok dengan variabel apapun di layouts.app
    var el           = document.getElementById('antrian-data');
    var poliAktif    = el.dataset.poli;
    var csrfToken    = el.dataset.csrf;
    var sseUrl       = el.dataset.sseUrl;
    var panggilUrl   = el.dataset.panggilUrl;
    var terlambatUrl = el.dataset.terlambatUrl;
    var selesaiUrl   = el.dataset.selesaiUrl;

    // ================================================
    // SSE
    // ================================================
    var source     = new EventSource(sseUrl);
    var lastUpdate = 0;

    source.addEventListener('queue-update', function (e) {
        var now = Date.now();
        if (now - lastUpdate < 700) return; // throttle
        lastUpdate = now;
        var state = JSON.parse(e.data);
        requestAnimationFrame(function () { updateBadgeCount(state); });
    });

    source.onerror = function () {
        document.getElementById('sse-status').className = 'badge bg-danger';
        document.getElementById('sse-status').innerHTML =
            '<i class="fas fa-circle me-1" style="font-size:0.6rem;"></i>Offline';
    };

    source.onopen = function () {
        document.getElementById('sse-status').className = 'badge bg-success';
        document.getElementById('sse-status').innerHTML =
            '<i class="fas fa-circle me-1" style="font-size:0.6rem;"></i>Live';
    };

    function updateBadgeCount(state) {
        for (var kode in state) {
            var badge = document.querySelector('.badge-count-' + kode);
            if (badge) badge.textContent = state[kode].menunggu;
        }
    }

    // ================================================
    // Fungsi diexpose ke window supaya onclick bisa akses
    // ================================================
    window.panggilBerikutnya = function () {
        var btn = document.getElementById('btn-panggil');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        fetch(panggilUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ kode_poli: poliAktif }),
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Tidak ada antrian menunggu.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-forward me-2"></i>Panggil Berikutnya';
            }
        })
        .catch(function () {
            alert('Gagal memanggil antrian.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-forward me-2"></i>Panggil Berikutnya';
        });
    };

    window.panggilTerlambat = function (id, nomor, nama) {
        if (!confirm('Panggil ulang ' + nomor + ' \u2014 ' + nama + '?')) return;

        fetch(terlambatUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ id: id }),
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) window.location.reload();
            else alert('Gagal memanggil ulang.');
        });
    };

    window.selesaikan = function () {
        if (!confirm('Tandai pasien yang dipanggil sebagai selesai/masuk?')) return;

        fetch(selesaiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ kode_poli: poliAktif }),
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) window.location.reload();
        });
    };

})();
</script>
@endpush
@endsection