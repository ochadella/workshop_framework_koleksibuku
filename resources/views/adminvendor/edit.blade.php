@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit Vendor</h3>
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
        <form action="{{ route('adminvendor.update', $vendor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>Nama Vendor</label>
                <input type="text" name="nama_vendor" class="form-control" value="{{ $vendor->nama_vendor }}" required>
            </div>

            <div class="form-group mb-3">
                <label>User Vendor</label>
                <input type="text" class="form-control" value="{{ $vendor->user->name ?? '-' }} - {{ $vendor->user->email ?? '-' }}" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('adminvendor.index') }}" class="btn btn-light">Kembali</a>
        </form>
    </div>
</div>
@endsection