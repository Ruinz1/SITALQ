<?php

namespace App\Filament\Resources\PaguAnggaranResource\Pages;

use App\Filament\Resources\PaguAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaguAnggaran extends EditRecord
{
    protected static string $resource = PaguAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
