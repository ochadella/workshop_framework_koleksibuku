<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Buku</title>
  <link rel="stylesheet" href="{{ asset('purple/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('purple/assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('purple/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel w-100">
        <div class="content-wrapper">
          <h3 class="page-title mb-3">Edit Buku (ID: {{ $id }})</h3>

          <div class="card">
            <div class="card-body">
              <form action="{{ route('buku.update', $id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                  <label>Judul Buku</label>
                  <input type="text" name="judul" class="form-control" value="Contoh Buku">
                </div>

                <div class="form-group mb-3">
                  <label>Penulis</label>
                  <input type="text" name="penulis" class="form-control" value="Contoh Penulis">
                </div>

                <button class="btn btn-gradient-primary">Update</button>
                <a href="{{ route('buku.index') }}" class="btn btn-light">Kembali</a>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('purple/assets/vendors/js/vendor.bundle.base.js') }}"></script>
</body>
</html>
