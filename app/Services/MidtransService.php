<?php

namespace App\Services;

use App\Models\Transaksi;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Transaksi $transaksi)
    {
        
        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->kode_transaksi,
                'gross_amount' => $transaksi->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $transaksi->peserta->nama,
                'email' => $transaksi->peserta->email,
                'phone' => $transaksi->peserta->no_hp,
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
            ]
        ];

        try {
            $response = \Midtrans\Snap::createTransaction($params);

            // Hanya update kode_transaksi & snap_token, status_pembayaran tetap 0 (pending)
            $transaksi->update([
                'kode_transaksi' => $transaksi->kode_transaksi,
                'snap_token' => $response->token ?? null,
                'midtrans_transaction_id' => $transaksi->kode_transaksi,
                
            ]);

            return [
                'redirect_url' => $response->redirect_url,
                'snap_token' => $response->token ?? null,
                'order_id' => $transaksi->kode_transaksi,
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Gagal membuat transaksi di Midtrans: ' . $e->getMessage(),
            ];
        }
    }

    
    public function handleNotification($request)
    {
        $notif = new \Midtrans\Notification();

        // Ambil order_id dari notifikasi
        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $paymentType = $notif->payment_type;
        $acquiringBank = $notif->acquiring_bank ?? null;
        $paymentMethod = $notif->payment_method ?? null;

        // Gabungkan payment method dan acquiring bank jika ada
        $paymentTypeFull = $paymentMethod && $acquiringBank
            ? $paymentMethod . ' + ' . $acquiringBank
            : $paymentType;

        // Temukan transaksi berdasarkan order_id
        $transaksi = \App\Models\Transaksi::where('kode_transaksi', $orderId)->first();

        if ($transaksi) {
            // Jika pembayaran berhasil (settlement/capture)
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $transaksi->update([
                    'status_pembayaran' => 1,
                    'midtrans_payment_type' => $paymentTypeFull,
                ]);
            } else if (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'])) {
                // Jika pembayaran gagal/expire/cancel, update juga payment type
                $transaksi->update([
                    'status_pembayaran' => 0,
                    'midtrans_payment_type' => $paymentTypeFull,
                ]);
            }
        }

        return $notif;
    }

    public function checkTransactionStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            // Pastikan hasilnya berupa objek
            if (is_array($status)) {
                $status = json_decode(json_encode($status));
            }
            return $status;
        } catch (\Exception $e) {
            // Tangani error jika orderId tidak ditemukan atau error lain
            return null;
        }
    }
} 