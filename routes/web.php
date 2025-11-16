<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua controller yang kita gunakan
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\IzinController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Karyawan\IzinController as KaryawanIzinController;
use App\Http\Controllers\Karyawan\AbsenController as KaryawanAbsenController; // <-- Ini penting
use App\Http\Controllers\Admin\RekapController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Utama
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================================
// RUTE TAMU (BELUM LOGIN)
// ==========================================================
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.process');
});


// ==========================================================
// RUTE UNTUK USER YANG SUDAH LOGIN
// ==========================================================
Route::middleware('auth')->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function() {
        if(Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('karyawan.dashboard');
        }
    })->name('dashboard');

    // ==========================================================
    // === GRUP RUTE KHUSUS ADMIN ===
    // ==========================================================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        Route::get('/dashboard', function () {return view('admin.dashboard'); })->name('admin.dashboard');

        // --- CRUD Akun Karyawan ---
        Route::get('/kelola-akun', [AkunController::class, 'index'])->name('admin.akun.index');
        Route::get('/kelola-akun/tambah', [AkunController::class, 'create'])->name('admin.akun.create');
        Route::post('/kelola-akun', [AkunController::class, 'store'])->name('admin.akun.store');
        Route::get('/kelola-akun/{user}/edit', [AkunController::class, 'edit'])->name('admin.akun.edit');
        Route::put('/kelola-akun/{user}', [AkunController::class, 'update'])->name('admin.akun.update');
        Route::post('/kelola-akun/{user}/reset', [AkunController::class, 'resetPassword'])->name('admin.akun.reset');
        Route::post('/kelola-akun/{user}/toggle', [AkunController::class, 'toggleStatus'])->name('admin.akun.toggle');
        
        // --- Approval Izin ---
        Route::get('/approval-izin', [IzinController::class, 'index'])->name('admin.izin.index');
        Route::post('/approval-izin/{izin}/setujui', [IzinController::class, 'setujui'])->name('admin.izin.setujui');
        Route::post('/approval-izin/{izin}/tolak', [IzinController::class, 'tolak'])->name('admin.izin.tolak');
        Route::get('/riwayat-izin', [IzinController::class, 'riwayat'])->name('admin.izin.riwayat');

        // --- Laporan & Jadwal ---
       Route::get('/rekap-laporan', [RekapController::class, 'index'])->name('admin.laporan.index');
        Route::get('/kelola-jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
        Route::put('/kelola-jadwal', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    
    }); // <-- Akhir Grup Admin


    // ==========================================================
    // === GRUP RUTE KHUSUS KARYAWAN ===
    // ==========================================================
    Route::middleware('role:karyawan')->prefix('karyawan')->group(function () {
        
        // Rute dashboard SEKARANG menunjuk ke 'AbsenController'
        Route::get('/dashboard', [KaryawanAbsenController::class, 'index'])->name('karyawan.dashboard');

        // --- Fitur Pengajuan Izin / Sakit ---
        Route::get('/izin/riwayat', [KaryawanIzinController::class, 'index'])->name('karyawan.izin.riwayat');
        Route::get('/izin/tambah', [KaryawanIzinController::class, 'create'])->name('karyawan.izin.create');
        Route::post('/izin', [KaryawanIzinController::class, 'store'])->name('karyawan.izin.store');
        
        // --- Fitur Absensi Selfie ---
        Route::post('/absen/masuk', [KaryawanAbsenController::class, 'storeMasuk'])->name('karyawan.absen.masuk');
        Route::post('/absen/keluar', [KaryawanAbsenController::class, 'storeKeluar'])->name('karyawan.absen.keluar'); 
        
    }); // <-- Akhir dari Grup Karyawan

}); // <-- Akhir dari Grup 'auth'