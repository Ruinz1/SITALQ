<?php

namespace App\Filament\Exports;

use App\Models\Jadwal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class JadwalExporter extends Exporter
{
    protected static ?string $model = Jadwal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('guru.nama')
                ->label('Guru'),
            ExportColumn::make('guru.mapel.nama')
                ->label('Mata Pelajaran'),
            ExportColumn::make('hari')
                ->label('Hari'),
            ExportColumn::make('jam')
                ->label('Jam'),
            ExportColumn::make('tahunAjaran.nama'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your jadwal export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
