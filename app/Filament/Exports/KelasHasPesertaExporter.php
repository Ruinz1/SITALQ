<?php

namespace App\Filament\Exports;

use App\Models\KelasHasPeserta;
use Illuminate\Support\Facades\Log;
use Filament\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class KelasHasPesertaExporter extends Exporter
{
    protected static ?string $model = KelasHasPeserta::class;

    public static function getColumns(): array
    {
        try {
            $counter = 1;
            
            
            return [

                ExportColumn::make('kelas.nama_kelas')
                ->label('Nama Kelas')
                ->formatStateUsing(fn ($state, $record) => 
                    $record->kelas->nama_kelas ?? '-')
                ->default('-'),
            
                ExportColumn::make('kelas.tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->formatStateUsing(fn ($state, $record) => 
                    $record->kelas->tahunAjaran->nama ?? '-')
                ->default('-'),

                
                ExportColumn::make('nomor_urut')
                    ->label('No')
                    ->formatStateUsing(function () use (&$counter) {
                        return $counter++;
                    }),
                
                ExportColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->formatStateUsing(fn ($state, $record) => 
                        $record->peserta->nama ?? '-')
                    ->default('-'),
                
               
            ];
        } catch (\Exception $e) {
            Log::error('Error in KelasHasPesertaExporter::getColumns: ' . $e->getMessage());
            return [];
        }
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export daftar peserta kelas telah selesai dengan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diexport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diexport.';
        }

        return $body;
    }
}
