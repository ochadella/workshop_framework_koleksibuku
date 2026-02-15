@extends('layouts.app')

@section('title', 'Data Buku')

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
    <h3 class="page-title">Data Buku</h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Buku</li>
      </ul>
    </nav>
  </div>

  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
      <i class="mdi mdi-arrow-left"></i> Kembali
    </a>
    <button type="button" class="btn btn-gradient-primary btn-sm" data-toggle="modal" data-target="#modalTambahBuku">
      <i class="mdi mdi-plus"></i> Tambah Buku
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
      <h4 class="card-title">Daftar Buku</h4>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Kode</th>
              <th>Judul</th>
              <th>Pengarang</th>
              <th>Kategori</th>
              <th width="220">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bukus as $i => $buku)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $buku->kode }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->pengarang }}</td>
                <td>{{ $buku->kategori->nama_kategori ?? '-' }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-sm btn-warning btn-edit"
                    data-toggle="modal"
                    data-target="#modalEditBuku"
                    data-id="{{ $buku->id }}"
                    data-kode="{{ $buku->kode }}"
                    data-judul="{{ $buku->judul }}"
                    data-pengarang="{{ $buku->pengarang }}"
                    data-kategori_id="{{ $buku->kategori_id }}"
                  >
                    Edit
                  </button>
                  <form action="{{ url('dashboard/buku/'.$buku->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data buku.</td>
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

  <!-- MODAL: TAMBAH BUKU -->
  <div class="modal fade" id="modalTambahBuku" tabindex="-1" role="dialog" aria-labelledby="modalTambahBukuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ url('dashboard/buku') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahBukuLabel">Tambah Buku</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Kode</label>
              <input type="text" name="kode" class="form-control" placeholder="Masukkan kode" required>
            </div>
            <div class="form-group mb-3">
              <label>Judul Buku</label>
              <input type="text" name="judul" class="form-control" placeholder="Masukkan judul" required>
            </div>
            <div class="form-group mb-3">
              <label>Pengarang</label>
              <input type="text" name="pengarang" class="form-control" placeholder="Masukkan pengarang" required>
            </div>
            <div class="form-group mb-3">
              <label>Kategori</label>
              <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                  <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
              </select>
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

  <!-- MODAL: EDIT BUKU -->
  <div class="modal fade" id="modalEditBuku" tabindex="-1" role="dialog" aria-labelledby="modalEditBukuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="formEditBuku" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditBukuLabel">Edit Buku</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Kode</label>
              <input type="text" name="kode" id="editKode" class="form-control" required>
            </div>
            <div class="form-group mb-3">
              <label>Judul Buku</label>
              <input type="text" name="judul" id="editJudul" class="form-control" required>
            </div>
            <div class="form-group mb-3">
              <label>Pengarang</label>
              <input type="text" name="pengarang" id="editPengarang" class="form-control" required>
            </div>
            <div class="form-group mb-3">
              <label>Kategori</label>
              <select name="kategori_id" id="editKategoriId" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                  <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
              </select>
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
    $(document).on('click', '.btn-edit', function () {
      var id          = $(this).data('id');
      var kode        = $(this).data('kode');
      var judul       = $(this).data('judul');
      var pengarang   = $(this).data('pengarang');
      var kategori_id = $(this).data('kategori_id');

      $('#editKode').val(kode);
      $('#editJudul').val(judul);
      $('#editPengarang').val(pengarang);
      $('#editKategoriId').val(kategori_id);

      $('#formEditBuku').attr('action', "{{ url('dashboard/buku') }}/" + id);
    });
  </script>

@endpush