<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaguAnggaranResource\Pages;
use App\Filament\Resources\PaguAnggaranResource\RelationManagers;
use App\Models\Pagu_anggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaguAnggaranResource extends Resource
{
    protected static ?string $model = Pagu_anggaran::class;

    protected static ?string $pluralModelLabel = 'Pengadaan';

    protected static ?string $modelLabel = 'Pengadaan';

    protected static ?string $navigationGroup = 'Data Keuangan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Forms\Components\Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama')
                    ->required()
                    ->disabled(fn () => Auth::user()->hasRole('guru')),
                Forms\Components\Select::make('kategori')
                    ->options([
                        '1' => 'Pengadaan',
                        '2' => 'Pembelian',
                        '3' => 'Pengadaan dan Pembelian',
                    ])
                    ->required()
                    ->disabled(fn () => Auth::user()->hasRole('guru')),
                Forms\Components\TextInput::make('nama_item')
                    ->required()
                    ->disabled(fn () => Auth::user()->hasRole('guru')),
                Forms\Components\TextInput::make('harga_satuan')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state && $get('jumlah')) {
                            $set('total_harga', $state * $get('jumlah'));
                        }
                    })
                    ->disabled(fn () => Auth::user()->hasRole('guru')),
                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->disabled(fn () => Auth::user()->hasRole('guru')),
                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state && $get('harga_satuan')) {
                            $set('total_harga', $state * $get('harga_satuan'));
                        }
                    })
                    ->disabled(fn () => Auth::user()->hasRole('guru')),
                Forms\Components\TextInput::make('total_harga')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\Hidden::make('status')
                    ->default('pending'),
                Forms\Components\Hidden::make('tanggal_pengajuan')
                    ->default(now()),
                // Fields below will only be visible to admin when editing
                Forms\Components\Textarea::make('alasan_penolakan')
                    ->visible(fn () => Auth::user()->hasRole('Admin'))
                    ->hidden(fn ($context) => $context === 'create')
                    ->default(null),
                Forms\Components\Textarea::make('keterangan')
                    ->visible(fn () => Auth::user()->hasRole('Admin'))
                    ->hidden(fn ($context) => $context === 'create')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahunAjaran.nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Pengadaan',
                        '2' => 'Pembelian',
                        '3' => 'Pengadaan dan Pembelian',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_item')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->money('idr')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tanggal_pengajuan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_disetujui')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disetujuiOleh.username')
                    ->searchable()
                    ->label('Verifikasi Oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('alasan_penolakan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Print PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Pagu_anggaran $record) => route('pengajuan.print', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->required(),
                    ])
                    ->action(function (Pagu_anggaran $record, array $data): void {
                        $record->update([
                            'status' => 'approved',
                            'tanggal_disetujui' => now()->toDateString(),
                            'disetujui_oleh' => Auth::user()->id,
                            'keterangan' => $data['keterangan'],
                            'alasan_penolakan' => null,
                        ]);
                    })
                    ->visible(fn (Pagu_anggaran $record): bool => 
                        (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super_Admin')) && 
                        ($record->status === 'pending' || $record->status === 'pending')
                    ),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan (Optional)'),
                    ])
                    ->action(function (Pagu_anggaran $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'tanggal_disetujui' => now()->toDateString(),
                            'disetujui_oleh' => Auth::user()->id,
                            'alasan_penolakan' => $data['alasan_penolakan'],
                            'keterangan' => $data['keterangan'],
                        ]);
                    })
                    ->visible(fn (Pagu_anggaran $record): bool => 
                    Auth::user()->hasRole('Admin') && 
                    ($record->status === 'pending' || $record->status === 'approved')
                    ),
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
            'index' => Pages\ListPaguAnggarans::route('/'),
            'create' => Pages\CreatePaguAnggaran::route('/create'),
            'edit' => Pages\EditPaguAnggaran::route('/{record}/edit'),
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
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}



