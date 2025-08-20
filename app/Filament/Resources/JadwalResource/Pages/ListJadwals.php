<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwals extends ListRecords
{
    protected static string $resource = JadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'tahunAjarans' => \App\Models\TahunAjaran::where('status', '1')->get(),
            'kelas' => \App\Models\Kelas::all(),
        ];
    }

    public function getView(): string
    {
        return 'filament.resources.jadwal-resource.pages.list-jadwals';
    }
}
