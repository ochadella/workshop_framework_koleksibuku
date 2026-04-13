@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Data Customer </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">List Customer</h4>

                    <div>
                        <a href="{{ route('customer.createBlob') }}" class="btn btn-primary btn-sm">
                            + Customer Blob
                        </a>

                        <a href="{{ route('customer.createFile') }}" class="btn btn-success btn-sm">
                            + Customer File
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($customers as $index => $c)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $c->nama_customer }}</td>
                                    <td>{{ $c->email }}</td>
                                    <td>{{ $c->no_hp }}</td>
                                    <td>{{ $c->alamat }}</td>

                                    <td>
                                        {{-- FOTO BLOB --}}
                                        @if($c->foto_blob)
                                            <img src="{{ $c->foto_blob }}" width="60" class="rounded">
                                        @endif

                                        {{-- FOTO FILE --}}
                                        @if($c->foto_path)
                                            <img src="{{ asset('storage/' . $c->foto_path) }}" width="60" class="rounded">
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('customer.edit', $c->id) }}" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('customer.destroy', $c->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada data customer
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
@endsection