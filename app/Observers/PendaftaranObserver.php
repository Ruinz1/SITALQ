<?php

namespace App\Observers;

use App\Models\Pendaftaran;
use Illuminate\Validation\ValidationException;

class PendaftaranObserver
{
    public function creating(Pendaftaran $pendaftaran)
    {
        // Cek apakah ada pendaftaran yang aktif
        if (Pendaftaran::where('status', '1')->exists()) {
            throw ValidationException::withMessages([
                'status' => 'Tidak dapat membuat pendaftaran baru karena masih ada pendaftaran yang aktif',
            ]);
        }

        // Cek apakah tahun ajaran aktif
        if (!$pendaftaran->tahunAjaran()->where('status', '1')->exists()) {
            throw ValidationException::withMessages([
                'tahun_ajaran_id' => 'Hanya dapat membuat pendaftaran dengan tahun ajaran yang aktif',
            ]);
        }
    }

    public function updating(Pendaftaran $pendaftaran)
    {
        // Jika status diubah menjadi aktif
        if ($pendaftaran->isDirty('status') && $pendaftaran->status === '1') {
            // Cek apakah ada pendaftaran lain yang aktif
            $activePendaftaran = Pendaftaran::where('status', '1')
                ->where('id', '!=', $pendaftaran->id)
                ->exists();

            if ($activePendaftaran) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya dapat mengaktifkan satu pendaftaran',
                ]);
            }
        }
    }
}