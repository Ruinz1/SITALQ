<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PendaftaranPerTahunChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Total Pendaftaran per Tahun Ajaran';
    protected int | string | array $columnSpan = [
        'md' => 12,
        'lg' => 8,
    ];

    protected function getData(): array
    {
        // Grafik: jumlah peserta diterima (tanggal_diterima != null) per tahun ajaran
        $rows = DB::table('pesertas')
            ->whereNotNull('pesertas.tanggal_diterima')
            ->leftJoin('kode_pendaftarans', 'pesertas.kode_pendaftaran_id', '=', 'kode_pendaftarans.id')
            ->leftJoin('pendaftarans', 'kode_pendaftarans.pendaftaran_id', '=', 'pendaftarans.id')
            ->leftJoin('tahun_ajarans', 'pendaftarans.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->selectRaw('COALESCE(tahun_ajarans.nama, pesertas.tahun_ajaran_masuk) as label, COUNT(pesertas.id) as total')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $labels = $rows->pluck('label')->toArray();
        $totals = $rows->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendaftaran',
                    'data' => $totals,
                    'backgroundColor' => 'rgba(59,130,246,0.5)',
                    'borderColor' => 'rgba(59,130,246,1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}


