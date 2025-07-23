<?php

namespace App\Filament\Resources\KasResource\Widgets;

use App\Models\Kas;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class KasOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPemasukan = Kas::where('tipe', 'masuk')->sum('jumlah');
        $totalPengeluaran = Kas::where('tipe', 'keluar')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukan, 2, ',', '.'))
                ->description('Total uang masuk')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 2, ',', '.'))
                ->description('Total uang keluar')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
            Stat::make('Saldo', 'Rp ' . number_format($saldo, 2, ',', '.'))
                ->description('Sisa saldo')
                ->descriptionIcon('heroicon-o-scale')
                ->color('primary'),
        ];
    }
} 