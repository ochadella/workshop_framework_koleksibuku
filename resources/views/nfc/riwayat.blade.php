@extends('layouts.app')

@section('title', 'Riwayat NFC')

@section('content')

<div class="page-header">
    <h3 class="page-title">Riwayat Scan NFC</h3>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>

            <li class="breadcrumb-item active">
                Riwayat NFC
            </li>
        </ol>
    </nav>
</div>

<div class="card">

    <div class="card-body">

        <h4 class="card-title">
            Data Scan NFC
        </h4>

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th width="80">No</th>
                        <th>Serial Number</th>
                        <th>Isi NFC</th>
                        <th>Waktu Scan</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($logs as $i => $log)

                    <tr>
                        <td>{{ $i + 1 }}</td>

                        <td>
                            {{ $log->serial_number ?? '-' }}
                        </td>

                        <td>
                            {{ $log->isi_nfc ?? '-' }}
                        </td>

                        <td>
                            {{ $log->created_at }}
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="text-center">
                            Belum ada data NFC
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection