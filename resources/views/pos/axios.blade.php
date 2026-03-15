@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Modul 5 - POS Axios</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">POS Axios</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Point Of Sale (POS)</h4>
                <p class="card-description">
                    Masukkan kode barang, tekan Enter, lalu tambahkan ke tabel transaksi menggunakan Axios.
                </p>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="kode_barang" class="form-label">Kode Barang</label>
                        <input type="text" id="kode_barang" class="form-control" placeholder="Masukkan kode barang">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" id="nama_barang" class="form-control" readonly>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" id="harga" class="form-control" readonly>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" id="jumlah" class="form-control" value="1" min="1">
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" id="btn-tambah" class="btn btn-gradient-primary w-100" disabled>
                            Tambahkan
                        </button>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="tabel-transaksi">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-end">
                    <h4>Total: Rp <span id="total">0</span></h4>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-success" id="btn-bayar">
                        Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kodeBarang = document.getElementById('kode_barang');
    const namaBarang = document.getElementById('nama_barang');
    const harga = document.getElementById('harga');
    const jumlah = document.getElementById('jumlah');
    const btnTambah = document.getElementById('btn-tambah');
    const btnBayar = document.getElementById('btn-bayar');
    const tabelBody = document.querySelector('#tabel-transaksi tbody');
    const totalEl = document.getElementById('total');

    function resetForm() {
        kodeBarang.value = '';
        namaBarang.value = '';
        harga.value = '';
        jumlah.value = 1;
        btnTambah.disabled = true;
        kodeBarang.focus();
    }

    function formatRupiah(angka) {
        return parseInt(angka).toLocaleString('id-ID');
    }

    function hitungTotal() {
        let total = 0;

        document.querySelectorAll('#tabel-transaksi tbody tr').forEach(function (row) {
            total += parseInt(row.querySelector('.subtotal').dataset.subtotal);
        });

        totalEl.textContent = formatRupiah(total);
    }

    kodeBarang.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            let kode = kodeBarang.value;

            if (kode === '') {
                alert('Kode barang harus diisi');
                return;
            }

            axios.get("{{ url('/dashboard/get-barang') }}/" + kode)
                .then(function (response) {
                    namaBarang.value = response.data.data.nama_barang;
                    harga.value = response.data.data.harga;
                    jumlah.value = 1;
                    btnTambah.disabled = false;
                })
                .catch(function (error) {
                    namaBarang.value = '';
                    harga.value = '';
                    jumlah.value = 1;
                    btnTambah.disabled = true;
                    alert('Barang tidak ditemukan');
                    console.log(error);
                });
        }
    });

    btnTambah.addEventListener('click', function () {
        let kode = kodeBarang.value;
        let nama = namaBarang.value;
        let hargaValue = parseInt(harga.value);
        let jumlahValue = parseInt(jumlah.value);

        if (!kode || !nama || !hargaValue || jumlahValue < 1) {
            alert('Data barang belum lengkap');
            return;
        }

        let subtotal = hargaValue * jumlahValue;
        let barisLama = tabelBody.querySelector('tr[data-kode="' + kode + '"]');

        if (barisLama) {
            let jumlahInput = barisLama.querySelector('.jumlah-input');
            let jumlahLama = parseInt(jumlahInput.value);
            let jumlahBaru = jumlahLama + jumlahValue;
            let subtotalBaru = hargaValue * jumlahBaru;

            jumlahInput.value = jumlahBaru;
            barisLama.querySelector('.subtotal').dataset.subtotal = subtotalBaru;
            barisLama.querySelector('.subtotal').textContent = formatRupiah(subtotalBaru);
        } else {
            let row = `
                <tr data-kode="${kode}">
                    <td class="kode">${kode}</td>
                    <td class="nama_barang">${nama}</td>
                    <td class="harga" data-harga="${hargaValue}">${formatRupiah(hargaValue)}</td>
                    <td>
                        <input type="number" class="form-control jumlah-input" value="${jumlahValue}" min="1">
                    </td>
                    <td class="subtotal" data-subtotal="${subtotal}">${formatRupiah(subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                    </td>
                </tr>
            `;

            tabelBody.insertAdjacentHTML('beforeend', row);
        }

        hitungTotal();
        resetForm();
    });

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('jumlah-input')) {
            let row = e.target.closest('tr');
            let hargaRow = parseInt(row.querySelector('.harga').dataset.harga);
            let jumlahRow = parseInt(e.target.value);

            if (jumlahRow < 1 || isNaN(jumlahRow)) {
                jumlahRow = 1;
                e.target.value = 1;
            }

            let subtotal = hargaRow * jumlahRow;

            row.querySelector('.subtotal').dataset.subtotal = subtotal;
            row.querySelector('.subtotal').textContent = formatRupiah(subtotal);

            hitungTotal();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-hapus')) {
            e.target.closest('tr').remove();
            hitungTotal();
        }
    });

    btnBayar.addEventListener('click', function () {
        if (document.querySelectorAll('#tabel-transaksi tbody tr').length < 1) {
            alert('Belum ada transaksi');
            return;
        }

        let items = [];
        let total = 0;

        document.querySelectorAll('#tabel-transaksi tbody tr').forEach(function (row) {
            let kode = row.querySelector('.kode').textContent;
            let nama_barang = row.querySelector('.nama_barang').textContent;
            let hargaRow = parseInt(row.querySelector('.harga').dataset.harga);
            let jumlahRow = parseInt(row.querySelector('.jumlah-input').value);
            let subtotal = parseInt(row.querySelector('.subtotal').dataset.subtotal);

            total += subtotal;

            items.push({
                kode: kode,
                nama_barang: nama_barang,
                harga: hargaRow,
                jumlah: jumlahRow,
                subtotal: subtotal
            });
        });

        axios.post("{{ route('pos.simpan') }}", {
            total: total,
            items: items,
            _token: "{{ csrf_token() }}"
        })
        .then(function (response) {
            alert(response.data.message);
            tabelBody.innerHTML = '';
            hitungTotal();
            resetForm();
        })
        .catch(function (error) {
            console.log(error);
            alert('Gagal menyimpan transaksi');
        });
    });

    resetForm();
});
</script>
@endsection