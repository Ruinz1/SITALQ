<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MidtransService;
use App\Models\Kas;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Midtrans Callback', $payload);

        // Verifikasi signature untuk sandbox
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);

        if ($hashed !== $payload['signature_key']) {
            Log::error('Invalid signature from Midtrans');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        $transaksi = Transaksi::where('kode_transaksi', $orderId)->first();

        if (!$transaksi) {
            Log::error('Transaksi tidak ditemukan', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Update status transaksi berdasarkan callback Midtrans
        if ($transactionStatus == 'capture') {
            $transaksi->status_pembayaran = ($fraudStatus == 'accept') ? 1 : 0;
        } else if ($transactionStatus == 'settlement') {
            $transaksi->status_pembayaran = 1;
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'failure'])) {
            $transaksi->status_pembayaran = 2;
        } else if ($transactionStatus == 'expire') {
            $transaksi->status_pembayaran = 3;
        } else if ($transactionStatus == 'pending') {
            $transaksi->status_pembayaran = 0;
        }

        // Update snap_token, midtrans_transaction_id, midtrans_payment_type
        $transaksi->snap_token = $payload['token'] ?? $transaksi->snap_token;
        $transaksi->midtrans_transaction_id = $orderId;
        $transaksi->midtrans_payment_type = $paymentType;

        $transaksi->save();

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            if (!Kas::where('transaksi_id', $transaksi->id)->exists()) {
                Kas::create([
                    'transaksi_id' => $transaksi->id,
                    'pagu_anggaran_id' => null,
                    'tahun_ajaran_id' => $transaksi->peserta->kode_pendaftaran->pendaftaran->tahun_ajaran_id,
                    'tipe' => 'masuk',
                    'sumber' => 'Transaksi Pendaftaran',
                    'jumlah' => $transaksi->total_bayar,
                    'keterangan' => 'Pembayaran dari ' . $transaksi->peserta->nama,
                    'kategori' => 'Pendaftaran',
                    'tanggal' => $transaksi->updated_at,
                    'user_id' => null,
                ]);
            }
        }

        Log::info('Status transaksi berhasil diperbarui', [
            'order_id' => $orderId,
            'status' => $transaksi->status_pembayaran
        ]);

        return response()->json(['message' => 'Callback berhasil diproses']);
    }

    public function cekStatus(Request $request)
    {
        $orderId = $request->order_id;
        $status = app(MidtransService::class)->checkTransactionStatus($orderId);

        if ($status) {
            return response()->json([
                'success' => true,
                'message' => 'Status transaksi: ' . $status->transaction_status
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan atau terjadi kesalahan.'
            ]);
        }
    }
} 