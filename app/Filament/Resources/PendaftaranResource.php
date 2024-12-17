<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Registration Management';

    protected static ?string $pluralModelLabel = 'Pendaftaran';
    protected static ?string $modelLabel = 'Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Select::make('tahun_ajaran_id')
                    ->relationship(
                        'tahunAjaran', 
                        'nama',
                        modifyQueryUsing: fn ($query) => $query->where('status', '1')
                    )
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn ($context) => $context === 'edit')
                    ->dehydrated(fn ($context) => $context === 'create')
                    ->helperText('Hanya menampilkan tahun ajaran yang aktif')
                    ->visible(fn ($context) => $context === 'create'),
                    Forms\Components\Select::make('status')
                    ->options([
                        '1' => 'Aktif',
                        '0' => 'Nonaktif',
                    ])
                    ->default('0')
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('tahunAjaran.nama')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('kode_pendaftaran_count')
                        ->counts('kodePendaftaran')
                        ->label('Jumlah Kode')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'AKTIF',
                        '0' => 'NONAKTIF',
                        default => 'UNDEFINED',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status Pendaftaran'),
                
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KodePendaftaranRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('kodePendaftaran');
    }
}
