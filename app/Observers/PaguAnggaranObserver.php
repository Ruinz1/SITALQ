<?php

namespace App\Observers;

use App\Models\Pagu_anggaran;
use App\Models\Kas;

class PaguAnggaranObserver
{
    public function updated(Pagu_anggaran $paguAnggaran)
    {
        // Cek apakah status berubah menjadi approved
        if ($paguAnggaran->isDirty('status') && $paguAnggaran->status === 'approved') {
            try {
                // Buat record kas baru untuk pengeluaran
                Kas::create([
                    'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
                    'tipe' => 'keluar',
                    'sumber' => 'pagu_anggaran',
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'jumlah' => $paguAnggaran->total_harga,
                    'kategori' => $paguAnggaran->kategori,
                    'keterangan' => 'Pengeluaran untuk ' . $paguAnggaran->nama_item . ' - ' . $paguAnggaran->keterangan,
                    'user_id' => $paguAnggaran->disetujui_oleh,
                    'tanggal' => now()
                ]);
            } catch (\Exception $e) {
                // Jika terjadi error, kembalikan status ke nilai sebelumnya
                $paguAnggaran->status = $paguAnggaran->getOriginal('status');
                $paguAnggaran->save();
                throw $e;
            }
        }
    }

    public function created(Pagu_anggaran $paguAnggaran)
    {
        // Catat ke kas sebagai pengeluaran
        Kas::create([
            'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
            'tipe' => 'keluar',
            'sumber' => 'pagu_anggaran',
            'jumlah' => $paguAnggaran->jumlah,
            'keterangan' => 'Pengeluaran untuk ' . $paguAnggaran->keterangan,
            'tanggal' => $paguAnggaran->tanggal,
            'user_id' => null,
        ]);
    }
} 