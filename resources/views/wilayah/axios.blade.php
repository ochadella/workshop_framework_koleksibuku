@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Modul 5 - Axios</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cascading Wilayah Axios</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cascading Wilayah Indonesia</h4>
                <p class="card-description">
                    Pilih Provinsi, lalu Kota, Kecamatan, dan Kelurahan akan tampil otomatis menggunakan Axios.
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
                    <strong>Info:</strong> Halaman ini menggunakan Axios untuk mengambil data wilayah secara bertahap tanpa reload halaman.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Wilayah Axios page loaded');

    const provinsi = document.getElementById('provinsi');
    const kota = document.getElementById('kota');
    const kecamatan = document.getElementById('kecamatan');
    const kelurahan = document.getElementById('kelurahan');
    const btnReset = document.getElementById('btn-reset');

    function resetKota() {
        kota.innerHTML = '<option value="">Pilih Kota</option>';
    }

    function resetKecamatan() {
        kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
    }

    function resetKelurahan() {
        kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';
    }

    function loadProvinces() {
        axios.get("{{ url('/dashboard/get-provinces') }}")
            .then(function (response) {
                provinsi.innerHTML = '<option value="">Pilih Provinsi</option>';

                response.data.forEach(function (item) {
                    provinsi.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });

                console.log('Provinsi loaded:', response.data);
            })
            .catch(function (error) {
                console.log('Error provinces:', error);
                alert('Gagal mengambil data provinsi');
            });
    }

    loadProvinces();

    provinsi.addEventListener('change', function () {
        let provinceId = this.value;
        console.log('Provinsi dipilih:', provinceId);

        resetKota();
        resetKecamatan();
        resetKelurahan();

        if (provinceId === '') {
            return;
        }

        axios.get("{{ url('/dashboard/get-cities') }}/" + provinceId)
            .then(function (response) {
                console.log('Kota loaded:', response.data);

                response.data.forEach(function (item) {
                    kota.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            })
            .catch(function (error) {
                console.log('Error cities:', error);
                alert('Gagal mengambil data kota');
            });
    });

    kota.addEventListener('change', function () {
        let cityId = this.value;
        console.log('Kota dipilih:', cityId);

        resetKecamatan();
        resetKelurahan();

        if (cityId === '') {
            return;
        }

        axios.get("{{ url('/dashboard/get-districts') }}/" + cityId)
            .then(function (response) {
                console.log('Kecamatan loaded:', response.data);

                response.data.forEach(function (item) {
                    kecamatan.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            })
            .catch(function (error) {
                console.log('Error districts:', error);
                alert('Gagal mengambil data kecamatan');
            });
    });

    kecamatan.addEventListener('change', function () {
        let districtId = this.value;
        console.log('Kecamatan dipilih:', districtId);

        resetKelurahan();

        if (districtId === '') {
            return;
        }

        axios.get("{{ url('/dashboard/get-villages') }}/" + districtId)
            .then(function (response) {
                console.log('Kelurahan loaded:', response.data);

                response.data.forEach(function (item) {
                    kelurahan.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            })
            .catch(function (error) {
                console.log('Error villages:', error);
                alert('Gagal mengambil data kelurahan');
            });
    });

    btnReset.addEventListener('click', function () {
        provinsi.value = '';
        resetKota();
        resetKecamatan();
        resetKelurahan();
    });
});
</script>
@endsection