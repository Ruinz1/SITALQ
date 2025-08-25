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
        try {
            // Jika status berubah
            if ($paguAnggaran->isDirty('status')) {
                // Jika berubah menjadi approved
                if ($paguAnggaran->status === 'approved') {
                    $saldoKas = $this->getSaldoKas($paguAnggaran->tahun_ajaran_id);
                    if ($saldoKas < $paguAnggaran->total_harga) {
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
                        throw new \Exception('Pagu anggaran ditolak karena saldo kas tidak mencukupi.');
                    }
                    // Buat/selaraskan entri kas
                    Kas::updateOrCreate(
                        ['pagu_anggaran_id' => $paguAnggaran->id],
                        [
                            'transaksi_id' => null,
                            'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
                            'tipe' => 'keluar',
                            'sumber' => 'Anggaran/Pengadaan',
                            'jumlah' => $paguAnggaran->total_harga,
                            'kategori' => $paguAnggaran->kategori,
                            'keterangan' => $paguAnggaran->keterangan,
                            'tanggal' => now(),
                            'user_id' => $paguAnggaran->disetujui_oleh,
                        ]
                    );
                    Log::info('Entri kas diselaraskan untuk pagu anggaran (approved)', [
                        'pagu_anggaran_id' => $paguAnggaran->id
                    ]);
                } else {
                    // Jika status menjadi non-approved dari approved, hapus kas terkait
                    $wasApproved = $paguAnggaran->getOriginal('status') === 'approved';
                    if ($wasApproved) {
                        $deleted = Kas::where('pagu_anggaran_id', $paguAnggaran->id)->delete();
                        Log::info('Status pagu berubah dari approved ke non-approved, menghapus kas', [
                            'pagu_anggaran_id' => $paguAnggaran->id,
                            'deleted' => $deleted
                        ]);
                    }
                }
                return;
            }

            // Jika status tidak berubah dan tetap approved, namun ada perubahan data penting
            if ($paguAnggaran->status === 'approved') {
                $relevantChanged = $paguAnggaran->wasChanged('total_harga')
                    || $paguAnggaran->wasChanged('kategori')
                    || $paguAnggaran->wasChanged('keterangan')
                    || $paguAnggaran->wasChanged('tahun_ajaran_id')
                    || $paguAnggaran->wasChanged('disetujui_oleh');

                if ($relevantChanged) {
                    // Jika ada kenaikan total_harga, validasi delta terhadap saldo kas
                    $kas = Kas::where('pagu_anggaran_id', $paguAnggaran->id)->first();
                    $oldJumlah = $kas?->jumlah ?? 0;
                    $delta = (float) $paguAnggaran->total_harga - (float) $oldJumlah;
                    if ($delta > 0) {
                        $saldoKas = $this->getSaldoKas($paguAnggaran->tahun_ajaran_id);
                        if ($saldoKas < $delta) {
                            Log::warning('Kenaikan total_harga melebihi saldo kas', [
                                'pagu_anggaran_id' => $paguAnggaran->id,
                                'delta' => $delta,
                                'saldo_kas' => $saldoKas
                            ]);
                            // Batalkan perubahan nilai ke nilai sebelumnya
                            $paguAnggaran->total_harga = $oldJumlah;
                            $paguAnggaran->save();
                            throw new \Exception('Perubahan total anggaran dibatalkan karena saldo kas tidak mencukupi.');
                        }
                    }

                    Kas::updateOrCreate(
                        ['pagu_anggaran_id' => $paguAnggaran->id],
                        [
                            'transaksi_id' => null,
                            'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
                            'tipe' => 'keluar',
                            'sumber' => 'Anggaran/Pengadaan',
                            'jumlah' => $paguAnggaran->total_harga,
                            'kategori' => $paguAnggaran->kategori,
                            'keterangan' => $paguAnggaran->keterangan,
                            'tanggal' => now(),
                            'user_id' => $paguAnggaran->disetujui_oleh,
                        ]
                    );
                    Log::info('Entri kas diselaraskan untuk pagu anggaran (updated fields)', [
                        'pagu_anggaran_id' => $paguAnggaran->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Gagal menyelaraskan entri kas untuk pagu anggaran', [
                'pagu_anggaran_id' => $paguAnggaran->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
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