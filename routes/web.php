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
use App\Http\Controllers\PesananController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminVendorController;
use App\Models\Vendor;
use App\Models\Menu;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Root (Halaman utama sebelum login = welcome)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->role === 'vendor') {
            return redirect()->route('vendor.index');
        }

        return redirect()->route('dashboard');
    }

    $vendors = Vendor::orderBy('nama_vendor')->get();

    return view('welcome', compact('vendors'));
})->name('welcome');

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
| Callback Midtrans
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [PesananController::class, 'callback'])->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| Customer Page (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/pesan', function () {
    $vendors = Vendor::orderBy('nama_vendor')->get();
    return view('welcome', compact('vendors'));
})->name('customer.pesan');

Route::get('/get-menu/{vendor}', function ($vendor) {
    return Menu::where('vendor_id', $vendor)
        ->orderBy('nama_menu')
        ->get();
})->name('customer.getMenu');

Route::post('/checkout', [PesananController::class, 'checkout'])->name('customer.checkout');
Route::post('/check-status', [PesananController::class, 'checkStatus'])->name('customer.checkStatus');
Route::post('/midtrans/callback', [PesananController::class, 'callback'])->name('midtrans.callback');
Route::post('/bayar-sukses/{id}', [PesananController::class, 'bayarSukses'])->name('customer.bayarSukses');


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

    // vendor
    Route::get('/adminvendor', [AdminVendorController::class, 'index'])->name('adminvendor.index');
    Route::get('/adminvendor/create', [AdminVendorController::class, 'create'])->name('adminvendor.create');
    Route::post('/adminvendor', [AdminVendorController::class, 'store'])->name('adminvendor.store');
    Route::get('/adminvendor/{id}/edit', [AdminVendorController::class, 'edit'])->name('adminvendor.edit');
    Route::put('/adminvendor/{id}', [AdminVendorController::class, 'update'])->name('adminvendor.update');
    Route::delete('/adminvendor/{id}', [AdminVendorController::class, 'destroy'])->name('adminvendor.destroy');

    Route::get('/dashboard/adminvendor/{id}/pesanan', [AdminVendorController::class, 'pesanan'])->name('adminvendor.pesanan');
    Route::get('/dashboard/adminvendor/{vendorId}/pesanan/{pesananId}', [AdminVendorController::class, 'pesananDetail'])->name('adminvendor.pesanan.detail');

});

Route::middleware('auth')->prefix('vendor')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('vendor.index');

    Route::post('/menu', [VendorController::class, 'storeMenu'])->name('vendor.menu.store');
    Route::delete('/menu/{id}', [VendorController::class, 'deleteMenu'])->name('vendor.menu.delete');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('vendor.pesanan');
    Route::post('/pesanan/{id}/lunas', [PesananController::class, 'lunas'])->name('vendor.pesanan.lunas');
    
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('vendor.pesanan.show');
    Route::get('/pesanan/{id}/struk', [PesananController::class, 'cetakStruk'])->name('vendor.pesanan.struk');
});