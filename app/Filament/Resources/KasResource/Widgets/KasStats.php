<?php

namespace App\Filament\Resources\KasResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pemasukan', $this->getPemasukan())
                ->description('Total pemasukan')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Pengeluaran', $this->getPengeluaran())
                ->description('Total pengeluaran')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),
        ];
    }
}
