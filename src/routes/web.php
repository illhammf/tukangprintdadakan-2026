<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\PesananController;
use App\Http\Controllers\Customer\ProfilController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\KontakController;
use App\Http\Controllers\Frontend\LayananController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Livewire Asset Handling
|--------------------------------------------------------------------------
| Jangan dihapus. Ini bawaan boilerplate untuk handling Livewire asset.
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});

/*
|--------------------------------------------------------------------------
| Public Website
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tentang-kami', [HomeController::class, 'tentang'])->name('tentang');

Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');

Route::get('/layanan/{layanan:slug}', [LayananController::class, 'show'])->name('layanan.show');

Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');

Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

/*
|--------------------------------------------------------------------------
| Customer Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.store');

    Route::get('/registrasi', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/registrasi', [CustomerAuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Area
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('pelanggan')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/buat', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan/{pesanan}', [PesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{pesanan}/batal', [PesananController::class, 'cancel'])->name('pesanan.cancel');
    });