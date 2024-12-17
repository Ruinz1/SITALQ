<?php

namespace App\Observers;

use App\Models\TahunAjaran;

class TahunAjaranObserver
{
    /**
     * Handle the TahunAjaran "created" event.
     */
    public function created(TahunAjaran $tahunAjaran): void
    {
        //
    }

    /**
     * Handle the TahunAjaran "updated" event.
     */
    public function updated(TahunAjaran $tahunAjaran): void
    {
        //
    }

    /**
     * Handle the TahunAjaran "deleted" event.
     */
    public function deleted(TahunAjaran $tahunAjaran): void
    {
        //
    }

    /**
     * Handle the TahunAjaran "restored" event.
     */
    public function restored(TahunAjaran $tahunAjaran): void
    {
        //
    }

    /**
     * Handle the TahunAjaran "force deleted" event.
     */
    public function forceDeleted(TahunAjaran $tahunAjaran): void
    {
        //
    }

    public function creating(TahunAjaran $tahunAjaran)
    {
        // Jika tidak ada tahun ajaran aktif, set yang baru sebagai aktif
        if (!TahunAjaran::where('status', '1')->exists()) {
            $tahunAjaran->status = '1';
        }
    }

    public function updating(TahunAjaran $tahunAjaran)
    {
        // Jika status diubah menjadi nonaktif
        if ($tahunAjaran->isDirty('status') && $tahunAjaran->status === '0') {
            // Cari tahun ajaran terbaru selain yang sedang diupdate
            $latestTahunAjaran = TahunAjaran::where('id', '!=', $tahunAjaran->id)
                ->latest()
                ->first();
                
            if ($latestTahunAjaran) {
                $latestTahunAjaran->update(['status' => '1']);
            }
        }
        
        // Jika status diubah menjadi aktif
        if ($tahunAjaran->isDirty('status') && $tahunAjaran->status === '1') {
            TahunAjaran::where('id', '!=', $tahunAjaran->id)
                ->update(['status' => '0']);
        }
    }
}
