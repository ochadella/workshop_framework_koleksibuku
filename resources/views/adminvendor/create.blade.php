@extends('layouts.app')

@section('title', 'Tambah Vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">Tambah Vendor</h3>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('adminvendor.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label>Nama Vendor</label>
                <input type="text" name="nama_vendor" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label>Pilih User Vendor</label>
                <select name="iduser" class="form-control" required>
                    <option value="">-- Pilih User Vendor --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('adminvendor.index') }}" class="btn btn-light">Kembali</a>
        </form>
    </div>
</div>
@endsection