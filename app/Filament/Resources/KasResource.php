<?php

namespace App\Filament\Resources;

use Pages\ViewKas;
use App\Models\Kas;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KasResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KasResource\Widgets\KasOverview;

class KasResource extends Resource
{
    protected static ?string $model = Kas::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Data Keuangan';

    protected static ?string $pluralModelLabel = 'Kas';

    protected static ?string $modelLabel = 'Kas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama')
                    ->required()
                    ->label('Tahun Ajaran'),
                Forms\Components\Select::make('tipe')
                    ->options([
                        'masuk' => 'Pemasukan',
                        'keluar' => 'Pengeluaran',
                    ])
                    ->required()
                    ->label('Tipe'),
                Forms\Components\Select::make('sumber')
                    ->options([
                        'Transaksi' => 'Transaksi',
                        'Sumbangan' => 'Sumbangan',
                        'Pagu Anggaran' => 'Pagu Anggaran',
                    ])
                    ->required()
                    ->label('Sumber'),
                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->prefix('Rp')
                    ->label('Jumlah'),
                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->label('Kategori'),
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255)
                    ->label('Keterangan'),
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal'),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahunAjaran.nama')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun Ajaran'),
                Tables\Columns\TextColumn::make('tipe')
                    ->sortable()
                    ->label('Tipe'),
                Tables\Columns\TextColumn::make('sumber')
                    ->sortable()
                    ->label('Sumber'),
                Tables\Columns\TextColumn::make('jumlah')
                    ->money('idr')
                    ->sortable()
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->label('Keterangan'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable()
                    ->label('Tanggal'),
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

    public static function getWidgets(): array
    {
        return [
            KasOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKas::route('/'),
            'create' => Pages\CreateKas::route('/create'),
           
        ];
    }
    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}

