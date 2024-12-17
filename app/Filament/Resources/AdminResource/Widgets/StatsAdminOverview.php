<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Jadwal;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    
    {
        return [
            
            Stat::make('Tahun Ajaran', TahunAjaran::where('status', '1')->first()->nama)
                ->description('Aktif')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('success'),

            Stat::make('Total Kelas', Kelas::count())
                ->description('Total kelas')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success'),
            
            Stat::make('Total Guru', Guru::count())
                ->description('Total guru')
                ->descriptionIcon('heroicon-o-user')
                ->color('success'),
                
            Stat::make('Total Jadwal', Jadwal::count())
                ->description('Total jadwal')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
                
            Stat::make('Total Mata Pelajaran', Mapel::count())
                ->description('Total mata pelajaran')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('success'),
                
            Stat::make('Total Peserta Pendaftaran', Peserta::count())
                ->description('Total peserta pendaftaran')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),
                
            Stat::make('Pendaftaran', function () {
                $pendaftaran = Pendaftaran::where('status', '1')->first();
                
                if ($pendaftaran) {
                    return $pendaftaran->tahunAjaran->nama;
                }
                return 'Pendaftaran sedang ditutup';
            })
            ->description(Pendaftaran::where('status', '1')->exists() ? 'Pendaftaran sedang dibuka' : 'Tidak ada pendaftaran yang aktif')
            ->descriptionIcon(Pendaftaran::where('status', '1')->exists() ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
            ->color(Pendaftaran::where('status', '1')->exists() ? 'success' : 'danger'),
                
        ];
    }
}
