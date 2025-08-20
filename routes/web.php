<?php

use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PaguPrintController;
use App\Http\Controllers\NilaiPrintController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesertaPrintController;



// Route Midtrans Callback (webhook) - hanya satu endpoint yang dipakai
Route::post('/payment/notification', [PaymentController::class, 'notificationHandler']);
// Route Web (Frontend)
Route::middleware(['web'])->group(function () {
    Route::get('/', [FrontController::class, 'index'])->name('front.index');
    Route::get('/sarana', [FrontController::class, 'sejarah'])->name('front.sejarah');
    Route::get('/akademik', [FrontController::class, 'akademik'])->name('front.akademik');
    Route::get('/jadwal/download', [JadwalController::class, 'download'])->name('jadwal.download');
    
    Route::get('/pembayaran', [FrontController::class, 'pembayaran'])->name('front.pembayaran');
    Route::get('/daftar', [FrontController::class, 'daftar'])->name('front.daftar');
    Route::post('/daftar', [PesertaController::class, 'store'])->name('peserta.store');

    Route::get('download-peserta-kelas/{kelas_id}', [PdfController::class, 'downloadPesertaKelas'])
        ->name('download.peserta-kelas');
    Route::get('peserta/{peserta}/print', [PesertaPrintController::class, 'print'])
        ->name('peserta.print');
    Route::get('pengajuan/{pengajuan}/print', [PaguPrintController::class, 'print'])
        ->name('pengajuan.print');
    Route::get('nilai/{peserta}/print', [NilaiPrintController::class, 'print'])
        ->name('nilai.print');

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

    Route::get('payment/finish', function() {
        $orderId = request('order_id');
        $transactionStatus = request('transaction_status');
        
        if ($orderId && ($transactionStatus === 'capture' || $transactionStatus === 'settlement')) {
            $transaksi = \App\Models\Transaksi::where('kode_transaksi', $orderId)->first();
            if ($transaksi) {
                $transaksi->update(['status_pembayaran' => 1]);
                return redirect()->route('front.index')
                            ->with('success', 'Pembayaran berhasil!');
            }
        }
        
        return redirect()->route('front.index')
                        ->with('error', 'Terjadi kesalahan dalam proses pembayaran');
    })->name('payment.finish');

    // Payment finish (redirect user setelah pembayaran)
   

   });

// Route API (untuk AJAX/JS)
Route::prefix('api')->group(function () {
    Route::get('/check-kode/{kode}', [PesertaController::class, 'checkKode']);
    Route::get('/check-pembayaran/{kode}', [PembayaranController::class, 'checkKode']);
    
    // Jadwal Schedule API
    Route::get('/jadwal/schedule', [App\Http\Controllers\JadwalScheduleController::class, 'getSchedule']);
    Route::get('/jadwal/conflicts', [App\Http\Controllers\JadwalScheduleController::class, 'getConflicts']);
});


// Aksi Jadwal (web, proteksi auth + role)
Route::middleware(['web', 'auth', 'role:Admin|Super_Admin'])->group(function () {
    Route::delete('/jadwal/delete/{jadwal}', [App\Http\Controllers\JadwalScheduleController::class, 'destroy'])
        ->name('jadwal.destroy');
});



