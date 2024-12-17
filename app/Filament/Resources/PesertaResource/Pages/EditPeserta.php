<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPeserta extends EditRecord
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $peserta = $this->record;
        
        // Data keluarga
        if ($peserta->keluarga) {
            if ($peserta->keluarga->ayah) {
                $data['ayah'] = $peserta->keluarga->ayah->toArray();
            }
            if ($peserta->keluarga->ibu) {
                $data['ibu'] = $peserta->keluarga->ibu->toArray();
            }
            if ($peserta->keluarga->wali) {
                $data['wali'] = $peserta->keluarga->wali->toArray();
                $data['is_wali'] = '1';
            }
        }

        // Data informasi
        if ($peserta->informasi) {
            $data['informasi'] = $peserta->informasi->toArray();
        }

        // Data keterangan
        if ($peserta->keterangan) {
            $data['keterangan'] = $peserta->keterangan->toArray();
        }

        // Data pendanaan
        if ($peserta->pendanaan) {
            $data['pendanaan'] = $peserta->pendanaan->toArray();
        }

        // Data survei
        if ($peserta->survei) {
            $data['survei'] = $peserta->survei->toArray();
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $peserta = $record;

        // Update data peserta
        $peserta->update($data);

        // Update atau create data keluarga
        if (isset($data['ayah']) || isset($data['ibu']) || isset($data['wali'])) {
            $keluarga = $peserta->keluarga ?? $peserta->keluarga()->create();

            // Update data ayah
            if (isset($data['ayah'])) {
                if ($keluarga->ayah) {
                    $keluarga->ayah->update($data['ayah']);
                } else {
                    $keluarga->ayah()->create($data['ayah']);
                }
            }

            // Update data ibu
            if (isset($data['ibu'])) {
                if ($keluarga->ibu) {
                    $keluarga->ibu->update($data['ibu']);
                } else {
                    $keluarga->ibu()->create($data['ibu']);
                }
            }

            // Update data wali jika ada
            if (isset($data['wali']) && isset($data['is_wali'])) {
                if ($keluarga->wali) {
                    $keluarga->wali->update($data['wali']);
                } else {
                    $keluarga->wali()->create($data['wali']);
                }
            }
        }

        // Update atau create data informasi
        if (isset($data['informasi'])) {
            if ($peserta->informasi) {
                $peserta->informasi->update($data['informasi']);
            } else {
                $peserta->informasi()->create($data['informasi']);
            }
        }

        // Update atau create data keterangan
        if (isset($data['keterangan'])) {
            if ($peserta->keterangan) {
                $peserta->keterangan->update($data['keterangan']);
            } else {
                $peserta->keterangan()->create($data['keterangan']);
            }
        }

        // Update atau create data pendanaan
        if (isset($data['pendanaan'])) {
            if ($peserta->pendanaan) {
                $peserta->pendanaan->update($data['pendanaan']);
            } else {
                $peserta->pendanaan()->create($data['pendanaan']);
            }
        }

        // Update atau create data survei
        if (isset($data['survei'])) {
            if ($peserta->survei) {
                $peserta->survei->update($data['survei']);
            } else {
                $peserta->survei()->create($data['survei']);
            }
        }

        return $peserta;
    }
}
