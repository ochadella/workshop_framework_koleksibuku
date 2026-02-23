<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PdfController; // ✅ TAMBAHAN

Auth::routes();

/*
|--------------------------------------------------------------------------
| Root (Halaman utama sebelum login = welcome)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Kalau sudah login, langsung ke dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    // Kalau belum login, tampilkan welcome (resources/views/welcome.blade.php)
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
| Google Login + OTP (di luar auth middleware, karena user belum login)
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp', [OtpController::class, 'verify'])->name('otp.verify');

/*
|--------------------------------------------------------------------------
| Authenticated Pages (prefix /dashboard)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard (pakai controller)
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PDF Routes (Studi Kasus 2) ✅ TAMBAHAN
    |--------------------------------------------------------------------------
    */
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

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
    | Welcome Page (di dalam dashboard + auth)
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
});