<?php

namespace App\Filament\Resources\PendaftaranResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class KodePendaftaranRelationManager extends RelationManager
{
    protected static string $relationship = 'kodePendaftaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->readOnly()
                    ->default(function () {
                        // Cek kode yang sudah dihapus (dari yang terlama)
                        $deletedCode = \App\Models\KodePendaftaran::onlyTrashed()
                            ->orderBy('deleted_at', 'asc')
                            ->first();

                        if ($deletedCode) {
                            // Gunakan kode yang sudah dihapus
                            return $deletedCode->kode;
                        } else {
                            // Jika tidak ada kode yang dihapus, buat kode baru
                            $lastCode = \App\Models\KodePendaftaran::withTrashed()
                                ->orderByRaw('CAST(SUBSTRING(kode, 7) AS UNSIGNED) DESC')
                                ->first();
                            
                            $lastNumber = 0;
                            if ($lastCode) {
                                $lastNumber = (int) substr($lastCode->kode, 6);
                            }
                            
                            return str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes())
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable()
                    ->label('No Urut'),
                Tables\Columns\TextColumn::make('peserta_count')
                    ->counts('peserta')
                    ->label('Jumlah Peserta'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->visible(fn (RelationManager $livewire, $record): bool => 
                        $livewire->getOwnerRecord()->status == '1'
                    )
                    ->using(function (array $data, RelationManager $livewire) {
                        // Ambil semua kode yang ada (termasuk yang dihapus)
                        $existingCodes = \App\Models\KodePendaftaran::withTrashed()
                            ->pluck('kode')
                            ->map(function ($kode) {
                                return (int) substr($kode, 6); // Ambil angka dari kode
                            })
                            ->toArray();

                        // Cari nomor terendah yang belum digunakan
                        $number = 1;
                        while (in_array($number, $existingCodes)) {
                            $number++;
                        }

                        // Buat kode baru dengan nomor yang ditemukan
                        $newCode = str_pad($number, 3, '0', STR_PAD_LEFT);
                        
                        return $livewire->getOwnerRecord()->kodePendaftaran()->create([
                            'kode' => $newCode,
                        ]);
                    }),

                Tables\Actions\Action::make('generateMultipleKode')
                    ->label('Generate Kode')
                    ->visible(fn (RelationManager $livewire, $record): bool => 
                        $livewire->getOwnerRecord()->status == '1'
                    )
                    ->form([
                        TextInput::make('jumlah')
                            ->label('Jumlah Kode')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(50)
                            ->default(1),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $jumlah = $data['jumlah'];
                        $createdCodes = 0;
                        
                        while ($createdCodes < $jumlah) {
                            // Cek kode yang sudah dihapus (dari yang terlama)
                            $deletedCode = \App\Models\KodePendaftaran::onlyTrashed()
                                ->orderBy('deleted_at', 'asc')
                                ->first();

                            if ($deletedCode) {
                                // Cek apakah kode ini sudah digunakan kembali
                                $existingCode = \App\Models\KodePendaftaran::where('kode', $deletedCode->kode)
                                    ->exists();

                                if (!$existingCode) {
                                    // Gunakan kode yang sudah dihapus jika belum digunakan kembali
                                    $livewire->getOwnerRecord()->kodePendaftaran()->create([
                                        'kode' => $deletedCode->kode,
                                    ]);
                                    
                                    // Hapus permanen kode yang sudah digunakan
                                    $deletedCode->forceDelete();
                                    
                                    $createdCodes++;
                                    continue;
                                }
                            }
                            
                            // Jika kode yang dihapus sudah digunakan atau tidak ada, buat kode baru
                            $lastCode = \App\Models\KodePendaftaran::withTrashed()
                                ->orderBy('kode', 'desc')
                                ->first();
                            
                            $lastNumber = 0;
                            if ($lastCode) {
                                $lastNumber = (int) $lastCode->kode;
                            }
                            
                            $newCode = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                            
                            $livewire->getOwnerRecord()->kodePendaftaran()->create([
                                'kode' => $newCode,
                            ]);
                            $createdCodes++;
                        }

                        Notification::make()
                            ->title('Berhasil membuat ' . $jumlah . ' kode pendaftaran')
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (RelationManager $livewire, $record): bool => 
                        $livewire->getOwnerRecord()->status == '1'
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (RelationManager $livewire, $record): bool => 
                        $livewire->getOwnerRecord()->status == '1' && 
                        !$record->peserta()->exists()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (RelationManager $livewire): bool => 
                            $livewire->getOwnerRecord()->status == '1'
                        ),
                ]),
            ]);
    }
}
