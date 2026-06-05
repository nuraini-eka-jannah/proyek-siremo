<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MobilController;
use App\Http\Controllers\Admin\PenyewaController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\AdminProfilController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Register ──────────────────────────────────────────────────────
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister']);

// ── Lupa Password (kirim link via email) ──────────────────────────
Route::get('/forgot-password',  [AuthController::class, 'showForgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

// ── Reset Password (form isi password baru) ───────────────────────
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'processResetPassword'])
    ->name('password.update');
/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Penyewa (User biasa)
    |----------------------------------------------------------------------
    */
    Route::get('/penyewa/home', function () {
        return view('penyewa.home');
    })->name('penyewa.home');

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // Redirect /admin ke dashboard
        Route::redirect('/', '/admin/dashboard');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Dashboard API: polling stat card & notifikasi
        Route::get('/stat-terbaru',           [DashboardController::class, 'statTerbaru'])
            ->name('stat-terbaru');
        Route::get('/notifikasi/terbaru',     [DashboardController::class, 'notifikasiTerbaru'])
            ->name('notifikasi.terbaru');
        Route::post('/notifikasi/baca-semua', [DashboardController::class, 'bacaSemuaNotifikasi'])
            ->name('notifikasi.baca-semua');

        // Laporan PDF
        Route::get('/laporan',       [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

        // Edit Profil Admin
        Route::post('/profil/update', [AdminProfilController::class, 'updateProfil'])->name('profil.update');

        // Kendaraan (Mobil)
        // GET    /admin/kendaraan            → index
        // GET    /admin/kendaraan/create      → create (form tambah)
        // POST   /admin/kendaraan             → store
        // GET    /admin/kendaraan/{id}        → show
        // GET    /admin/kendaraan/{id}/edit   → edit
        // PUT    /admin/kendaraan/{id}        → update
        // DELETE /admin/kendaraan/{id}        → destroy
        Route::resource('kendaraan', MobilController::class);

        // Pelanggan (Penyewa)
        Route::resource('pelanggan', PenyewaController::class);

        // Transaksi Sewa
        Route::resource('transaksi', TransaksiController::class);
        Route::patch('/transaksi/{transaksi}/selesai', [TransaksiController::class, 'selesai'])
            ->name('transaksi.selesai');

        // Pengembalian
        Route::get('/pengembalian', [PengembalianController::class, 'index'])
            ->name('pengembalian.index');
        Route::get('/pengembalian/{transaksi}', [PengembalianController::class, 'show'])
            ->name('pengembalian.show');
        Route::post('/pengembalian/{transaksi}', [PengembalianController::class, 'proses'])
            ->name('pengembalian.proses');

        // Ulasan
        Route::get('/ulasan', [UlasanController::class, 'index'])
            ->name('ulasan.index');
        Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])
            ->name('ulasan.destroy');

    }); // end prefix admin

}); // end middleware auth