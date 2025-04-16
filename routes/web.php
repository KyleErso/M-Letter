<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\InstitutionController;
use App\Http\Controllers\Mahasiswa\MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\SuratKeteranganAktifController;
use App\Http\Controllers\Mahasiswa\SuratPengantarTugasController;
use App\Http\Controllers\Mahasiswa\SuratRekomendasiMBKMController;
use App\Http\Controllers\Mahasiswa\SuratKelulusanController;
use App\Http\Controllers\Mahasiswa\SuratLaporanHasilStudiController;
use App\Http\Controllers\Kaprodi\KaprodiDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route aplikasi web didefinisikan di sini.
|
*/

// Redirect root ke halaman login
Route::redirect('/', '/login');

// Route autentikasi bawaan Laravel
Auth::routes();

// Route registrasi khusus untuk super admin
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // -------------------------------
    // Dashboard dan Pengajuan Mahasiswa
    // -------------------------------
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // Dashboard Mahasiswa
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengajuan-surat', [MahasiswaDashboardController::class, 'pengajuan'])->name('pengajuan-surat');
        Route::get('/pengajuan/redirect', [MahasiswaDashboardController::class, 'redirectPengajuan'])->name('pengajuan.redirect');

        // Pengajuan Surat
        Route::get('/surat-keterangan-aktif', [SuratKeteranganAktifController::class, 'form'])->name('surat-keterangan-aktif');
        Route::post('/surat-keterangan-aktif', [SuratKeteranganAktifController::class, 'store']);

        Route::get('/surat-pengantar-tugas', [SuratPengantarTugasController::class, 'form'])->name('surat-pengantar-tugas');
        Route::post('/surat-pengantar-tugas', [SuratPengantarTugasController::class, 'store']);

        Route::get('/surat-rekomendasi-mbkm', [SuratRekomendasiMBKMController::class, 'form'])->name('surat-rekomendasi-mbkm');
        Route::post('/surat-rekomendasi-mbkm', [SuratRekomendasiMBKMController::class, 'store']);

        Route::get('/surat-kelulusan', [SuratKelulusanController::class, 'form'])->name('surat-kelulusan');
        Route::post('/surat-kelulusan', [SuratKelulusanController::class, 'store']);

        Route::get('/laporan-hasil-studi', [SuratLaporanHasilStudiController::class, 'form'])->name('laporan-hasil-studi');
        Route::post('/laporan-hasil-studi', [SuratLaporanHasilStudiController::class, 'store']);

        // Route Profil Mahasiswa
        Route::get('/profil', [MahasiswaProfileController::class, 'index'])->name('profile');
        Route::put('/profil', [MahasiswaProfileController::class, 'update'])->name('profile.update');

        Route::get('/mahasiswa/surat/{id}/download', [MahasiswaDashboardController::class, 'downloadSurat'])->name('mahasiswa.surat.download');

    });

    // -------------------------------
    // Dashboard Kaprodi
    // -------------------------------
    Route::prefix('kaprodi')->name('kaprodi.')->group(function () {
        // Tampilan dashboard kaprodi
        Route::get('/dashboard', [KaprodiDashboard::class, 'index'])->name('dashboard');

        // Aksi Approve pengajuan surat
        Route::put('/pengajuan/{id}/approve', [KaprodiDashboard::class, 'approve'])->name('approve');

        // Aksi Reject pengajuan surat
        Route::put('/pengajuan/{id}/reject', [KaprodiDashboard::class, 'reject'])->name('reject');
    });

    // -------------------------------
    // Dashboard Super Admin & CRUD Users
    // -------------------------------
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [SuperAdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [SuperAdminController::class, 'destroy'])->name('users.destroy');

        // Route untuk menambah Fakultas & Prodi secara bersamaan
        Route::get('/institution/create', [InstitutionController::class, 'create'])->name('institution.create');
        Route::post('/institution', [InstitutionController::class, 'store'])->name('institution.store');

        // Route untuk menambah Prodi saja
        Route::post('/institution/prodi', [InstitutionController::class, 'storeProdi'])->name('institution.storeProdi');
    });

    // -------------------------------
    // Dashboard Admin
    // -------------------------------
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/upload-surat/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'uploadSurat'])->name('upload.surat');
        Route::get('/detail-surat/{id}', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'detail'])->name('detail.surat'); // Jika diperlukan secara terpisah
    });


});
