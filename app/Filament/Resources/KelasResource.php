<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KelasResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KelasResource\RelationManagers;

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
            RelationManagers\KelasHasPesertaRelationManager::class,
            RelationManagers\KelasHasPenilaianRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
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

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        // Jika bukan guru, tidak perlu badge
        if (!$user->hasRole('Guru')) {
            return null;
        }
        $guru = \App\Models\Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            return null;
        }
        // Hitung jumlah penilaian pending untuk mapel guru ini
        $pendingCount = \App\Models\Penilaian::where('status', 'pending')
            ->where('mapel_id', $guru->mapel_id)
            ->count();
        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
