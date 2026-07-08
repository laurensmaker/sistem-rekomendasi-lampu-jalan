<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\HasilSurveiController;
use App\Http\Controllers\RankingSawController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])
            ->name('admin')
            ->middleware('role:admin'); 
            
        Route::get('/petugas', [DashboardController::class, 'petugas'])
            ->name('petugas_survei')
            ->middleware('role:petugas_survei');
            
        Route::get('/staf', [DashboardController::class, 'staf'])
            ->name('staf_perencana')
            ->middleware('role:staf_perencana');
            
        Route::get('/kepala', [DashboardController::class, 'kepala'])
            ->name('kepala_bidang')
            ->middleware('role:kepala_bidang');
    });

    // User Routes - Admin Only
    Route::resource('users', UserController::class)
        ->middleware('role:admin');
    Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])
        ->name('users.bulk-delete')
        ->middleware('role:admin');

    // Kriteria Routes - Admin dan Staf Perencana
    Route::resource('kriteria', KriteriaController::class)
        ->middleware('role:admin,staf_perencana'); // Pisahkan dengan koma
    Route::delete('kriteria/bulk-delete', [KriteriaController::class, 'bulkDelete'])
        ->name('kriteria.bulk-delete')
        ->middleware('role:admin,staf_perencana');

    // Lokasi Routes - Semua Role (TANPA MIDDLEWARE ROLE)
    Route::resource('lokasi', LokasiController::class);
    Route::delete('lokasi/bulk-delete', [LokasiController::class, 'bulkDelete'])
        ->name('lokasi.bulk-delete');

    // Dokumentasi Routes - Admin dan Petugas Survei
    Route::resource('dokumentasi', DokumentasiController::class)
        ->middleware('role:admin,petugas_survei');
    Route::delete('dokumentasi/bulk-delete', [DokumentasiController::class, 'bulkDelete'])
        ->name('dokumentasi.bulk-delete')
        ->middleware('role:admin,petugas_survei');

    // Hasil Survei Routes
    Route::resource('hasil-survei', HasilSurveiController::class)
        ->middleware('role:admin,petugas_survei,staf_perencana');
    Route::post('hasil-survei/{hasilSurvei}/hitung-saw', 
        [HasilSurveiController::class, 'hitungSAW'])
        ->name('hasil-survei.hitung-saw')
        ->middleware('role:admin,staf_perencana');
    Route::post('hasil-survei/{hasilSurvei}/generate-rekomendasi', 
        [HasilSurveiController::class, 'generateRekomendasi'])
        ->name('hasil-survei.generate-rekomendasi')
        ->middleware('role:admin,staf_perencana');

    // Ranking SAW Routes
    Route::prefix('ranking-saw')->name('ranking-saw.')->middleware('role:admin,staf_perencana')->group(function () {
        Route::get('/', [RankingSawController::class, 'index'])->name('index');
        Route::get('/{id}', [RankingSawController::class, 'show'])->name('show');
        Route::get('/export/excel', [RankingSawController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export/pdf', [RankingSawController::class, 'exportPdf'])->name('export-pdf');
        Route::post('/recalculate', [RankingSawController::class, 'recalculateAll'])->name('recalculate');
        Route::get('/compare', [RankingSawController::class, 'compare'])->name('compare');
        Route::get('/detail/{id}', [RankingSawController::class, 'getDetail'])->name('detail');
    });

    // Rekomendasi Routes
   Route::resource('rekomendasi', RekomendasiController::class)
    ->middleware('role:admin,staf_perencana,kepala_bidang');
    
    Route::post('rekomendasi/{rekomendasi}/ajukan', 
        [RekomendasiController::class, 'ajukan'])
        ->name('rekomendasi.ajukan')
        ->middleware('role:admin,staf_perencana');
        
    Route::post('rekomendasi/{rekomendasi}/validasi', 
        [RekomendasiController::class, 'validasi'])
        ->name('rekomendasi.validasi')
        ->middleware('role:admin,kepala_bidang');
        
    Route::get('rekomendasi/{rekomendasi}/cetak', 
        [RekomendasiController::class, 'cetak'])
        ->name('rekomendasi.cetak')
        ->middleware('role:admin,staf_perencana,kepala_bidang');
        
    Route::get('rekomendasi/export/excel', 
        [RekomendasiController::class, 'exportExcel'])
        ->name('rekomendasi.export-excel')
        ->middleware('role:admin,staf_perencana,kepala_bidang');

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])
            ->name('index')
            ->middleware('role:admin,staf_perencana,kepala_bidang');
        Route::get('/rekomendasi', [LaporanController::class, 'rekomendasi'])
            ->name('rekomendasi')
            ->middleware('role:admin,staf_perencana,kepala_bidang');
        Route::get('/survei', [LaporanController::class, 'survei'])
            ->name('survei')
            ->middleware('role:admin,staf_perencana,kepala_bidang');
    });
});

// Default Redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.' . auth()->user()->role);
    }
    return redirect()->route('login');
});