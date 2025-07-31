<?php

namespace App\Observers;

use App\Models\Pagu_anggaran;
use App\Models\Kas;
use Illuminate\Support\Facades\Log;

class PaguAnggaranObserver
{
    /**
     * Handle the Pagu_anggaran "updated" event.
     */
    public function updated(Pagu_anggaran $paguAnggaran)
    {
        // Cek apakah status berubah menjadi approved
        if ($paguAnggaran->isDirty('status') && $paguAnggaran->status === 'approved') {
            try {
                // Cek saldo kas sebelum menyetujui
                $saldoKas = $this->getSaldoKas($paguAnggaran->tahun_ajaran_id);
                
                if ($saldoKas < $paguAnggaran->total_harga) {
                    // Jika saldo tidak cukup, tolak pagu anggaran
                    $paguAnggaran->status = 'rejected';
                    $paguAnggaran->alasan_penolakan = 'Saldo kas tidak mencukupi. Saldo tersedia: Rp ' . number_format($saldoKas, 0, ',', '.') . ', Kebutuhan: Rp ' . number_format($paguAnggaran->total_harga, 0, ',', '.');
                    $paguAnggaran->save();
                    
                    Log::warning('Pagu anggaran ditolak karena saldo kas tidak mencukupi', [
                        'pagu_anggaran_id' => $paguAnggaran->id,
                        'nama_item' => $paguAnggaran->nama_item,
                        'total_harga' => $paguAnggaran->total_harga,
                        'saldo_kas' => $saldoKas,
                        'kekurangan' => $paguAnggaran->total_harga - $saldoKas
                    ]);
                    
                    throw new \Exception('Pagu anggaran ditolak karena saldo kas tidak mencukupi. Saldo tersedia: Rp ' . number_format($saldoKas, 0, ',', '.') . ', Kebutuhan: Rp ' . number_format($paguAnggaran->total_harga, 0, ',', '.'));
                }
                
                // Buat entri baru ke tabel kas
                Kas::create([
                    'transaksi_id' => null,
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
                    'tipe' => 'keluar',
                    'sumber' => 'Anggaran/Pengadaan',
                    'jumlah' => $paguAnggaran->total_harga,
                    'kategori' => $paguAnggaran->kategori,
                    'keterangan' => $paguAnggaran->keterangan,
                    'tanggal' => now(),
                    'user_id' => $paguAnggaran->disetujui_oleh,
                ]);
                
                Log::info('Entri kas berhasil dibuat untuk pagu anggaran', [
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'nama_item' => $paguAnggaran->nama_item,
                    'jumlah' => $paguAnggaran->total_harga,
                    'keterangan' => $paguAnggaran->keterangan
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal membuat entri kas untuk pagu anggaran', [
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'error' => $e->getMessage()
                ]);
                
                // Jika terjadi error dan status belum diubah ke rejected, kembalikan status ke nilai sebelumnya
                if ($paguAnggaran->status !== 'rejected') {
                    $paguAnggaran->status = $paguAnggaran->getOriginal('status');
                    $paguAnggaran->save();
                }
                throw $e;
            }
        }
    }

    /**
     * Handle the Pagu_anggaran "created" event.
     */
    public function created(Pagu_anggaran $paguAnggaran)
    {
        // Jika pagu anggaran langsung dibuat dengan status approved
        if ($paguAnggaran->status === 'approved') {
            try {
                // Cek saldo kas sebelum menyetujui
                $saldoKas = $this->getSaldoKas($paguAnggaran->tahun_ajaran_id);
                
                if ($saldoKas < $paguAnggaran->total_harga) {
                    // Jika saldo tidak cukup, tolak pagu anggaran
                    $paguAnggaran->status = 'rejected';
                    $paguAnggaran->alasan_penolakan = 'Saldo kas tidak mencukupi. Saldo tersedia: Rp ' . number_format($saldoKas, 0, ',', '.') . ', Kebutuhan: Rp ' . number_format($paguAnggaran->total_harga, 0, ',', '.');
                    $paguAnggaran->save();
                    
                    Log::warning('Pagu anggaran baru ditolak karena saldo kas tidak mencukupi', [
                        'pagu_anggaran_id' => $paguAnggaran->id,
                        'nama_item' => $paguAnggaran->nama_item,
                        'total_harga' => $paguAnggaran->total_harga,
                        'saldo_kas' => $saldoKas,
                        'kekurangan' => $paguAnggaran->total_harga - $saldoKas
                    ]);
                    
                    throw new \Exception('Pagu anggaran ditolak karena saldo kas tidak mencukupi. Saldo tersedia: Rp ' . number_format($saldoKas, 0, ',', '.') . ', Kebutuhan: Rp ' . number_format($paguAnggaran->total_harga, 0, ',', '.'));
                }
                
                // Buat entri baru ke tabel kas
                Kas::create([
                    'transaksi_id' => null,
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
                    'tipe' => 'keluar',
                    'sumber' => 'Anggaran/Pengadaan',
                    'jumlah' => $paguAnggaran->total_harga,
                    'kategori' => $paguAnggaran->kategori,
                    'keterangan' => $paguAnggaran->keterangan,
                    'tanggal' => now(),
                    'user_id' => $paguAnggaran->disetujui_oleh,
                ]);
                
                Log::info('Entri kas berhasil dibuat untuk pagu anggaran baru', [
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'nama_item' => $paguAnggaran->nama_item,
                    'jumlah' => $paguAnggaran->total_harga,
                    'keterangan' => $paguAnggaran->keterangan
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal membuat entri kas untuk pagu anggaran baru', [
                    'pagu_anggaran_id' => $paguAnggaran->id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }
    }

    /**
     * Handle the Pagu_anggaran "deleted" event.
     */
    public function deleted(Pagu_anggaran $paguAnggaran)
    {
        try {
            // Hapus entri kas yang terkait dengan pagu anggaran ini
            $deletedCount = Kas::where('pagu_anggaran_id', $paguAnggaran->id)->delete();
            
            Log::info('Entri kas berhasil dihapus untuk pagu anggaran yang dihapus', [
                'pagu_anggaran_id' => $paguAnggaran->id,
                'nama_item' => $paguAnggaran->nama_item,
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus entri kas untuk pagu anggaran yang dihapus', [
                'pagu_anggaran_id' => $paguAnggaran->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Handle the Pagu_anggaran "forceDeleted" event.
     */
    public function forceDeleted(Pagu_anggaran $paguAnggaran)
    {
        try {
            // Hapus entri kas yang terkait dengan pagu anggaran ini (permanen)
            $deletedCount = Kas::where('pagu_anggaran_id', $paguAnggaran->id)->forceDelete();
            
            Log::info('Entri kas berhasil dihapus permanen untuk pagu anggaran yang dihapus permanen', [
                'pagu_anggaran_id' => $paguAnggaran->id,
                'nama_item' => $paguAnggaran->nama_item,
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus permanen entri kas untuk pagu anggaran yang dihapus permanen', [
                'pagu_anggaran_id' => $paguAnggaran->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Method untuk mendapatkan saldo kas berdasarkan tahun ajaran
     */
    private function getSaldoKas($tahunAjaranId)
    {
        $pemasukan = Kas::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('tipe', 'masuk')
            ->sum('jumlah');
            
        $pengeluaran = Kas::where('tahun_ajaran_id', $tahunAjaranId)
            ->where('tipe', 'keluar')
            ->sum('jumlah');
            
        return $pemasukan - $pengeluaran;
    }
} 