<?php

namespace App\Filament\Resources\PaguAnggaranResource\Pages;

use App\Filament\Resources\PaguAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaguAnggarans extends ListRecords
{
    protected static string $resource = PaguAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
