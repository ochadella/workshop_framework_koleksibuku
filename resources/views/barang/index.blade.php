@extends('layouts.app')

@section('title', 'Data Barang')

@push('styles')
<style>
  body.modal-open { overflow: hidden !important; padding-right: 0 !important; }
  .container-scroller, .page-body-wrapper, .main-panel, .content-wrapper { overflow: visible !important; }
  .modal-dialog { max-width: 700px; }
  .modal-content { max-height: calc(100vh - 140px); overflow: hidden; }
  .modal-body { overflow-y: auto; max-height: calc(100vh - 240px); }

  /* DataTables biar rapi */
  .dataTables_wrapper .dataTables_filter input { margin-left: 8px; }
  .dataTables_wrapper .dataTables_length select { margin: 0 6px; }
</style>

<!-- DataTables CSS (Bootstrap 4 friendly) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')

  <div class="page-header">
    <h3 class="page-title">Data Barang (Toko Buku)</h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Barang</li>
      </ul>
    </nav>
  </div>

  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
      <i class="mdi mdi-arrow-left"></i> Kembali
    </a>

    <button type="button" class="btn btn-gradient-primary btn-sm" data-toggle="modal" data-target="#modalTambahBarang">
      <i class="mdi mdi-plus"></i> Tambah Barang
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
      <h4 class="card-title">Daftar Barang</h4>

      {{-- ✅ FORM CETAK LABEL (sesuai modul: pilih data + input X,Y) --}}
      <form action="{{ route('barang.cetakLabel') }}" method="POST" target="_blank" class="mb-3" id="formCetakLabel">
        @csrf
        <div class="row align-items-end">
          <div class="col-md-2 mb-2">
            <label class="mb-1">X (Kolom)</label>
            <input type="number" name="x" class="form-control" value="1" min="1" max="5" required>
          </div>
          <div class="col-md-2 mb-2">
            <label class="mb-1">Y (Baris)</label>
            <input type="number" name="y" class="form-control" value="1" min="1" max="8" required>
          </div>
          <div class="col-md-8 mb-2 d-flex gap-2">
            <button type="submit" class="btn btn-success btn-sm">
              <i class="mdi mdi-printer"></i> Cetak Label
            </button>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        <table id="barangTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th width="60">
                <input type="checkbox" id="checkAll">
              </th>
              <th width="120">ID Barang</th>
              <th>Nama</th>
              <th width="140">Harga</th>
              <th>Deskripsi</th>
              <th width="220">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($barangs as $item)
              <tr>
                <td>
                  {{-- ✅ FIX: value pakai id_barang (BRG000001), bukan $item->id --}}
                  <input type="checkbox" name="ids[]" value="{{ $item->id_barang }}" class="checkItem" form="formCetakLabel">
                </td>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-sm btn-warning btn-edit-barang"
                    data-toggle="modal"
                    data-target="#modalEditBarang"
                    data-id="{{ $item->id }}"
                    data-nama="{{ $item->nama_barang }}"
                    data-harga="{{ $item->harga }}"
                    data-deskripsi="{{ $item->deskripsi }}"
                  >
                    Edit
                  </button>

                  <form action="{{ url('dashboard/barang/'.$item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')">Hapus</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data barang.</td>
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

  <!-- DataTables (Bootstrap 4) -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function () {
      // Delay sedikit biar DOM + template Purple selesai render dulu
      setTimeout(function () {
        // Kalau sudah pernah di-init, pakai instance yang ada (jangan clear/destroy yg bikin tbody kosong)
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#barangTable')) {
          $('#barangTable').DataTable().columns.adjust().draw(false);
          return;
        }

        // Init pertama kali
        if ($.fn.DataTable) {
          $('#barangTable').DataTable({
            pageLength: 10,
            destroy: true,
            retrieve: true
          });
        }
      }, 150);

      // ✅ Check all
      $('#checkAll').on('change', function(){
        $('.checkItem').prop('checked', $(this).is(':checked'));
      });

      // ✅ Validasi: minimal pilih 1 item sebelum cetak
      $('#formCetakLabel').on('submit', function(e){
        if ($('.checkItem:checked').length === 0) {
          e.preventDefault();
          alert('Pilih minimal 1 barang untuk dicetak.');
        }
      });
    });
  </script>

  <!-- MODAL: TAMBAH BARANG -->
  <div class="modal fade" id="modalTambahBarang" tabindex="-1" role="dialog" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ url('dashboard/barang') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Nama Barang</label>
              <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan nama barang" required>
            </div>

            <div class="form-group mb-3">
              <label>Harga</label>
              <input type="number" name="harga" class="form-control" placeholder="Masukkan harga" required>
            </div>

            <div class="form-group mb-3">
              <label>Deskripsi (opsional)</label>
              <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi"></textarea>
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

  <!-- MODAL: EDIT BARANG -->
  <div class="modal fade" id="modalEditBarang" tabindex="-1" role="dialog" aria-labelledby="modalEditBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form id="formEditBarang" method="POST">
          @csrf
          @method('PUT')

          <div class="modal-header">
            <h5 class="modal-title" id="modalEditBarangLabel">Edit Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Nama Barang</label>
              <input type="text" name="nama_barang" id="editNamaBarang" class="form-control" required>
            </div>

            <div class="form-group mb-3">
              <label>Harga</label>
              <input type="number" name="harga" id="editHargaBarang" class="form-control" required>
            </div>

            <div class="form-group mb-3">
              <label>Deskripsi (opsional)</label>
              <textarea name="deskripsi" id="editDeskripsiBarang" class="form-control" rows="3"></textarea>
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
    $(document).on('click', '.btn-edit-barang', function () {
      var id = $(this).data('id');
      var nama = $(this).data('nama');
      var harga = $(this).data('harga');
      var deskripsi = $(this).data('deskripsi');

      $('#editNamaBarang').val(nama);
      $('#editHargaBarang').val(harga);
      $('#editDeskripsiBarang').val(deskripsi);

      $('#formEditBarang').attr('action', "{{ url('dashboard/barang') }}/" + id);
    });
  </script>

@endpush