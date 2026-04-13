@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Tambah Customer (File) </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin">

        <div class="card">
            <div class="card-body">

                <form action="{{ route('customer.storeFile') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Nama Customer</label>
                        <input type="text" name="nama_customer" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload Foto</label>
                        <input type="file" name="foto_file" class="form-control" accept="image/*" required>
                    </div>

                    <button class="btn btn-success">Simpan</button>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection