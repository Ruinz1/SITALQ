<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwal extends CreateRecord
{
    protected static string $resource = JadwalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values from URL parameters
        if (request()->has('hari')) {
            $data['hari'] = request()->get('hari');
        }
        
        if (request()->has('jam')) {
            $data['jam'] = request()->get('jam');
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
