@extends('layouts.app')

@section('title', 'Data Vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">Data Vendor</h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Vendor</li>
        </ul>
    </nav>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="mb-3">
    <a href="{{ route('adminvendor.create') }}" class="btn btn-primary btn-sm">
        Tambah Vendor
    </a>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Daftar Vendor</h4>

        @if($vendors->count())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="80">No</th>
                        <th>Nama Vendor</th>
                        <th>User Vendor</th>
                        <th>Email</th>
                        <th width="180">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vendors as $i => $vendor)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $vendor->nama_vendor }}</td>
                            <td>{{ $vendor->user->name ?? '-' }}</td>
                            <td>{{ $vendor->user->email ?? '-' }}</td>
                            <td>
                                <a href="{{ route('adminvendor.pesanan', $vendor->id) }}" class="btn btn-info btn-sm">
                                    Detail
                                </a>

                                <a href="{{ route('adminvendor.edit', $vendor->id) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('adminvendor.destroy', $vendor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus vendor ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                Belum ada data vendor.
            </div>
        @endif
    </div>
</div>
@endsection