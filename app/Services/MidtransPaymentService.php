<?php

namespace App\Services;

use App\Models\Transaksi;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransPaymentService
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
        $orderId = 'TRX-' . $transaksi->id . '-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $transaksi->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $transaksi->peserta->nama,
                'email' => $transaksi->peserta->email,
                'phone' => $transaksi->peserta->no_hp,
            ],
            'callbacks' => [
                'finish' => route('payment.success'),
            ]
        ];

        $response = Snap::createTransaction($params);
        $snapToken = $response->token ?? null;
        $redirectUrl = $response->redirect_url ?? null;

        $transaksi->update([
            'kode_transaksi' => $orderId,
            'snap_token' => $snapToken,
            'midtrans_transaction_id' => $orderId,
        ]);

        return [
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
            'order_id' => $orderId,
        ];
    }

    public function handleCallback($payload)
    {
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $snapToken = $payload['token'] ?? null;

        $transaksi = Transaksi::where('kode_transaksi', $orderId)->first();
        if (!$transaksi) return false;

        $updateData = [
            'midtrans_payment_type' => $paymentType,
            'snap_token' => $snapToken ?? $transaksi->snap_token,
        ];

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $updateData['status_pembayaran'] = 1;
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'failure'])) {
            $updateData['status_pembayaran'] = 2;
        } elseif ($transactionStatus === 'expire') {
            $updateData['status_pembayaran'] = 3;
        } elseif ($transactionStatus === 'pending') {
            $updateData['status_pembayaran'] = 0;
        }

        $transaksi->update($updateData);
        return true;
    }
} 