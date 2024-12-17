<?php

namespace App\Observers;

use App\Models\Kelas;

class KelasObserver
{
    public function saving(Kelas $kelas)
    {
        $jumlahPeserta = $kelas->kelasHasPeserta()->count();
        
        if ($kelas->kapasitas > $jumlahPeserta) {
            $kelas->status = 'tersedia';
        } else {
            $kelas->status = 'penuh';
        }
    }
}
