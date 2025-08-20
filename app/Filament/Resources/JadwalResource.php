<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Jadwal;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\Action;
use App\Filament\Exports\JadwalExporter;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Models\Export;
use App\Filament\Resources\JadwalResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JadwalResource\RelationManagers;
use Illuminate\Support\Facades\Auth;
use App\Policies\JadwalPolicy;

class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Jadwal Management';

    protected static ?string $pluralModelLabel = 'Jadwal';
    protected static ?string $modelLabel = 'Jadwal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $guru = \App\Models\Guru::find($state);
                        if ($guru && !$guru->mapel) {
                            Notification::make()
                                ->warning()
                                ->title('Peringatan')
                                ->body('Guru ini belum memiliki mata pelajaran.')
                                ->persistent()
                                ->send();
                            
                            $set('mapel_id', null);
                        } else {
                            $set('mapel_id', $guru ? $guru->mapel->nama : null);
                        }
                    }),
                Forms\Components\TextInput::make('mapel_id')
                    ->label('Mata Pelajaran ID')
                    ->disabled()
                    ->afterStateHydrated(function ($get, callable $set, $state) {

                        $guru = \App\Models\Guru::find($get('guru_id'));
                        $set('mapel_id', $guru ? $guru->mapel->id : null);
                    })
                    ->reactive(),
                Forms\Components\Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()
                    ->label('Kelas'),
                Forms\Components\Select::make('hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                    ])
                    ->required()
                    ->default(function () {
                        return request()->get('hari');
                    }),
                Forms\Components\TimePicker::make('jam')
                    ->required()
                    ->withoutSeconds()
                    ->format('H:i')
                    ->hoursStep(1)
                    ->minutesStep(5)
                    ->default(function () {
                        return request()->get('jam');
                    }),

                Forms\Components\Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama', function ($query) {
                        return $query->where('status', '1');
                    })
                    ->required()
                    ->label('Tahun Ajaran'),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hari')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guru.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guru.mapel.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam')
                    ->dateTime('H:i')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahunAjaran.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->sortable()
                    ->label('Kelas'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama'),
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas'),
                    
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
            ])
            ->headerActions([
                ExportAction::make()
                ->exporter(JadwalExporter::class)
                ->columnMapping(false),
                // Action::make('aturJadwal')
                //     ->label('Atur Jadwal Otomatis')
                //     ->action(function () {
                //         $jadwals = Jadwal::all();
                //         self::aturJadwalOtomatis($jadwals);
                //     })
                //     ->color('success')
                //     ->icon('heroicon-o-clock')
                //     ->requiresConfirmation()
                //     ->modalHeading('Atur Jadwal Otomatis')
                //     ->modalDescription('Sistem akan mengatur ulang jam pada jadwal yang bertabrakan. Lanjutkan?'),
            ]);
    }

    // private static function aturJadwalOtomatis(Collection $jadwals): void
    // {
    //     // Kelompokkan jadwal berdasarkan hari
    //     $jadwalPerHari = $jadwals->groupBy('hari');

    //     foreach ($jadwalPerHari as $hari => $jadwalHari) {
    //         $jadwalTerurut = $jadwalHari->sortBy('jam');
    //         $jamTerakhir = null;
            
    //         foreach ($jadwalTerurut as $jadwal) {
    //             if ($jamTerakhir !== null) {
    //                 $jamSekarang = strtotime($jadwal->jam);
    //                 $selisihMenit = ($jamSekarang - $jamTerakhir) / 60;

    //                 // Jika jadwal bertabrakan (selisih kurang dari 40 menit)
    //                 if ($selisihMenit < 40) {
    //                     // Atur jadwal 40 menit setelah jadwal sebelumnya
    //                     $jamBaru = date('H:i', $jamTerakhir + (40 * 60));
    //                     $jadwal->update(['jam' => $jamBaru]);
    //                 }
    //             }
                
    //             $jamTerakhir = strtotime($jadwal->jam);
    //         }
    //     }

    //     Notification::make()
    //         ->title('Jadwal berhasil diatur')
    //         ->success()
    //         ->send();
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwals::route('/'),
            'create' => Pages\CreateJadwal::route('/create'),
            'edit' => Pages\EditJadwal::route('/{record}/edit'),
        ];
    }
}
