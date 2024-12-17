<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kelas')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('guru_id')
                    ->label('Guru Wali')
                    ->relationship('guru', 'nama')
                    ->required(),
                Forms\Components\Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship(
                        'tahunAjaran', 
                        'nama',
                        modifyQueryUsing: fn ($query) => $query->where('status', '1')
                    )
                    ->required()
                    ->disabled(fn ($context) => $context === 'edit'),
                Forms\Components\TextInput::make('kapasitas')
                    ->required()
                    ->numeric()
                    ->default(30),
                Forms\Components\Select::make('status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'penuh' => 'Penuh'
                    ])
                    ->default('tersedia')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.nama')
                    ->label('Guru Wali')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->numeric()
                    ->state(function (Kelas $record): int {
                        $jumlahSiswa = $record->kelasHasPeserta()->count();
                        return $record->kapasitas - $jumlahSiswa;
                    })
                    ->prefix('Kursi Tersisa: ')
                    ->sortable()
                    ->color(function (Kelas $record): string {
                        $sisaKursi = $record->kapasitas - $record->kelasHasPeserta()->count();
                        $kapasitas = $record->kapasitas;
                        return match(true) {
                            $sisaKursi <= 0 => 'danger',
                            $sisaKursi <= $kapasitas - $sisaKursi => 'warning',
                            default => 'success'
                        };
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(function (Kelas $record): string {
                        $sisaKursi = $record->kapasitas - $record->kelasHasPeserta()->count();
                        $kapasitas = $record->kapasitas;
                        return match(true) {
                            $sisaKursi <= 0 => 'danger',
                            $sisaKursi <= $kapasitas - $sisaKursi => 'warning',
                            default => 'success'
                        };
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\KelasHasPesertaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
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
