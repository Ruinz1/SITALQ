<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Jadwal;
use App\Models\Peserta;
use App\Models\KodePendaftaran;
use App\Models\Pendaftaran;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Tampilkan semua statistik untuk menyederhanakan hak akses dan menghindari dependency role
        $isGuru = false;
        
        $stats = [
            Stat::make('Tahun Ajaran', TahunAjaran::where('status', '1')->first()?->nama ?? '-')
                ->description('Aktif')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('success'),

            Stat::make('Total Kelas', Kelas::count())
                ->description('Total kelas')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success'),

            Stat::make('Total Jadwal', Jadwal::count())
                ->description('Total jadwal')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),

            Stat::make('Total Mata Pelajaran', Mapel::count())
                ->description('Total mata pelajaran')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('success'),
                
        ];

        // Tambahkan hanya jika bukan guru
        if (! $isGuru) {
            $stats[] = Stat::make('Total Guru', Guru::count())
                ->description('Total guru')
                ->descriptionIcon('heroicon-o-user')
                ->color('success');

            $stats[] = Stat::make('Total Peserta Pendaftaran', Peserta::count())
                ->description('Total peserta pendaftaran')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success');

            $stats[] = Stat::make('Total Siswa', Peserta::where('tanggal_diterima', '!=', null)->count())
                ->description('Jumlah Siswa')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success');

            $stats[] = Stat::make('Pendaftaran', function () {
                    $pendaftaran = Pendaftaran::where('status', '1')->first();
                    return $pendaftaran ? $pendaftaran->tahunAjaran->nama : 'Pendaftaran sedang ditutup';
                })
                ->description(Pendaftaran::where('status', '1')->exists() ? 'Pendaftaran sedang dibuka' : 'Tidak ada pendaftaran yang aktif')
                ->descriptionIcon(Pendaftaran::where('status', '1')->exists() ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                ->color(Pendaftaran::where('status', '1')->exists() ? 'success' : 'danger');

          

            
        }

        return $stats;
    }
}
