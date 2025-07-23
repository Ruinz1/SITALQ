<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Models\Kas;
use Illuminate\Support\Facades\Log;

class TransaksiObserver
{
    public function updated(Transaksi $transaksi)
    {
        Log::info('Observer jalan', ['transaksi_id' => $transaksi->id]);
        // Cek jika status pembayaran berubah menjadi sukses
        if ($transaksi->isDirty('status_pembayaran') && $transaksi->status_pembayaran == 1) {
            Kas::create([
                'transaksi_id' => $transaksi->id,
                'pagu_anggaran_id' => null,
                'tahun_ajaran_id' => $transaksi->peserta->tahun_ajaran_id,
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

   
} 