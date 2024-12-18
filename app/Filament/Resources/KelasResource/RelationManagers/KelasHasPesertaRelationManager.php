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

    private const CRITERIA_WEIGHTS = [
        'pergaulan_dengan_sebaya' => 0.25,
        'keterangan_membaca' => 0.25,
        'keterangan_menulis' => 0.25,
        'keterangan_menghitung' => 0.25
    ];

    private const SCORE_MAPPING = [
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('peserta_id')
                    ->label('Peserta')
                    ->options(fn () => $this->getAvailablePesertaOptions())
                    ->searchable()
                    ->required(),
            ]);
    }

    private function getAvailablePesertaOptions(): array
    {
        $tahunAjaranId = $this->getOwnerRecord()->tahun_ajaran_id;
        
        return Peserta::where('status_peserta', 'diterima')
            ->whereHas('kodePendaftaran.pendaftaran', function (Builder $query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->whereDoesntHave('kelasHasPeserta', function (Builder $query) use ($tahunAjaranId) {
                $query->whereHas('kelas', function (Builder $q) use ($tahunAjaranId) {
                    $q->where('tahun_ajaran_id', $tahunAjaranId);
                });
            })
            ->pluck('nama', 'id')
            ->toArray();
    }

    private function updateKelasStatus(): void
    {
        $kelas = $this->getOwnerRecord();
        $status = $kelas->kelasHasPeserta()->count() >= $kelas->kapasitas ? 'penuh' : 'tersedia';
        $kelas->update(['status' => $status]);
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
                    ->form([
                        Forms\Components\Select::make('algoritma')
                            ->label('Pilih Algoritma')
                            ->options([
                                'greedy' => 'Greedy (Berdasarkan Kriteria Akademik & Sosial)',
                                'kmeans' => 'K-Means (Berdasarkan Umur)'
                            ])
                            ->required()
                    ])
                    ->modalHeading('Tambah Peserta Otomatis')
                    ->modalDescription('Pilih algoritma yang akan digunakan untuk menambahkan peserta secara otomatis.')
                    ->modalSubmitActionLabel('Ya, Tambahkan')
                    ->visible(function () {
                        $kelas = $this->getOwnerRecord();
                        return $kelas->kelasHasPeserta()->count() < $kelas->kapasitas && 
                               auth()->user()->hasAnyRole(['Admin', 'Super_Admin']);
                    })
                    ->action(function (array $data) {
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

                        $pesertaSorted = $data['algoritma'] === 'greedy' 
                            ? $this->greedyPrioritization($pesertaAvailable, $kapasitasTersisa)
                            : $this->kmeansPrioritization($pesertaAvailable, $kapasitasTersisa);

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
        $pesertaScored = $pesertaAvailable->map(function ($peserta) {
            if (!$peserta->informasi || !$peserta->keterangan) {
                return $this->createEmptyScore($peserta);
            }

            $totalSkor = $this->calculateTotalScore($peserta);
            return $this->createDetailedScore($peserta, $totalSkor);
        });

        return $pesertaScored->sortByDesc('skor')
            ->take($kapasitasTersisa)
            ->map(fn($item) => $item['peserta']);
    }

    private function createEmptyScore($peserta)
    {
        return [
            'peserta' => $peserta,
            'skor' => 0,
            'detail' => [
                'nama' => $peserta->nama,
                'error' => 'Data tidak lengkap'
            ]
        ];
    }

    private function calculateTotalScore($peserta)
    {
        $skorPergaulan = self::SCORE_MAPPING['pergaulan_dengan_sebaya'][$peserta->informasi->pergaulan_dengan_sebaya] ?? 0;
        
        $skorMembaca = self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_membaca] ?? 0;
        
        $skorMenulis = self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_menulis] ?? 0;
        
        $skorMenghitung = self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_menghitung] ?? 0;

        $totalSkor = (self::CRITERIA_WEIGHTS['pergaulan_dengan_sebaya'] * $skorPergaulan) +
                    (self::CRITERIA_WEIGHTS['keterangan_membaca'] * $skorMembaca) +
                    (self::CRITERIA_WEIGHTS['keterangan_menulis'] * $skorMenulis) +
                    (self::CRITERIA_WEIGHTS['keterangan_menghitung'] * $skorMenghitung);

        return $totalSkor;
    }

    private function createDetailedScore($peserta, $totalSkor)
    {
        $detailInfo = [
            'nama' => $peserta->nama,
            'skor_detail' => [
                'pergaulan' => [
                    'nilai' => $peserta->informasi->pergaulan_dengan_sebaya,
                    'skor' => self::SCORE_MAPPING['pergaulan_dengan_sebaya'][$peserta->informasi->pergaulan_dengan_sebaya] ?? 0,
                    'bobot' => self::CRITERIA_WEIGHTS['pergaulan_dengan_sebaya'],
                    'nilai_akhir' => self::CRITERIA_WEIGHTS['pergaulan_dengan_sebaya'] * (self::SCORE_MAPPING['pergaulan_dengan_sebaya'][$peserta->informasi->pergaulan_dengan_sebaya] ?? 0)
                ],
                'membaca' => [
                    'nilai' => $peserta->keterangan->keterangan_membaca,
                    'skor' => self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_membaca] ?? 0,
                    'bobot' => self::CRITERIA_WEIGHTS['keterangan_membaca'],
                    'nilai_akhir' => self::CRITERIA_WEIGHTS['keterangan_membaca'] * (self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_membaca] ?? 0)
                ],
                'menulis' => [
                    'nilai' => $peserta->keterangan->keterangan_menulis,
                    'skor' => self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_menulis] ?? 0,
                    'bobot' => self::CRITERIA_WEIGHTS['keterangan_menulis'],
                    'nilai_akhir' => self::CRITERIA_WEIGHTS['keterangan_menulis'] * (self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_menulis] ?? 0)
                ],
                'menghitung' => [
                    'nilai' => $peserta->keterangan->keterangan_menghitung,
                    'skor' => self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_menghitung] ?? 0,
                    'bobot' => self::CRITERIA_WEIGHTS['keterangan_menghitung'],
                    'nilai_akhir' => self::CRITERIA_WEIGHTS['keterangan_menghitung'] * (self::SCORE_MAPPING['keterangan_kriteria'][$peserta->keterangan->keterangan_menghitung] ?? 0)
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
    }

    private function kmeansPrioritization($pesertaAvailable, $kapasitasTersisa)
    {
        // Jika tidak ada peserta yang tersedia, return collection kosong
        if ($pesertaAvailable->isEmpty()) {
            return collect();
        }

        // Ekstrak umur peserta
        $pesertaWithAge = $pesertaAvailable->map(function ($peserta) {
            return [
                'peserta' => $peserta,
                'umur' => \Carbon\Carbon::parse($peserta->tanggal_lahir)->age
            ];
        });

        // Tentukan jumlah cluster (k) berdasarkan kapasitas
        $k = min(3, ceil($kapasitasTersisa / 10)); // Maksimal 3 cluster
        
        // Pastikan k minimal 1
        $k = max(1, $k);

        // Inisialisasi centroid awal (random)
        $umurMin = $pesertaWithAge->min('umur');
        $umurMax = $pesertaWithAge->max('umur');
        $centroids = collect();
        
        // Hindari pembagian dengan nol jika k = 1
        if ($k === 1) {
            $centroids->push($umurMin);
        } else {
            for ($i = 0; $i < $k; $i++) {
                $centroids->push($umurMin + ($i * ($umurMax - $umurMin) / ($k - 1)));
            }
        }

        // Iterasi maksimal untuk k-means
        $maxIterations = 100;
        $iteration = 0;
        $previousCentroids = null;

        // K-means clustering
        do {
            // Assign setiap peserta ke cluster terdekat
            $clusters = collect(array_fill(0, $k, collect()));
            
            foreach ($pesertaWithAge as $data) {
                $minDistance = PHP_FLOAT_MAX;
                $clusterIndex = 0;
                
                for ($i = 0; $i < $k; $i++) {
                    $distance = abs($data['umur'] - $centroids[$i]);
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $clusterIndex = $i;
                    }
                }
                
                $clusters[$clusterIndex]->push($data);
            }

            // Simpan centroid sebelumnya untuk cek konvergensi
            $previousCentroids = collect($centroids->all());

            // Update centroid
            for ($i = 0; $i < $k; $i++) {
                if ($clusters[$i]->isNotEmpty()) {
                    $centroids[$i] = $clusters[$i]->average('umur');
                }
            }

            $iteration++;
        } while (!$previousCentroids->every(fn($value, $key) => abs($value - $centroids[$key]) < 0.0001) && $iteration < $maxIterations);

        // Urutkan cluster berdasarkan rata-rata umur
        $sortedClusters = $clusters->sortBy(function ($cluster) {
            return $cluster->average('umur');
        });

        // Ambil peserta dari setiap cluster secara merata
        $selectedPeserta = collect();
        $pesertaPerCluster = ceil($kapasitasTersisa / $k);

        foreach ($sortedClusters as $cluster) {
            $clusterPeserta = $cluster->take($pesertaPerCluster);
            $selectedPeserta = $selectedPeserta->concat($clusterPeserta);
        }

        // Ambil hanya sejumlah kapasitas yang tersisa
        return $selectedPeserta->take($kapasitasTersisa)->map(function ($data) {
            return $data['peserta'];
        });
    }
}