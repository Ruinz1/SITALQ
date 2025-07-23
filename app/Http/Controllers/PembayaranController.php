<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function checkKode($kode)
    {
        $transaksi = Transaksi::with('peserta')
            ->where('kode_transaksi', $kode)
            ->where('status_pembayaran', 0) // hanya transaksi yang belum dibayar
            ->first();

        if (!$transaksi) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode transaksi tidak ditemukan atau sudah dibayar'
            ]);
        }

        // Pastikan relasi peserta ada
        if (!$transaksi->peserta) {
            return response()->json([
                'valid' => false,
                'message' => 'Data peserta tidak ditemukan'
            ]);
        }

        // Generate Snap Token jika belum ada
        if (!$transaksi->snap_token) {
            $payload = [
                'transaction_details' => [
                    'order_id' => $transaksi->kode_transaksi,
                    'gross_amount' => $transaksi->total_bayar
                ],
                'customer_details' => [
                    'first_name' => $transaksi->peserta->nama,
                    'email' => $transaksi->peserta->email,
                    'phone' => $transaksi->peserta->no_hp
                ],
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                    'mandiri_clickpay', 'gopay', 'shopeepay', 
                    'qris', 'indomaret'
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($payload);
                $transaksi->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'valid' => true,
            'nama_lengkap' => $transaksi->peserta->nama,
            'total_pembayaran' => $transaksi->total_bayar,
            'metode_pembayaran' => $transaksi->midtrans_payment_type,
            'snap_token' => $transaksi->snap_token
        ]);
    }

    public function handlePaymentNotification(Request $request)
    {
        $notif = new \Midtrans\Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        
        $transaksi = Transaksi::where('kode_transaksi', $order_id)->first();
        
        if ($transaksi) {
            if ($transaction == 'settlement' || $transaction == 'capture') {
                $transaksi->update([
                    'status_pembayaran' => 1,
                    'midtrans_payment_type' => $type
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
