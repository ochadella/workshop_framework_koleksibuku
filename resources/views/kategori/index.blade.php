@extends('layouts.app')

@section('title', 'Data Kategori')

@push('styles')
<style>
  body.modal-open { overflow: hidden !important; padding-right: 0 !important; }
  .container-scroller, .page-body-wrapper, .main-panel, .content-wrapper { overflow: visible !important; }
  .modal-dialog { max-width: 600px; }
  .modal-content { max-height: calc(100vh - 140px); overflow: hidden; }
  .modal-body { overflow-y: auto; max-height: calc(100vh - 240px); }
</style>
@endpush

@section('content')

  <div class="page-header">
    <h3 class="page-title">Data Kategori</h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Kategori</li>
      </ul>
    </nav>
  </div>

  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
      <i class="mdi mdi-arrow-left"></i> Kembali
    </a>
    <button type="button" class="btn btn-gradient-primary btn-sm" data-toggle="modal" data-target="#modalTambahKategori">
      <i class="mdi mdi-plus"></i> Tambah Kategori
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Daftar Kategori</h4>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Kategori</th>
              <th width="220">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kategoris as $i => $kategori)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $kategori->nama_kategori }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-sm btn-warning btn-edit-kategori"
                    data-toggle="modal"
                    data-target="#modalEditKategori"
                    data-id="{{ $kategori->id }}"
                    data-nama="{{ $kategori->nama_kategori }}"
                  >
                    Edit
                  </button>
                  <form action="{{ url('dashboard/kategori/'.$kategori->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted">Belum ada data kategori.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection

@push('scripts')

  <!-- WAJIB URUT (Bootstrap 4) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- MODAL: TAMBAH KATEGORI -->
  <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ url('dashboard/kategori') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Nama Kategori</label>
              <input type="text" name="nama" class="form-control" placeholder="Masukkan nama kategori" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-gradient-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL: EDIT KATEGORI -->
  <div class="modal fade" id="modalEditKategori" tabindex="-1" role="dialog" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="formEditKategori" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditKategoriLabel">Edit Kategori</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Nama Kategori</label>
              <input type="text" name="nama" id="editNamaKategori" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    $(document).on('click', '.btn-edit-kategori', function () {
      var id   = $(this).data('id');
      var nama = $(this).data('nama');
      $('#editNamaKategori').val(nama);
      $('#formEditKategori').attr('action', "{{ url('dashboard/kategori') }}/" + id);
    });
  </script>

@endpush