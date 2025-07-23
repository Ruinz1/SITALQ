<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Penilaian;
use App\Models\Mapel;
use App\Models\KelasHasPeserta;
use App\Models\Guru;
use App\Models\Kelas;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class KelasHasPenilaianRelationManager extends RelationManager
{
    protected static string $relationship = 'kelasHasPenilaian';

    protected static ?string $title = 'Nilai Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kelas_has_peserta_id')
                    ->label('Siswa')
                    ->options(function () {
                        $kelasId = $this->getOwnerRecord()->id;
                        return KelasHasPeserta::where('kelas_id', $kelasId)
                            ->with('peserta')
                            ->get()
                            ->pluck('peserta.nama', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $existingMapelIds = Penilaian::where('kelas_has_peserta_id', $state)
                                ->pluck('mapel_id')
                                ->toArray();
                            
                            $availableMapel = Mapel::whereNotIn('id', $existingMapelIds)
                                ->pluck('id')
                                ->toArray();
                                
                            if (!empty($availableMapel)) {
                                $set('mapel_id', $availableMapel[0]);
                            }
                        }
                    }),
                Forms\Components\Select::make('mapel_id')
                    ->label('Mata Pelajaran')
                    ->options(function (Forms\Get $get) {
                        $kelasId = $this->getOwnerRecord()->id;
                        $pesertaId = $get('kelas_has_peserta_id');
                        
                        if ($pesertaId) {
                            $existingMapelIds = Penilaian::where('kelas_has_peserta_id', $pesertaId)
                                ->pluck('mapel_id')
                                ->toArray();
                            
                            return Mapel::whereNotIn('id', $existingMapelIds)
                                ->pluck('nama', 'id');
                        }
                        
                        return Mapel::pluck('nama', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->disabled(fn (Forms\Get $get) => !$get('kelas_has_peserta_id')),
                Forms\Components\TextInput::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $keterangan = [];
                        
                        if ($state >= 90) {
                            $keterangan = ['tuntas', 'sangat_baik'];
                        } elseif ($state >= 80) {
                            $keterangan = ['tuntas', 'baik'];
                        } elseif ($state >= 70) {
                            $keterangan = ['tuntas', 'cukup'];
                        } else {
                            $keterangan = ['belum_tuntas', 'kurang'];
                        }
                        
                        $set('keterangan', $keterangan);
                    }),
                Forms\Components\CheckboxList::make('keterangan')
                    ->label('Keterangan')
                    ->options([
                        'tuntas' => 'Tuntas',
                        'belum_tuntas' => 'Belum Tuntas',
                        'perlu_bimbingan' => 'Perlu Bimbingan',
                        'sangat_baik' => 'Sangat Baik',
                        'baik' => 'Baik',
                        'cukup' => 'Cukup',
                        'kurang' => 'Kurang',
                    ])
                    ->columns(2)
                    ->disabled(),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'ganjil' => 'Ganjil',
                        'genap' => 'Genap',
                    ])
                    ->required(),
                Forms\Components\Hidden::make('status')
                    ->default('pending'),
            ]);
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        $kelas = $this->getOwnerRecord();
        $isGuruAktif = $guru && $kelas->guru_id === $guru->id;

        return $table
            ->recordTitleAttribute('kelasHasPeserta.peserta.nama')
            ->modifyQueryUsing(function (Builder $query) use ($guru, $isGuruAktif) {
                if ($guru && !$isGuruAktif) {
                    return $query->where('mapel_id', $guru->mapel_id);
                }
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('kelasHasPeserta.peserta.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mapel.nama')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function ($record) {
                        return $record->nilai !== null ? 'Selesai' : 'Pending';
                    })
                    ->color(fn ($record): string => match (true) {
                        $record->nilai !== null => 'success',
                        default => 'warning',
                    }),
               
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        'ganjil' => 'Ganjil',
                        'genap' => 'Genap',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('syncPeserta')
                    ->label('Sinkronisasi Peserta')
                    ->icon('heroicon-o-arrow-path')
                    ->visible($isGuruAktif)
                    ->form([
                        Forms\Components\Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'ganjil' => 'Ganjil',
                                'genap' => 'Genap',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $kelasId = $this->getOwnerRecord()->id;
                        $pesertaKelas = KelasHasPeserta::where('kelas_id', $kelasId)->get();
                        $mapels = Mapel::all();
                        $countCreated = 0;
                        
                        if ($mapels->isNotEmpty()) {
                            foreach ($pesertaKelas as $peserta) {
                                foreach ($mapels as $mapel) {
                                    // Cek apakah peserta sudah memiliki penilaian untuk mapel ini di semester yang sama
                                    $existingPenilaian = Penilaian::where('kelas_has_peserta_id', $peserta->id)
                                        ->where('mapel_id', $mapel->id)
                                        ->where('semester', $data['semester'])
                                        ->first();
                                    
                                    if (!$existingPenilaian) {
                                        Penilaian::create([
                                            'kelas_has_peserta_id' => $peserta->id,
                                            'mapel_id' => $mapel->id,
                                            'semester' => $data['semester'],
                                            'status' => 'pending',
                                        ]);
                                        $countCreated++;
                                    }
                                }
                            }

                            if ($countCreated > 0) {
                                Notification::make()
                                    ->title('Sinkronisasi Berhasil')
                                    ->body('Data penilaian berhasil dibuat untuk ' . $countCreated . ' penilaian')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Tidak Ada Data Baru')
                                    ->body('Semua penilaian untuk semester ' . $data['semester'] . ' sudah ada')
                                    ->warning()
                                    ->send();
                            }
                        }
                    }),
                Tables\Actions\Action::make('printNilai')
                    ->label('Print Nilai')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('peserta_id')
                            ->label('Pilih Siswa')
                            ->options(function () {
                                $kelasId = $this->getOwnerRecord()->id;
                                return KelasHasPeserta::where('kelas_id', $kelasId)
                                    ->with('peserta')
                                    ->get()
                                    ->pluck('peserta.nama', 'id');
                            })
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'ganjil' => 'Ganjil',
                                'genap' => 'Genap',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('akhlak')
                            ->label('Akhlak')
                            ->rows(3)
                            ->placeholder('Masukkan penilaian akhlak siswa'),
                        Forms\Components\Textarea::make('hafalan')
                            ->label('Hafalan')
                            ->rows(3)
                            ->placeholder('Masukkan penilaian hafalan siswa'),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(3)
                            ->placeholder('Masukkan catatan tambahan'),
                    ])
                    ->action(function (array $data) {
                        return redirect()->route('nilai.print', [
                            'peserta' => $data['peserta_id'],
                            'semester' => $data['semester'],
                            'akhlak' => $data['akhlak'],
                            'hafalan' => $data['hafalan'],
                            'catatan' => $data['catatan']
                        ]);
                    })
                    ->visible(function () use ($guru, $isGuruAktif) {
                        if (!$guru) return false;
                        return $isGuruAktif;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('inputNilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(function (Penilaian $record) use ($guru, $isGuruAktif) {
                        if (!$guru) return false;
                        if ($isGuruAktif) return $record->nilai === null;
                        return $record->nilai === null && $record->mapel_id === $guru->mapel_id;
                    })
                    ->form([
                        Forms\Components\TextInput::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $keterangan = [];
                        
                        if ($state >= 90) {
                            $keterangan = ['Tuntas', 'Sangat Baik'];
                        } elseif ($state >= 80) {
                            $keterangan = ['Tuntas', 'Baik'];
                        } elseif ($state >= 70) {
                            $keterangan = ['Tuntas', 'Cukup'];
                        } else {
                            $keterangan = ['Tidak Tuntas', 'Kurang'];
                        }
                        
                        $set('keterangan', $keterangan);
                    }),
                Forms\Components\CheckboxList::make('keterangan')
                    ->label('Keterangan')
                    ->options([
                        'Tuntas' => 'Tuntas',
                        'Tidak Tuntas' => 'Tidak Tuntas',
                        'Sangat Baik' => 'Sangat Baik',
                        'Baik' => 'Baik',
                        'Cukup' => 'Cukup',
                        'Kurang' => 'Kurang',
                    ])
                    ->columns(2)
                    ->disabled(),
                    ])
                    ->action(function (Penilaian $record, array $data): void {
                        $keterangan = [];
                        
                        if ($data['nilai'] >= 90) {
                            $keterangan = ['Tuntas', 'Sangat Baik'];
                        } elseif ($data['nilai'] >= 80) {
                            $keterangan = ['Tuntas', 'Baik'];
                        } elseif ($data['nilai'] >= 70) {
                            $keterangan = ['Tuntas', 'Cukup'];
                        } else {
                            $keterangan = ['Tidak Tuntas', 'Kurang'];
                        }

                        $record->update([
                            'nilai' => $data['nilai'],
                            'keterangan' => $keterangan,
                            'status' => 'approved'
                        ]);

                        Notification::make()
                            ->title('Nilai berhasil disimpan')
                            ->body('Nilai ' . $data['nilai'] . ' berhasil disimpan untuk ' . $record->kelasHasPeserta->peserta->nama)
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('updateNilai')
                    ->label('Update Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(function (Penilaian $record) use ($guru, $isGuruAktif) {
                        if (!$guru) return false;
                        if ($isGuruAktif) return $record->nilai !== null;
                        return $record->nilai !== null && $record->mapel_id === $guru->mapel_id;
                    })
                    ->form([
                        Forms\Components\TextInput::make('nilai')
                            ->label('Nilai')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->required()
                            ->default(fn (Penilaian $record) => $record->nilai)
                            ->reactive(),
                        Forms\Components\CheckboxList::make('keterangan')
                            ->label('Keterangan')
                            ->options([
                                'Tuntas' => 'Tuntas',
                                'Tidak Tuntas' => 'Tidak Tuntas',
                                'Sangat Baik' => 'Sangat Baik',
                                'Baik' => 'Baik',
                                'Cukup' => 'Cukup',
                                'Kurang' => 'Kurang',
                            ])
                            ->columns(2)
                            ->default(fn (Penilaian $record) => $record->keterangan)
                            ->maxItems(fn (\Filament\Forms\Get $get) => $get('nilai') > 80 ? null : 2),
                    ])
                    ->action(function (Penilaian $record, array $data): void {
                        $keterangan = [];
                        
                        if ($data['nilai'] >= 90) {
                            $keterangan = ['Tuntas', 'Sangat Baik'];
                        } elseif ($data['nilai'] >= 80) {
                            $keterangan = ['Tuntas', 'Baik'];
                        } elseif ($data['nilai'] >= 70) {
                            $keterangan = ['Tuntas', 'Cukup'];
                        } else {
                            $keterangan = ['Tidak Tuntas', 'Kurang'];
                        }

                        $record->update([
                            'nilai' => $data['nilai'],
                            'keterangan' => $keterangan,
                            'status' => 'approved'
                        ]);

                        Notification::make()
                            ->title('Nilai berhasil disimpan')
                            ->body('Nilai ' . $data['nilai'] . ' berhasil disimpan untuk ' . $record->kelasHasPeserta->peserta->nama)
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public function kelasHasPenilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }
}
