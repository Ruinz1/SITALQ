<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Peserta;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KelasHasPeserta;
use App\Mail\PesertaTerdaftarKelas;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Unique;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\KelasHasPesertaExporter;
use App\Filament\Exports\KelasHasPesertaPdfExporter;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ActionGroup;

class KelasHasPesertaRelationManager extends RelationManager
{
    protected static string $relationship = 'kelasHasPeserta';

    protected static ?string $title = 'Peserta Kelas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peserta_id')
                    ->label('Peserta')
                    ->options(function () {
                        $tahunAjaranId = $this->getOwnerRecord()->tahun_ajaran_id;
                        
                        // Debug: Cek data peserta untuk tahun ajaran tertentu
                        // dd([
                        //     'tahun_ajaran_id' => $tahunAjaranId,
                        //     'peserta_check' => Peserta::with(['kodePendaftaran.pendaftaran'])
                        //         ->where('status_peserta', 'diterima')
                        //         ->get()
                        //         ->map(function($peserta) {
                        //             return [
                        //                 'id' => $peserta->id,
                        //                 'nama' => $peserta->nama,
                        //                 'status' => $peserta->status_peserta,
                        //                 'kode_pendaftaran' => $peserta->kodePendaftaran ? [
                        //                     'id' => $peserta->kodePendaftaran->id,
                        //                     'pendaftaran' => $peserta->kodePendaftaran->pendaftaran ? [
                        //                         'id' => $peserta->kodePendaftaran->pendaftaran->id,
                        //                         'tahun_ajaran_id' => $peserta->kodePendaftaran->pendaftaran->tahun_ajaran_id
                        //                     ] : 'Tidak ada pendaftaran'
                        //                 ] : 'Tidak ada kode pendaftaran'
                        //             ];
                        //         })->toArray(),
                        //     'pendaftaran_tahun_2' => \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
                        //         ->with(['kodePendaftaran.peserta'])
                        //         ->get()
                        //         ->map(function($pendaftaran) {
                        //             return [
                        //                 'pendaftaran_id' => $pendaftaran->id,
                        //                 'tahun_ajaran_id' => $pendaftaran->tahun_ajaran_id,
                        //                 'kode_pendaftaran' => $pendaftaran->kodePendaftaran ? $pendaftaran->kodePendaftaran->map(function($kode) {
                        //                     return [
                        //                         'kode_id' => $kode->id,
                        //                         'peserta' => $kode->peserta ? $kode->peserta->map(function($p) {
                        //                             return [
                        //                                 'peserta_id' => $p->id,
                        //                                 'nama' => $p->nama,
                        //                                 'status' => $p->status_peserta
                        //                             ];
                        //                         })->toArray() : 'Tidak ada peserta'
                        //                     ];
                        //                 })->toArray() : 'Tidak ada kode pendaftaran'
                        //             ];
                        //         })->toArray(),
                        //     'raw_query' => \App\Models\Pendaftaran::where('tahun_ajaran_id', $tahunAjaranId)
                        //         ->with(['kodePendaftaran.peserta'])
                        //         ->get()
                        //         ->toArray()
                        // ]);

                        $query = Peserta::where('status_peserta', 'diterima')
                            ->whereHas('kodePendaftaran.pendaftaran', function (Builder $query) use ($tahunAjaranId) {
                                $query->where('tahun_ajaran_id', $tahunAjaranId);
                            })
                            ->whereDoesntHave('kelasHasPeserta', function (Builder $query) use ($tahunAjaranId) {
                                $query->whereHas('kelas', function (Builder $q) use ($tahunAjaranId) {
                                    $q->where('tahun_ajaran_id', $tahunAjaranId);
                                });
                            });

                        return $query->pluck('nama', 'id');
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('peserta.nama')
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable(),
                
                    
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ActionGroup::make([
                    ExportAction::make('exportExcel')
                        ->label('Export Excel')
                        ->icon('heroicon-m-arrow-down-on-square')
                        ->exporter(KelasHasPesertaExporter::class)
                        ->color('success')
                        ->tooltip('Download sebagai Excel')
                        ->columnMapping(false),
                        
                    Tables\Actions\Action::make('exportPdf')
                        ->label('Export PDF')
                        ->icon('heroicon-m-arrow-down-on-square')
                        ->url(fn () => route('download.peserta-kelas', ['kelas_id' => $this->getOwnerRecord()->id]))
                        ->color('danger')
                        ->tooltip('Download sebagai PDF')
                        ->openUrlInNewTab(),
                ])
                ->label('Print Peserta Kelas'),

                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peserta')
                    ->visible(function () {
                        $kelas = $this->getOwnerRecord();
                        return $kelas->kelasHasPeserta()->count() < $kelas->kapasitas && 
                               auth()->user()->hasAnyRole(['Admin', 'Super_Admin']);
                    })
                    ->after(function () {
                        $kelas = $this->getOwnerRecord();
                        // Update status kelas
                        if ($kelas->kelasHasPeserta()->count() >= $kelas->kapasitas) {
                            $kelas->update(['status' => 'penuh']);
                        } else {
                            $kelas->update(['status' => 'tersedia']);
                        }
                        $this->getOwnerRecord()->refresh();
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Berhasil ditambahkan')
                            ->body('Data peserta berhasil ditambahkan ke kelas.')
                    ),
                Tables\Actions\Action::make('tambah_otomatis')
                    ->label('Tambah Otomatis')
                    ->requiresConfirmation()
                    ->modalHeading('Tambah Peserta Otomatis')
                    ->modalDescription('Sistem akan menambahkan peserta berdasarkan kriteria akademik dan sosial untuk memastikan keseimbangan kelas yang optimal.')
                    ->modalSubmitActionLabel('Ya, Tambahkan')
                    ->visible(function () {
                        $kelas = $this->getOwnerRecord();
                        return $kelas->kelasHasPeserta()->count() < $kelas->kapasitas && 
                               auth()->user()->hasAnyRole(['Admin', 'Super_Admin']);
                    })
                    ->action(function () {
                        $kelas = $this->getOwnerRecord();
                        $kapasitasTersisa = min(30, $kelas->kapasitas - $kelas->kelasHasPeserta()->count());
                        
                        if ($kapasitasTersisa <= 0) {
                            Notification::make()
                                ->warning()
                                ->title('Kelas sudah penuh')
                                ->send();
                            return;
                        }

                        // Query untuk mendapatkan peserta yang tersedia
                        $pesertaAvailable = Peserta::where('status_peserta', 'diterima')
                            ->with(['informasi', 'keterangan', 'kodePendaftaran.pendaftaran'])
                            ->whereHas('kodePendaftaran.pendaftaran', function (Builder $query) use ($kelas) {
                                $query->where('tahun_ajaran_id', $kelas->tahun_ajaran_id);
                            })
                            ->whereDoesntHave('kelasHasPeserta', function (Builder $query) use ($kelas) {
                                $query->whereHas('kelas', function (Builder $q) use ($kelas) {
                                    $q->where('tahun_ajaran_id', $kelas->tahun_ajaran_id);
                                });
                            })
                            ->get();

                        // Gunakan algoritma greedy
                        $pesertaSorted = $this->greedyPrioritization($pesertaAvailable, $kapasitasTersisa);

                        $jumlahDitambahkan = 0;
                        
                        foreach ($pesertaSorted as $peserta) {
                            KelasHasPeserta::create([
                                'kelas_id' => $kelas->id,
                                'peserta_id' => $peserta->id,
                            ]);
                            $jumlahDitambahkan++;
                        }

                        // Update status kelas jika mencapai kapasitas
                        if ($kelas->kelasHasPeserta()->count() >= $kelas->kapasitas) {
                            $kelas->update(['status' => 'penuh']);
                        }

                        Notification::make()
                            ->success()
                            ->title("Berhasil menambahkan {$jumlahDitambahkan} peserta")
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Tambah Peserta Otomatis')
                    ->modalDescription('Sistem akan menambahkan peserta secara otomatis berdasarkan kriteria akademik dan sosial untuk memastikan keseimbangan kelas yang optimal.')
                    ->modalSubmitActionLabel('Ya, Tambahkan')
                    ->visible(function () {
                        $kelas = $this->getOwnerRecord();
                        return $kelas->kelasHasPeserta()->count() < $kelas->kapasitas && 
                               auth()->user()->hasAnyRole(['Admin', 'Super_Admin']);
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Hapus Peserta')
                    ->modalDescription(fn (KelasHasPeserta $record): string => "Apakah anda yakin ingin menghapus peserta {$record->peserta->nama}?")
                    ->after(function () {
                        $kelas = $this->getOwnerRecord();
                        // Update status kelas setelah menghapus
                        if ($kelas->kelasHasPeserta()->count() >= $kelas->kapasitas) {
                            $kelas->update(['status' => 'penuh']);
                        } else {
                            $kelas->update(['status' => 'tersedia']);
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function () {
                            $kelas = $this->getOwnerRecord();
                            $jumlahPeserta = $kelas->kelasHasPeserta()->count();
                            
                            if ($jumlahPeserta <= $kelas->kapasitas && $kelas->status === 'penuh') {
                                $kelas->update(['status' => 'tersedia']);
                            }
                        }),
                    
                    Tables\Actions\BulkAction::make('kirim_email')
                        ->visible(fn () => auth()->user()->hasAnyRole(['Admin', 'Super_Admin']))
                        ->label('Kirim Email Pemberitahuan')
                        ->icon('heroicon-o-envelope')
                        ->requiresConfirmation()
                        ->modalHeading('Kirim Email Pemberitahuan')
                        ->modalDescription('Email akan dikirim ke peserta yang dipilih untuk memberitahukan bahwa mereka terdaftar di kelas ini.')
                        ->modalSubmitActionLabel('Ya, Kirim Email')
                        ->action(function ($records) {
                            $kelas = $this->getOwnerRecord();
                            
                            foreach ($records as $record) {
                                $peserta = $record->peserta;
                                
                                // Kirim email ke peserta
                                Mail::to($peserta->email)->send(
                                    new PesertaTerdaftarKelas($peserta, $kelas)
                                );
                            }

                            Notification::make()
                                ->success()
                                ->title('Email berhasil dikirim')
                                ->body('Email pemberitahuan telah dikirim ke peserta yang dipilih.')
                                ->send();
                        }),
                ]),
            ]);
    }

    private function greedyPrioritization($pesertaAvailable, $kapasitasTersisa)
    {
        // Bobot untuk setiap kriteria
        $weights = [
            'pergaulan_dengan_sebaya' => 0.25,
            'keterangan_membaca' => 0.25,
            'keterangan_menulis' => 0.25,
            'keterangan_menghitung' => 0.25
        ];

        // Nilai skor untuk setiap kategori
        $scoreMap = [
            'pergaulan_dengan_sebaya' => [
                'Aktif' => 1.0,
                'Pasif' => 0.5
            ],
            'keterangan_kriteria' => [
                'Sudah mampu' => 1.0,
                'Sedikit bisa' => 0.5,
                'Belum bisa' => 0.25
            ]
        ];

        // Hitung skor prioritas untuk setiap peserta
        $pesertaScored = $pesertaAvailable->map(function ($peserta) use ($weights, $scoreMap) {
            // Cek apakah informasi dan keterangan ada
            if (!$peserta->informasi || !$peserta->keterangan) {
                \Illuminate\Support\Facades\Log::warning('Data tidak lengkap untuk peserta:', [
                    'peserta_id' => $peserta->id,
                    'nama' => $peserta->nama,
                    'informasi_exists' => !!$peserta->informasi,
                    'keterangan_exists' => !!$peserta->keterangan
                ]);
                return [
                    'peserta' => $peserta,
                    'skor' => 0,
                    'detail' => [
                        'nama' => $peserta->nama,
                        'error' => 'Data tidak lengkap'
                    ]
                ];
            }

            // Hitung skor pergaulan
            $skorPergaulan = $scoreMap['pergaulan_dengan_sebaya'][$peserta->informasi->pergaulan_dengan_sebaya] ?? 0;
            
            // Hitung skor membaca
            $skorMembaca = $scoreMap['keterangan_kriteria'][$peserta->keterangan->keterangan_membaca] ?? 0;
            
            // Hitung skor menulis
            $skorMenulis = $scoreMap['keterangan_kriteria'][$peserta->keterangan->keterangan_menulis] ?? 0;
            
            // Hitung skor menghitung
            $skorMenghitung = $scoreMap['keterangan_kriteria'][$peserta->keterangan->keterangan_menghitung] ?? 0;

            // Hitung skor total dengan pembobotan
            $totalSkor = ($weights['pergaulan_dengan_sebaya'] * $skorPergaulan) +
                        ($weights['keterangan_membaca'] * $skorMembaca) +
                        ($weights['keterangan_menulis'] * $skorMenulis) +
                        ($weights['keterangan_menghitung'] * $skorMenghitung);

            // Detail informasi untuk logging
            $detailInfo = [
                'nama' => $peserta->nama,
                'skor_detail' => [
                    'pergaulan' => [
                        'nilai' => $peserta->informasi->pergaulan_dengan_sebaya,
                        'skor' => $skorPergaulan,
                        'bobot' => $weights['pergaulan_dengan_sebaya'],
                        'nilai_akhir' => $skorPergaulan * $weights['pergaulan_dengan_sebaya']
                    ],
                    'membaca' => [
                        'nilai' => $peserta->keterangan->keterangan_membaca,
                        'skor' => $skorMembaca,
                        'bobot' => $weights['keterangan_membaca'],
                        'nilai_akhir' => $skorMembaca * $weights['keterangan_membaca']
                    ],
                    'menulis' => [
                        'nilai' => $peserta->keterangan->keterangan_menulis,
                        'skor' => $skorMenulis,
                        'bobot' => $weights['keterangan_menulis'],
                        'nilai_akhir' => $skorMenulis * $weights['keterangan_menulis']
                    ],
                    'menghitung' => [
                        'nilai' => $peserta->keterangan->keterangan_menghitung,
                        'skor' => $skorMenghitung,
                        'bobot' => $weights['keterangan_menghitung'],
                        'nilai_akhir' => $skorMenghitung * $weights['keterangan_menghitung']
                    ],
                ],
                'total_skor' => $totalSkor
            ];

            \Illuminate\Support\Facades\Log::info('Detail Skor Peserta:', $detailInfo);

            return [
                'peserta' => $peserta,
                'skor' => $totalSkor,
                'detail' => $detailInfo
            ];
        });

        // Urutkan dan ambil peserta sesuai kapasitas
        $pesertaSorted = $pesertaScored->sortByDesc('skor')
            ->take($kapasitasTersisa)
            ->map(function ($item) {
                return $item['peserta'];
            });

        // Log hasil akhir pengurutan
        \Illuminate\Support\Facades\Log::info('Hasil Akhir Pengurutan:', [
            'jumlah_peserta_terpilih' => $pesertaSorted->count(),
            'detail_urutan' => $pesertaScored
                ->sortByDesc('skor')
                ->take($kapasitasTersisa)
                ->map(function ($item) {
                    return [
                        'nama' => $item['peserta']->nama,
                        'total_skor' => $item['skor'],
                        'detail_nilai' => $item['detail']['skor_detail']
                    ];
                })->toArray()
        ]);

        return $pesertaSorted;
    }
}