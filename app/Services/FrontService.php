<?php

namespace App\Services;

use App\Models\jadwal;
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
        $jadwal = jadwal::all();
        return compact('jadwal');
    }

    

    // Tambahkan method-method service di sini
} 