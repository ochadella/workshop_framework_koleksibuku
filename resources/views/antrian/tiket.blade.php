@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow border-0">
                <div class="card-body text-center p-5">

                    <div class="mb-3">
                        <i class="fas fa-hospital text-primary" style="font-size: 3rem;"></i>
                    </div>

                    <h5 class="text-muted mb-1">{{ $antrian->nama_poli }}</h5>
                    <h6 class="text-muted mb-4">Nomor Antrian Anda</h6>

                    <div class="display-1 fw-bold text-primary mb-2"
                         style="font-size: 5rem; letter-spacing: 4px;">
                        {{ $antrian->nomor_antrian }}
                    </div>

                    <h4 class="mb-4">{{ $antrian->nama }}</h4>

                    <hr>

                    <div class="row text-center mt-3">
                        <div class="col">
                            <small class="text-muted d-block">Poli</small>
                            <strong>{{ $antrian->nama_poli }}</strong>
                        </div>
                        <div class="col">
                            <small class="text-muted d-block">Urutan</small>
                            <strong>{{ $antrian->urutan }}</strong>
                        </div>
                        <div class="col">
                            <small class="text-muted d-block">Tanggal</small>
                            <strong>{{ $antrian->created_at->format('d/m/Y') }}</strong>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Harap menunggu hingga nomor Anda dipanggil.
                        Perhatikan papan antrian atau dengarkan pengumuman.
                    </div>

                    <a href="{{ route('antrian.guest') }}" class="btn btn-outline-primary mt-2">
                        <i class="fas fa-arrow-left me-2"></i>Daftar Lagi
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection