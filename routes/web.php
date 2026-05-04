<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminVendorController;
use App\Http\Controllers\CustomerController; // ✅ TAMBAHAN CUSTOMER
use App\Models\Vendor;
use App\Models\Menu;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->role === 'vendor') {
            return redirect()->route('vendor.index');
        }

        if ($user->role === 'customer') {
            return redirect()->route('customer.pesan');
        }

        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Redirect Default Auth Home
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    $user = auth()->user();

    if ($user && $user->role === 'vendor') {
        return redirect()->route('vendor.index');
    }

    if ($user && $user->role === 'customer') {
        return redirect()->route('customer.pesan');
    }

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
| Customer Page (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/pesan', function () {
        if (auth()->user()->role !== 'customer') {
            return redirect()->route('dashboard')
                ->with('error', 'Halaman ini hanya untuk customer.');
        }

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
    Route::post('/bayar-sukses/{id}', [PesananController::class, 'bayarSukses'])->name('customer.bayarSukses');

    Route::get('/customer/riwayat', [PesananController::class, 'riwayatCustomer'])
        ->name('customer.riwayat');

    Route::get('/customer/pesanan/{id}/struk', [PesananController::class, 'cetakStrukCustomer'])
        ->name('customer.struk');

    /*
    |--------------------------------------------------------------------------
    | MODUL 8 - QR Code Pesanan Customer Bisa Diakses Ulang
    |--------------------------------------------------------------------------
    */
    Route::get('/pesanan/qrcode/{id}', [PesananController::class, 'showQrCode'])
        ->name('pesanan.qrcode');
});

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
    | MODUL 8 - Scan Barcode Barang
    |--------------------------------------------------------------------------
    */
    Route::get('/barang/scan-barcode', [BarangController::class, 'scanBarcode'])
        ->name('barang.scanBarcode');

    Route::get('/barang/cari-barcode/{id}', [BarangController::class, 'cariBarcode'])
        ->name('barang.cariBarcode');

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

    Route::get('/wilayah-axios', function () {
        return view('wilayah.axios');
    })->name('wilayah.axios');

    Route::get('/get-provinces', [WilayahController::class, 'getProvinces'])->name('wilayah.getProvinces');
    Route::get('/get-cities/{province}', [WilayahController::class, 'getCities'])->name('wilayah.getCities');
    Route::get('/get-districts/{city}', [WilayahController::class, 'getDistricts'])->name('wilayah.getDistricts');
    Route::get('/get-villages/{district}', [WilayahController::class, 'getVillages'])->name('wilayah.getVillages');

    /*
    |--------------------------------------------------------------------------
    | Modul 5 - POS AJAX
    |--------------------------------------------------------------------------
    */
    Route::get('/pos', function () {
        return view('pos.index');
    })->name('pos.index');

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
    Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'cetakStruk'])->name('transaksi.struk');

    /*
    |--------------------------------------------------------------------------
    | Tag Harga UMKM (CRUD Barang)
    |--------------------------------------------------------------------------
    */
    Route::resource('barang', BarangController::class);

    /*
    |--------------------------------------------------------------------------
    | Admin Vendor
    |--------------------------------------------------------------------------
    */
    Route::get('/adminvendor', [AdminVendorController::class, 'index'])->name('adminvendor.index');
    Route::get('/adminvendor/create', [AdminVendorController::class, 'create'])->name('adminvendor.create');
    Route::post('/adminvendor', [AdminVendorController::class, 'store'])->name('adminvendor.store');
    Route::get('/adminvendor/{id}/edit', [AdminVendorController::class, 'edit'])->name('adminvendor.edit');
    Route::put('/adminvendor/{id}', [AdminVendorController::class, 'update'])->name('adminvendor.update');
    Route::delete('/adminvendor/{id}', [AdminVendorController::class, 'destroy'])->name('adminvendor.destroy');

    Route::get('/dashboard/adminvendor/{id}/pesanan', [AdminVendorController::class, 'pesanan'])->name('adminvendor.pesanan');
    Route::get('/dashboard/adminvendor/{vendorId}/pesanan/{pesananId}', [AdminVendorController::class, 'pesananDetail'])->name('adminvendor.pesanan.detail');

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    | Data Customer
    | Tambah Customer 1 = simpan foto ke database/blob
    | Tambah Customer 2 = simpan file foto + path ke database
    |--------------------------------------------------------------------------
    */
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');

    Route::get('/customer/create-blob', [CustomerController::class, 'createBlob'])->name('customer.createBlob');
    Route::post('/customer/store-blob', [CustomerController::class, 'storeBlob'])->name('customer.storeBlob');

    Route::get('/customer/create-file', [CustomerController::class, 'createFile'])->name('customer.createFile');
    Route::post('/customer/store-file', [CustomerController::class, 'storeFile'])->name('customer.storeFile');

    Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
});

/*
|--------------------------------------------------------------------------
| Vendor Pages
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('vendor')->group(function () {
    Route::get('/', [VendorController::class, 'index'])->name('vendor.index');

    Route::post('/menu', [VendorController::class, 'storeMenu'])->name('vendor.menu.store');
    Route::delete('/menu/{id}', [VendorController::class, 'deleteMenu'])->name('vendor.menu.delete');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('vendor.pesanan');
    Route::post('/pesanan/{id}/lunas', [PesananController::class, 'lunas'])->name('vendor.pesanan.lunas');
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('vendor.pesanan.show');
    Route::get('/pesanan/{id}/struk', [PesananController::class, 'cetakStruk'])->name('vendor.pesanan.struk');

    /*
    |--------------------------------------------------------------------------
    | MODUL 8 - Scan QR Code Pesanan Customer oleh Vendor
    |--------------------------------------------------------------------------
    */
    Route::get('/scan-pesanan', [VendorController::class, 'scanPesanan'])
        ->name('vendor.scanPesanan');

    Route::get('/cari-pesanan/{id}', [VendorController::class, 'cariPesanan'])
        ->name('vendor.cariPesanan');
});