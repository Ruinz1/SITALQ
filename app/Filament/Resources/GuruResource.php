<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Users Management'; 

    protected static ?string $pluralModelLabel = 'Guru';
    protected static ?string $modelLabel = 'Guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->label('Nama'),
                Forms\Components\TextInput::make('alamat')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->label('Alamat'),
                Forms\Components\TextInput::make('telepon')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->label('Telepon'),
                Forms\Components\Select::make('mapel')
                    ->label('Mapel')
                    ->relationship('mapel', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Forms\Components\Select::make('user_id')
                    ->label('Username')
                    ->relationship(
                        'user',
                        'username',
                        fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'guru'))
                    )
                    ->searchable()
                    ->preload()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->unique(
                        table: 'gurus', 
                        column: 'user_id',
                        ignoreRecord: true
                    )
                    ->visible(fn (string $operation): bool => $operation === 'create'),
                // Tambahkan field lain sesuai kebutuhan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('user.roles', function ($query) {
                $query->where('name', 'guru');
            });
    }
}
