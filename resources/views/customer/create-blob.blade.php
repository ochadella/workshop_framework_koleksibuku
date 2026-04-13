@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title"> Tambah Customer (Blob) </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin">

        <div class="card">
            <div class="card-body">

                <form action="{{ route('customer.storeBlob') }}" method="POST">
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
                        <label>Ambil Foto</label><br>

                        <video id="video" width="300" autoplay></video><br><br>

                        <button type="button" class="btn btn-primary" onclick="takePhoto()">
                            Ambil Foto
                        </button>

                        <br><br>

                        <canvas id="canvas" width="300" style="display:none;"></canvas>

                        <img id="preview" width="150" class="rounded">

                        <input type="hidden" name="foto_blob" id="foto_blob">
                    </div>

                    <button class="btn btn-success">Simpan</button>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');
    const foto_blob = document.getElementById('foto_blob');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        });

    function takePhoto() {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, 300, 300);

        const dataURL = canvas.toDataURL('image/png');
        preview.src = dataURL;
        foto_blob.value = dataURL;
    }
</script>
@endsection