<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;

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
