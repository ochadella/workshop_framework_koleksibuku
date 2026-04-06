@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Modul 5 - POS AJAX jQuery</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">POS AJAX</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Point Of Sale (POS)</h4>
                <p class="card-description">
                    Masukkan kode barang, tekan Enter, lalu tambahkan ke tabel transaksi.
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
$(function () {
    function resetForm() {
        $('#kode_barang').val('');
        $('#nama_barang').val('');
        $('#harga').val('');
        $('#jumlah').val(1);
        $('#btn-tambah').prop('disabled', true);
        $('#kode_barang').focus();
    }

    function formatRupiah(angka) {
        return parseInt(angka).toLocaleString('id-ID');
    }

    function hitungTotal() {
        let total = 0;

        $('#tabel-transaksi tbody tr').each(function () {
            total += parseInt($(this).find('.subtotal').data('subtotal'));
        });

        $('#total').text(formatRupiah(total));
    }

    $('#kode_barang').on('keypress', function (e) {
        if (e.which == 13) {
            e.preventDefault();

            let kode = $(this).val();

            if (kode === '') {
                alert('Kode barang harus diisi');
                return;
            }

            $.ajax({
                url: "{{ url('/dashboard/get-barang') }}/" + kode,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    $('#nama_barang').val(response.data.nama_barang);
                    $('#harga').val(response.data.harga);
                    $('#jumlah').val(1);
                    $('#btn-tambah').prop('disabled', false);
                },
                error: function () {
                    $('#nama_barang').val('');
                    $('#harga').val('');
                    $('#jumlah').val(1);
                    $('#btn-tambah').prop('disabled', true);
                    alert('Barang tidak ditemukan');
                }
            });
        }
    });

    $('#btn-tambah').on('click', function () {
        let kode = $('#kode_barang').val();
        let nama = $('#nama_barang').val();
        let harga = parseInt($('#harga').val());
        let jumlah = parseInt($('#jumlah').val());

        if (!kode || !nama || !harga || jumlah < 1) {
            alert('Data barang belum lengkap');
            return;
        }

        let subtotal = harga * jumlah;
        let barisLama = $('#tabel-transaksi tbody').find('tr[data-kode="' + kode + '"]');

        if (barisLama.length > 0) {
            let jumlahLama = parseInt(barisLama.find('.jumlah-input').val());
            let jumlahBaru = jumlahLama + jumlah;
            let subtotalBaru = harga * jumlahBaru;

            barisLama.find('.jumlah-input').val(jumlahBaru);
            barisLama.find('.subtotal')
                .data('subtotal', subtotalBaru)
                .text(formatRupiah(subtotalBaru));
        } else {
            let row = `
                <tr data-kode="${kode}">
                    <td class="kode">${kode}</td>
                    <td class="nama_barang">${nama}</td>
                    <td class="harga" data-harga="${harga}">${formatRupiah(harga)}</td>
                    <td>
                        <input type="number" class="form-control jumlah-input" value="${jumlah}" min="1">
                    </td>
                    <td class="subtotal" data-subtotal="${subtotal}">${formatRupiah(subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                    </td>
                </tr>
            `;

            $('#tabel-transaksi tbody').append(row);
        }

        hitungTotal();
        resetForm();
    });

    $(document).on('input', '.jumlah-input', function () {
        let row = $(this).closest('tr');
        let harga = parseInt(row.find('.harga').data('harga'));
        let jumlah = parseInt($(this).val());

        if (jumlah < 1 || isNaN(jumlah)) {
            jumlah = 1;
            $(this).val(1);
        }

        let subtotal = harga * jumlah;

        row.find('.subtotal')
            .data('subtotal', subtotal)
            .text(formatRupiah(subtotal));

        hitungTotal();
    });

    $(document).on('click', '.btn-hapus', function () {
        $(this).closest('tr').remove();
        hitungTotal();
    });

    $('#btn-bayar').on('click', function () {
        if ($('#tabel-transaksi tbody tr').length < 1) {
            alert('Belum ada transaksi');
            return;
        }

        let items = [];
        let total = 0;

        $('#tabel-transaksi tbody tr').each(function () {
            let row = $(this);

            let kode = row.find('.kode').text();
            let nama_barang = row.find('.nama_barang').text();
            let harga = parseInt(row.find('.harga').data('harga'));
            let jumlah = parseInt(row.find('.jumlah-input').val());
            let subtotal = parseInt(row.find('.subtotal').data('subtotal'));

            total += subtotal;

            items.push({
                kode: kode,
                nama_barang: nama_barang,
                harga: harga,
                jumlah: jumlah,
                subtotal: subtotal
            });
        });

        $.ajax({
            url: "{{ route('pos.simpan') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                total: total,
                items: items
            },
            success: function (response) {
                if (response.snap_token) {
                    snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            alert('Pembayaran berhasil');
                            $('#tabel-transaksi tbody').html('');
                            hitungTotal();
                            resetForm();
                        },
                        onPending: function(result) {
                            alert('Pembayaran pending, silakan selesaikan pembayaran');
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal');
                        },
                        onClose: function() {
                            alert('Popup pembayaran ditutup');
                        }
                    });
                } else {
                    alert(response.message + (response.error ? '\n' + response.error : ''));
                    $('#tabel-transaksi tbody').html('');
                    hitungTotal();
                    resetForm();
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);

                let pesan = 'Gagal menyimpan transaksi';

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.error) {
                        pesan += '\n' + xhr.responseJSON.error;
                    } else if (xhr.responseJSON.message) {
                        pesan += '\n' + xhr.responseJSON.message;
                    }
                }

                alert(pesan);
            }
        });
    });

    resetForm();
});
</script>
@endsection