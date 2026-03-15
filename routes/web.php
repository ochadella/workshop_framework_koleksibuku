<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PdfController; // ✅ TAMBAHAN
use App\Http\Controllers\BarangController; // ✅ TAMBAHAN
use App\Http\Controllers\WilayahController; // ✅ TAMBAHAN MODUL 5
use App\Http\Controllers\TransaksiController; // ✅ TAMBAHAN RIWAYAT TRANSAKSI

Auth::routes();

/*
|--------------------------------------------------------------------------
| Root (Halaman utama sebelum login = welcome)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Redirect Default Auth Home
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Google Login + OTP
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp', [OtpController::class, 'verify'])->name('otp.verify');

/*
|--------------------------------------------------------------------------
| Authenticated Pages
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PDF Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

    Route::get('/pdf', function () {
        return view('pdf.index');
    })->name('pdf.index');

    /*
    |--------------------------------------------------------------------------
    | UI Features
    |--------------------------------------------------------------------------
    */
    Route::prefix('ui')->group(function () {
        Route::get('/buttons', function () {
            return view('pages.ui-features.buttons');
        })->name('buttons');

        Route::get('/dropdowns', function () {
            return view('pages.ui-features.dropdowns');
        })->name('dropdowns');

        Route::get('/typography', function () {
            return view('pages.ui-features.typography');
        })->name('typography');
    });

    /*
    |--------------------------------------------------------------------------
    | Charts
    |--------------------------------------------------------------------------
    */
    Route::get('/charts', function () {
        return view('pages.charts.chartjs');
    })->name('charts');

    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    Route::get('/forms', function () {
        return view('pages.forms.basic_elements');
    })->name('forms');

    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    */
    Route::get('/tables', function () {
        return view('pages.tables.basic-table');
    })->name('tables');

    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    */
    Route::get('/icons/font-awesome', function () {
        return view('pages.icons.font-awesome');
    })->name('icons.fontawesome');

    /*
    |--------------------------------------------------------------------------
    | Blank Page
    |--------------------------------------------------------------------------
    */
    Route::get('/blank', function () {
        return view('pages.samples.blank-page');
    })->name('blank');

    /*
    |--------------------------------------------------------------------------
    | Welcome Page
    |--------------------------------------------------------------------------
    */
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    /*
    |--------------------------------------------------------------------------
    | Koleksi Buku (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    /*
    |--------------------------------------------------------------------------
    | Cetak Label Barang
    |--------------------------------------------------------------------------
    */
    Route::delete('barang/cetak-label', [BarangController::class, 'cetakLabel'])
        ->name('barang.cetakLabel.delete');

    Route::post('barang/cetak-label', [BarangController::class, 'cetakLabel'])
        ->name('barang.cetakLabel');

    /*
    |--------------------------------------------------------------------------
    | Modul 4 - Javascript & jQuery
    |--------------------------------------------------------------------------
    */
    Route::get('/form-html', function () {
        return view('barang.form-html');
    })->name('form-html');

    Route::get('/form-datatables', function () {
        return view('barang.form-datatables');
    })->name('form-datatables');

    Route::get('/select-kota', function () {
        return view('barang.select-kota');
    })->name('select-kota');

    /*
    |--------------------------------------------------------------------------
    | Modul 5 - Cascading Wilayah
    |--------------------------------------------------------------------------
    */
    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');

    // ✅ TAMBAHAN: versi Axios
    Route::get('/wilayah-axios', function () {
        return view('wilayah.axios');
    })->name('wilayah.axios');

    Route::get('/get-provinces', [WilayahController::class, 'getProvinces']);
    Route::get('/get-cities/{province}', [WilayahController::class, 'getCities']);
    Route::get('/get-districts/{city}', [WilayahController::class, 'getDistricts']);
    Route::get('/get-villages/{district}', [WilayahController::class, 'getVillages']);

    /*
    |--------------------------------------------------------------------------
    | Modul 5 - POS AJAX
    |--------------------------------------------------------------------------
    */
    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos.index');

    // ✅ TAMBAHAN: versi Axios
    Route::get('/pos-axios', function () {
        return view('pos.axios');
    })->name('pos.axios');

    Route::get('/get-barang/{kode}', [BarangController::class, 'getBarangByKode'])
        ->name('barang.getByKode');

    Route::post('/pos/simpan', [BarangController::class, 'simpanTransaksi'])
        ->name('pos.simpan');

    /*
    |--------------------------------------------------------------------------
    | Riwayat Transaksi
    |--------------------------------------------------------------------------
    */
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');

    // ✅ TAMBAHAN: Cetak Struk PDF
    Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'cetakStruk'])->name('transaksi.struk');

    /*
    |--------------------------------------------------------------------------
    | Tag Harga UMKM (CRUD Barang)
    |--------------------------------------------------------------------------
    */
    Route::resource('barang', BarangController::class);

});