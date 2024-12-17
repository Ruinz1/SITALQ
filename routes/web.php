<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PesertaPrintController;

// Tambahkan middleware sanctum untuk semua route
Route::middleware(['web'])->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('front.index');
    Route::get('/sarana', [FrontController::class, 'sejarah'])->name('front.sejarah');
    Route::get('/akademik', [FrontController::class, 'akademik'])->name('front.akademik');
    Route::get('/jadwal/download', [JadwalController::class, 'download'])->name('jadwal.download');

Route::get('/daftar', [FrontController::class, 'daftar'])->name('front.daftar');
Route::post('/daftar', [PesertaController::class, 'store'])->name('peserta.store');

    Route::get('download-peserta-kelas/{kelas_id}', [PdfController::class, 'downloadPesertaKelas'])
        ->name('download.peserta-kelas');
        Route::get('peserta/{peserta}/print', [PesertaPrintController::class, 'print'])
        ->name('peserta.print');

// Testing Route
// Route::get('/test', function() {
//     return response()->json(['message' => 'Test route works!']);
// });

// Route::get('/test-404', function () {
//     return view('errors.404');
// });

// Route::get('/test-403', function () {
//     abort(403);
// });

// Route::get('/test-500', function () {
//     abort(500);
// });

// Route::get('/test-501', function () {
//         abort(501);
//     });
});

// Route API tetap di luar middleware group
Route::get('/api/check-kode/{kode}', [PesertaController::class, 'checkKode']);
// Route API tetap di luar middleware group
