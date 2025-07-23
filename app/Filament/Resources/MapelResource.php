<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mapel;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MapelResource extends Resource
{
    protected static ?string $model = Mapel::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Jadwal Management';

    protected static ?string $pluralModelLabel = 'Mata Pelajaran';
    protected static ?string $modelLabel = 'Mata Pelajaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->label('Nama'),
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->label('Kode')
                    ->default(function () {
                        // Ambil semua kode yang sudah ada
                        $existingCodes = \App\Models\Mapel::pluck('kode')->toArray();
                        
                        // Cek nomor 1-10 yang belum dipakai
                        for ($i = 1; $i <= 10; $i++) {
                            $newCode = 'TKALMP' . $i;
                            if (!in_array($newCode, $existingCodes)) {
                                return $newCode;
                            }
                        }
                        
                        // Jika semua nomor sudah terpakai
                        return '#TKAL' . rand(11, 99); // Alternatif nomor
                    })
                    ->disabled()
                    ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->hasRole(['Super_Admin', 'Admin'])),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->hasRole(['Super_Admin', 'Admin'])),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn () => Auth::user()->hasRole(['Super_Admin', 'Admin'])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\MapelResource\Pages\ListMapels::route('/'),
            'create' => \App\Filament\Resources\MapelResource\Pages\CreateMapel::route('/create'),
            'edit' => \App\Filament\Resources\MapelResource\Pages\EditMapel::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
