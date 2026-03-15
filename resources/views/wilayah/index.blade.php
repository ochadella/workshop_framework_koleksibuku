@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Modul 5 - AJAX jQuery</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cascading Wilayah</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cascading Wilayah Indonesia</h4>
                <p class="card-description">
                    Pilih Provinsi, lalu Kota, Kecamatan, dan Kelurahan akan tampil otomatis menggunakan AJAX jQuery.
                </p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <select id="provinsi" class="form-control">
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kota" class="form-label">Kota / Kabupaten</label>
                        <select id="kota" class="form-control">
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <select id="kecamatan" class="form-control">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kelurahan" class="form-label">Kelurahan</label>
                        <select id="kelurahan" class="form-control">
                            <option value="">Pilih Kelurahan</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-gradient-primary btn-sm" id="btn-reset">
                        Reset Pilihan
                    </button>
                </div>

                <hr class="my-4">

                <div class="alert alert-info mb-0">
                    <strong>Info:</strong> Halaman ini menggunakan AJAX jQuery untuk mengambil data wilayah secara bertahap tanpa reload halaman.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    console.log('Wilayah page loaded');

    function resetKota() {
        $('#kota').empty().append('<option value="">Pilih Kota</option>');
    }

    function resetKecamatan() {
        $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');
    }

    function resetKelurahan() {
        $('#kelurahan').empty().append('<option value="">Pilih Kelurahan</option>');
    }

    function loadProvinces() {
        $.ajax({
            url: "{{ url('/dashboard/get-provinces') }}",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $('#provinsi').empty().append('<option value="">Pilih Provinsi</option>');

                $.each(data, function (i, item) {
                    $('#provinsi').append(
                        '<option value="' + item.id + '">' + item.name + '</option>'
                    );
                });

                console.log('Provinsi loaded:', data);
            },
            error: function (xhr) {
                console.log('Error provinces:', xhr.responseText);
                alert('Gagal mengambil data provinsi');
            }
        });
    }

    loadProvinces();

    $(document).on('change', '#provinsi', function () {
        let provinceId = $(this).val();
        console.log('Provinsi dipilih:', provinceId);

        resetKota();
        resetKecamatan();
        resetKelurahan();

        if (provinceId === '') {
            return;
        }

        $.ajax({
            url: "{{ url('/dashboard/get-cities') }}/" + provinceId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log('Kota loaded:', data);

                $.each(data, function (i, item) {
                    $('#kota').append(
                        '<option value="' + item.id + '">' + item.name + '</option>'
                    );
                });
            },
            error: function (xhr) {
                console.log('Error cities:', xhr.responseText);
                alert('Gagal mengambil data kota');
            }
        });
    });

    $(document).on('change', '#kota', function () {
        let cityId = $(this).val();
        console.log('Kota dipilih:', cityId);

        resetKecamatan();
        resetKelurahan();

        if (cityId === '') {
            return;
        }

        $.ajax({
            url: "{{ url('/dashboard/get-districts') }}/" + cityId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log('Kecamatan loaded:', data);

                $.each(data, function (i, item) {
                    $('#kecamatan').append(
                        '<option value="' + item.id + '">' + item.name + '</option>'
                    );
                });
            },
            error: function (xhr) {
                console.log('Error districts:', xhr.responseText);
                alert('Gagal mengambil data kecamatan');
            }
        });
    });

    $(document).on('change', '#kecamatan', function () {
        let districtId = $(this).val();
        console.log('Kecamatan dipilih:', districtId);

        resetKelurahan();

        if (districtId === '') {
            return;
        }

        $.ajax({
            url: "{{ url('/dashboard/get-villages') }}/" + districtId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log('Kelurahan loaded:', data);

                $.each(data, function (i, item) {
                    $('#kelurahan').append(
                        '<option value="' + item.id + '">' + item.name + '</option>'
                    );
                });
            },
            error: function (xhr) {
                console.log('Error villages:', xhr.responseText);
                alert('Gagal mengambil data kelurahan');
            }
        });
    });

    $(document).on('click', '#btn-reset', function () {
        $('#provinsi').val('');
        resetKota();
        resetKecamatan();
        resetKelurahan();
    });
});
</script>
@endsection