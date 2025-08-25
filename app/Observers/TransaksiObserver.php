<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Models\Kas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Log;

class TransaksiObserver
{
    public function created(Transaksi $transaksi)
    {
        // Jika transaksi dibuat dengan status sukses, buat/selaraskan entri kas
        if ((int) $transaksi->status_pembayaran === 1) {
            $this->syncKasFromTransaksi($transaksi);
        }
    }

    public function updated(Transaksi $transaksi)
    {
        Log::info('TransaksiObserver updated dipanggil', [
            'transaksi_id' => $transaksi->id,
            'status_pembayaran' => $transaksi->status_pembayaran,
            'is_dirty_status' => $transaksi->isDirty('status_pembayaran'),
            'was_changed' => $transaksi->wasChanged('status_pembayaran')
        ]);

        // Jika status pembayaran berubah
        if ($transaksi->wasChanged('status_pembayaran')) {
            if ((int) $transaksi->status_pembayaran === 1) {
                Log::info('Status pembayaran berubah menjadi sukses, sinkronisasi kas', [
                    'transaksi_id' => $transaksi->id,
                    'total_bayar' => $transaksi->total_bayar
                ]);
                $this->syncKasFromTransaksi($transaksi);
            } else {
                // Jika status dibatalkan/ditolak, hapus entri kas terkait
                $deleted = Kas::where('transaksi_id', $transaksi->id)->delete();
                Log::info('Status pembayaran non-sukses, menghapus kas terkait', [
                    'transaksi_id' => $transaksi->id,
                    'deleted' => $deleted
                ]);
            }
        } else {
            // Jika status tetap sukses namun nilai/relasi berubah, selaraskan
            if ((int) $transaksi->status_pembayaran === 1 && (
                $transaksi->wasChanged('total_bayar') ||
                $transaksi->wasChanged('peserta_id')
            )) {
                Log::info('Transaksi sukses diperbarui, sinkronisasi kas (nilai/relasi berubah)', [
                    'transaksi_id' => $transaksi->id
                ]);
                $this->syncKasFromTransaksi($transaksi);
            }
        }
    }

    public function deleted(Transaksi $transaksi)
    {
        // Soft delete entri kas terkait transaksi
        $deleted = Kas::where('transaksi_id', $transaksi->id)->delete();
        Log::info('Transaksi dihapus, menghapus kas terkait (soft delete)', [
            'transaksi_id' => $transaksi->id,
            'deleted' => $deleted
        ]);
    }

    public function forceDeleted(Transaksi $transaksi)
    {
        // Force delete entri kas terkait transaksi
        $deleted = Kas::where('transaksi_id', $transaksi->id)->forceDelete();
        Log::info('Transaksi dihapus permanen, menghapus kas terkait (force delete)', [
            'transaksi_id' => $transaksi->id,
            'deleted' => $deleted
        ]);
    }

    private function syncKasFromTransaksi(Transaksi $transaksi): void
    {
        try {
            // Pastikan peserta dan tahun ajaran tersedia
            if (!$transaksi->peserta) {
                Log::error('Peserta tidak ditemukan untuk transaksi', ['transaksi_id' => $transaksi->id]);
                return;
            }

            // Ambil tahun ajaran aktif saat ini
            $activeYear = TahunAjaran::where('status', '1')->first();
            if (!$activeYear) {
                Log::error('Tahun ajaran aktif tidak ditemukan');
                return;
            }

            // Cek kecocokan transaksi dengan tahun ajaran aktif
            $matchesActive = false;
            // Cek dari field transaksi (kemungkinan berupa nama tahun ajaran)
            if (!empty($transaksi->tahun_masuk) && $transaksi->tahun_masuk === $activeYear->nama) {
                $matchesActive = true;
            }
            // Cek dari peserta: bisa berupa id atau nama (karena inkonsistensi data)
            $pesertaTahun = $transaksi->peserta->tahun_ajaran_masuk ?? null;
            if ($pesertaTahun !== null) {
                if ((string) $pesertaTahun === (string) $activeYear->id || (string) $pesertaTahun === (string) $activeYear->nama) {
                    $matchesActive = true;
                }
            }
            // Cek dari relasi peserta->tahunAjaran jika tersedia
            if (!$matchesActive && $transaksi->peserta->relationLoaded('tahunAjaran') && $transaksi->peserta->tahunAjaran) {
                if ((int) $transaksi->peserta->tahunAjaran->id === (int) $activeYear->id) {
                    $matchesActive = true;
                }
            }

            if (!$matchesActive) {
                Log::info('Transaksi tidak cocok dengan tahun ajaran aktif, dilewati', [
                    'transaksi_id' => $transaksi->id,
                    'tahun_masuk_transaksi' => $transaksi->tahun_masuk,
                    'peserta_tahun' => $pesertaTahun,
                    'active_tahun_ajaran_id' => $activeYear->id,
                    'active_tahun_ajaran_nama' => $activeYear->nama,
                ]);
                return;
            }

            Kas::updateOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'pagu_anggaran_id' => null,
                    'tahun_ajaran_id' => $activeYear->id,
                    'tipe' => 'masuk',
                    'sumber' => 'Transaksi Pendaftaran',
                    'jumlah' => $transaksi->total_bayar,
                    'keterangan' => 'Pembayaran dari ' . $transaksi->peserta->nama,
                    'kategori' => 'Pendaftaran',
                    'tanggal' => now(),
                    'user_id' => null,
                ]
            );

            Log::info('Entri kas diselaraskan dari transaksi', [
                'transaksi_id' => $transaksi->id,
                'synced' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyelaraskan entri kas dari transaksi', [
                'transaksi_id' => $transaksi->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 