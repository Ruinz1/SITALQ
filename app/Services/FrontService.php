<?php

namespace App\Services;

use App\Models\jadwal;
use App\Models\Kelas;
use App\Models\Pendaftaran;

class FrontService
{
    public function getPendaftaran()
    {
        $pendaftaran = Pendaftaran::where('status', '1')
            ->first();
        $isOpen = !is_null($pendaftaran);
        // Menambahkan status pendaftaran ke dalam return value
        return compact('pendaftaran', 'isOpen');
    }

    public function getJadwal()
    {
        $jadwal = jadwal::with(['guru.mapel', 'kelas'])->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return compact('jadwal', 'kelas');
    }

    

    // Tambahkan method-method service di sini
} 