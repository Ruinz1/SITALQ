<?php

namespace App\Filament\Resources\KasResource\Pages;

use App\Filament\Resources\KasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Kas;
use App\Models\Transaksi;
use App\Models\Peserta;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Pagu_anggaran;
use App\Filament\Resources\KasResource\Widgets\KasOverview;

class ListKas extends ListRecords
{
    protected static string $resource = KasResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            KasOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('sinkronisasi-transaksi')
                ->label('Sinkronisasi Data Transaksi')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action(function () {
                    // Ambil semua transaksi sukses yang belum ada di kas
                    $transaksis = Transaksi::where('status_pembayaran', 1)
                        ->whereNotIn('id', Kas::whereNotNull('transaksi_id')->pluck('transaksi_id')->toArray())
                        ->with(['peserta.kodePendaftaran.pendaftaran'])
                        ->get();

                    $jumlahSinkron = 0;
                    foreach ($transaksis as $trx) {
                        $tahunAjaranId = $trx->peserta?->kodePendaftaran?->pendaftaran?->tahun_ajaran_id ?? null;
                        if (!$tahunAjaranId) continue;
                        Kas::create([
                            'transaksi_id' => $trx->id,
                            'pagu_anggaran_id' => null,
                            'tahun_ajaran_id' => $tahunAjaranId,
                            'tipe' => 'Masuk',
                            'sumber' => 'Transaksi Pendaftaran',
                            'jumlah' => $trx->total_bayar,
                            'keterangan' => 'Pembayaran dari ' . ($trx->peserta->nama ?? '-'),
                            'kategori' => 'Pendaftaran',
                            'tanggal' => $trx->updated_at,
                            'user_id' => Auth::id(),
                        ]);
                        $jumlahSinkron++;
                    }
                    if ($jumlahSinkron > 0) {
                        Notification::make()
                            ->title('Sinkronisasi Berhasil')
                            ->body("Berhasil sinkronisasi $jumlahSinkron transaksi ke kas.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Tidak Ada Data Baru')
                            ->body('Tidak ada transaksi baru yang perlu disinkronkan.')
                            ->warning()
                            ->send();
                    }
                }),
            Actions\Action::make('sinkronisasi-pagu-anggaran')
                ->label('Sinkronisasi Data Pagu Anggaran')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    $paguList = Pagu_anggaran::whereNotIn('id', Kas::whereNotNull('pagu_anggaran_id')->pluck('pagu_anggaran_id')->toArray())
                        ->where('status', 'approved')
                        ->get();
                    $jumlahSinkron = 0;
                    foreach ($paguList as $pagu) {
                        Kas::create([
                            'pagu_anggaran_id' => $pagu->id,
                            'tahun_ajaran_id' => $pagu->tahun_ajaran_id,
                            'transaksi_id' => null,
                            'tipe' => 'keluar',
                            'sumber' => 'pagu_anggaran',
                            'jumlah' => $pagu->total_harga,
                            'kategori' => $pagu->kategori,
                            'keterangan' => $pagu->keterangan,
                            'tanggal' => $pagu->tanggal_disetujui,
                            'user_id' => Auth::id(),
                        ]);
                        $jumlahSinkron++;
                    }
                    if ($jumlahSinkron > 0) {
                        Notification::make()
                            ->title('Sinkronisasi Pagu Anggaran Berhasil')
                            ->body("Berhasil sinkronisasi $jumlahSinkron data pagu anggaran ke kas.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Tidak Ada Data Pagu Anggaran Baru')
                            ->body('Tidak ada pagu anggaran baru yang perlu disinkronkan.')
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }
}
