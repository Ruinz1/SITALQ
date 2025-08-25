<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Peserta;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JenisKelaminChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Total Siswa Berdasarkan Jenis Kelamin';
    protected int | string | array $columnSpan = [
        'md' => 12,
        'lg' => 6,
    ];


    protected function getData(): array
    {
        $result = Peserta::whereNotNull('tanggal_diterima')
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        return [
            'datasets' => [
                [
                    'label' => 'Total Siswa',
                    'data' => $result->values()->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.5)', // Blue for Laki-laki
                        'rgba(255, 99, 132, 0.5)',  // Pink for Perempuan
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                ],
            ],
            'labels' => $result->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
