<?php

namespace App\Observers;

use App\Models\Peserta;
use App\Models\KodePendaftaran;
use App\Models\transaksi;
use App\Models\Kas;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PesertaObserver
{
    // public function creating(Peserta $peserta): void
    // {
    //     // Validasi saat create baru
    //     $this->validateKodePendaftaran($peserta);
    // }

    public function updating(Peserta $peserta): void
    {
        // Jika kode_pendaftaran_id berubah, validasi kode baru
        // if ($peserta->isDirty('kode_pendaftaran_id')) {
        //     $this->validateKodePendaftaran($peserta, true);
        // }

        // Jika status diubah menjadi pending
        if ($peserta->isDirty('status_peserta') && $peserta->status_peserta === 'pending') {
            $peserta->tanggal_diterima = null;
        }
    }

    /**
     * Handle the Peserta "deleting" event.
     * Menghapus semua transaksi yang terkait dengan peserta yang akan dihapus
     */
    public function deleting(Peserta $peserta): void
    {
        // Ambil semua transaksi terkait peserta ini
        $transaksiIds = Transaksi::where('peserta_id', $peserta->id)->pluck('id');

        // Hapus entri kas yang terkait dengan transaksi-transaksi tersebut (soft delete)
        if ($transaksiIds->isNotEmpty()) {
            $deletedKas = Kas::whereIn('transaksi_id', $transaksiIds)->delete();
            Log::info('Menghapus kas terkait transaksi peserta', [
                'peserta_id' => $peserta->id,
                'deleted_kas' => $deletedKas
            ]);
        }

        // Hapus semua transaksi yang memiliki peserta_id yang sama (soft delete)
        Transaksi::where('peserta_id', $peserta->id)->delete();
        
        Log::info('Menghapus transaksi terkait untuk peserta', [
            'peserta_id' => $peserta->id,
            'peserta_nama' => $peserta->nama
        ]);
    }

    protected function validateKodePendaftaran(Peserta $peserta): void
    {
        Log::info('Validating kode pendaftaran:', ['kode_pendaftaran_id' => $peserta->kode_pendaftaran_id]);

        $kodePendaftaran = KodePendaftaran::with('pendaftaran')->find($peserta->kode_pendaftaran_id);

        if (!$kodePendaftaran) {
            Log::error('Kode pendaftaran tidak ditemukan:', ['kode_pendaftaran_id' => $peserta->kode_pendaftaran_id]);
            throw ValidationException::withMessages([
                'kode_pendaftaran' => 'Kode pendaftaran tidak valid',
            ]);
        }

        // Ambil pendaftaran yang terkait
        $pendaftaran = $kodePendaftaran->pendaftaran;

        // Tambahkan logging untuk memeriksa kode pendaftaran dan status
        Log::info('Memeriksa kode pendaftaran:', ['kode_pendaftaran_id' => $peserta->kode_pendaftaran_id]);

        // Periksa status pendaftaran
        if ($pendaftaran && $pendaftaran->status !== '1') {
            Log::error('Status pendaftaran tidak aktif:', ['status' => $pendaftaran->status]);
            throw ValidationException::withMessages([
                'kode_pendaftaran' => 'Kode pendaftaran tidak valid karena status pendaftaran tidak aktif',
            ]);
        } else {
            Log::info('Status pendaftaran aktif:', ['status' => $pendaftaran->status]);
        }
    }
} 