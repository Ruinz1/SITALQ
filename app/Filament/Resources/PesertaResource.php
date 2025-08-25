<?php

namespace App\Filament\Resources;

use Swift;
use Filament\Forms;
use Filament\Tables;
use App\Models\Peserta;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Twilio\Rest\Client;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TahunAjaran;
use App\Mail\PenerimaanPeserta;
use App\Models\KodePendaftaran;
use Filament\Resources\Resource;
use App\Services\WhatsAppService;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PesertaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;
    protected static ?string $pluralModelLabel = 'Siswa';
    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Registration Management';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            
            Forms\Components\Wizard::make([

            Forms\Components\Wizard\Step::make('Data Peserta')
                ->schema([
                    Grid::make(2)
                    ->schema([
                    Forms\Components\Select::make('kode_pendaftaran_id')
                        ->relationship(
                            name: 'kodePendaftaran',
                            titleAttribute: 'kode'
                        )
                        ->label('Kode Pendaftaran')
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state) {
                                $kodePendaftaran = KodePendaftaran::with('pendaftaran.tahunAjaran')
                                    ->find($state);
                                
                                if ($kodePendaftaran?->pendaftaran?->tahunAjaran) {
                                    $set('tahun_ajaran_masuk', $kodePendaftaran->pendaftaran->tahunAjaran->nama);
                                }
                            }
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText('Pilih kode pendaftaran'),
                    // ... konfigurasi lainnya ...
                                Forms\Components\TextInput::make('tahun_ajaran_masuk')
                                    ->label('Tahun Ajaran Masuk')
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('Tahun ajaran diambil otomatis dari kode pendaftaran')
                                    ->afterStateHydrated(function ($component, $state) {
                                        if ($state) {
                                            $tahunAjaran = TahunAjaran::find($state);
                                            if ($tahunAjaran) {
                                                $component->state($tahunAjaran->nama);
                                            }
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Lengkap   ')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->required()
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('agama')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->required(),
                                Forms\Components\TextInput::make('jenis_kelamin')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('alamat')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nama_panggilan')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('bahasa_sehari')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('anak_ke')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('jumlah_saudara_kandung')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('Jumlah Saudara Kandung')
                                    ->placeholder('-')
                                    ->hint('Isi 0 jika tidak memiliki saudara kandung')
                                    ->beforeStateDehydrated(function ($state) {
                                        return $state === '-' ? 0 : $state;
                                    })
                                    ->live()
                                    ->validationMessages([
                                        'min' => 'Jumlah saudara kandung tidak boleh kurang dari 0',
                                    ]),
                                Forms\Components\TextInput::make('jumlah_saudara_tiri')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->label('Jumlah Saudara Tiri')
                                    ->placeholder('-')
                                    ->live()
                                    ->hint('Isi 0 jika tidak memiliki saudara tiri')
                                    ->beforeStateDehydrated(function ($state) {
                                        return $state === '-' ? 0 : $state;
                                    })
                                    ->validationMessages([
                                        'min' => 'Jumlah saudara tiri tidak boleh kurang dari 0',
                                    ]),
                                Forms\Components\Repeater::make('saudara')
                                    ->relationship('saudara')
                                    ->schema([
                                        Forms\Components\TextInput::make('nama')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('hubungan')
                                            ->required()
                                            ->options([
                                                'Kandung' => 'Kandung',
                                                'Tiri' => 'Tiri',
                                            ]),
                                        Forms\Components\TextInput::make('umur')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->hidden(function (Get $get): bool {
                                        $jumlahSaudaraKandung = $get('jumlah_saudara_kandung');
                                        $jumlahSaudaraTiri = $get('jumlah_saudara_tiri');
                                        
                                        // Convert '-' to 0 if necessary
                                        $jumlahSaudaraKandung = $jumlahSaudaraKandung === '-' ? 0 : (int)$jumlahSaudaraKandung;
                                        $jumlahSaudaraTiri = $jumlahSaudaraTiri === '-' ? 0 : (int)$jumlahSaudaraTiri;
                                        
                                        return ($jumlahSaudaraKandung + $jumlahSaudaraTiri) <= 0;
                                    })
                                    ->minItems(function (Get $get): int {
                                        $jumlahSaudaraKandung = $get('jumlah_saudara_kandung');
                                        $jumlahSaudaraTiri = $get('jumlah_saudara_tiri');
                                        
                                        // Convert '-' to 0 if necessary
                                        $jumlahSaudaraKandung = $jumlahSaudaraKandung === '-' ? 0 : (int)$jumlahSaudaraKandung;
                                        $jumlahSaudaraTiri = $jumlahSaudaraTiri === '-' ? 0 : (int)$jumlahSaudaraTiri;
                                        
                                        return $jumlahSaudaraKandung + $jumlahSaudaraTiri;
                                    })
                                    ->maxItems(function (Get $get): int {
                                        $jumlahSaudaraKandung = $get('jumlah_saudara_kandung');
                                        $jumlahSaudaraTiri = $get('jumlah_saudara_tiri');
                                        
                                        // Convert '-' to 0 if necessary
                                        $jumlahSaudaraKandung = $jumlahSaudaraKandung === '-' ? 0 : (int)$jumlahSaudaraKandung;
                                        $jumlahSaudaraTiri = $jumlahSaudaraTiri === '-' ? 0 : (int)$jumlahSaudaraTiri;
                                        
                                        return $jumlahSaudaraKandung + $jumlahSaudaraTiri;
                                    })
                                    ->defaultItems(function (Get $get): int {
                                        $jumlahSaudaraKandung = $get('jumlah_saudara_kandung');
                                        $jumlahSaudaraTiri = $get('jumlah_saudara_tiri');
                                        
                                        // Convert '-' to 0 if necessary
                                        $jumlahSaudaraKandung = $jumlahSaudaraKandung === '-' ? 0 : (int)$jumlahSaudaraKandung;
                                        $jumlahSaudaraTiri = $jumlahSaudaraTiri === '-' ? 0 : (int)$jumlahSaudaraTiri;
                                        
                                        return $jumlahSaudaraKandung + $jumlahSaudaraTiri;
                                    })
                                    ->live()
                                    ->columnSpanFull(),
                               
                                    Forms\Components\TextInput::make('berat_badan')
                                    ->numeric()
                                    ->minValue(0)
                                    ->label('Berat Badan (kg)')
                                    ->validationMessages([
                                        'min' => 'Berat badan tidak boleh kurang dari 1 kg',
                                    ]),
                                Forms\Components\TextInput::make('tinggi_badan')
                                    ->numeric()
                                    ->minValue(0)
                                    ->label('Tinggi Badan (cm)')
                                    ->validationMessages([
                                        'min' => 'Tinggi badan tidak boleh kurang dari 1 cm',
                                    ]),
                                Forms\Components\Select::make('penyakitdiderita')
                                    ->label('Mempunyai Penyakit yang Diderita dengan perawatan ?')
                                    ->options([
                                        '1' => 'Ya',
                                        '0' => 'Tidak',
                                    ])
                                    ->default('0')
                                    ->live(),
                                Forms\Components\TextInput::make('penyakit_berapalama')
                                    ->label('Berapa Lama Penyakit Diderita ?')
                                    ->maxLength(255)
                                    ->hidden(fn (Get $get): bool => ! $get('penyakitdiderita')),
                                Forms\Components\TextInput::make('penyakit_kapan')
                                    ->label('Kapan Penyakit Diderita ?')
                                    ->hidden(fn (Get $get): bool => ! $get('penyakitdiderita')),
                                Forms\Components\TextInput::make('penyakit_pantangan')
                                    ->label('Pantangan Penyakit Diderita ?')
                                    ->maxLength(255)
                                    ->hidden(fn (Get $get): bool => ! $get('penyakitdiderita')),
                                Forms\Components\Textarea::make('toilet_traning')
                                    ->label('Toilet Traning (kemampuan ke kamar mandi untuk buang air dll)')
                                    ->maxLength(255),
                                    Forms\Components\Textarea::make('mempunyai_alergi')
                                    ->label('Mempunyai Alergi ?')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('lainnya')
                                    ->label('Lainnya')
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('tanggal_diterima')
                                    ->label('Tanggal Diterima')
                                    ->hidden(fn ($livewire) => $livewire instanceof Pages\CreatePeserta),
                                Forms\Components\ToggleButtons::make('status_peserta')
                                    ->label('Status Peserta')
                                    ->options([
                                        'diterima' => 'Diterima',
                                        'pending' => 'Pending',
                                    ])
                                    ->default('pending')
                                    ->grouped()
                                    ->icons([
                                        'diterima' => 'heroicon-o-check-circle',
                                        'pending' => 'heroicon-o-clock',
                                    ])
                                    ->colors([
                                        'diterima' => 'success',
                                        'pending' => 'warning',
                                    ])
                                    ->required(),
                                Forms\Components\FileUpload::make('ttd_ortu')
                                    ->label('TTD Orang Tua')
                                    ->image()
                                    ->imageEditor()
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1920')
                                    ->imageResizeTargetHeight('1080'),
                                Forms\Components\Select::make('is_pindahan')
                                    ->label('Masuk TK ini Sebagai ')
                                    ->live()
                                    ->required()
                                    ->options([
                                        '0' => 'Murid Baru',
                                        '1' => 'Murid Pindahan',
                                    ])
                                    ->default('0')
                                    ->afterStateHydrated(function ($component, $record) {
                                        if ($record) {
                                            // Jika ini adalah edit form dan record ada
                                            $component->state($record->is_pindahan ? '1' : '0');
                                        } else {
                                            // Jika ini adalah create form
                                            $component->state('0');
                                        }
                                    }),
                                Forms\Components\Textinput::make('asal_tk')
                                    ->label('Asal TK')
                                    ->maxLength(255)
                                    ->required(fn (Get $get): bool => $get('is_pindahan') === '1')
                                    ->hidden(fn (Get $get): bool => $get('is_pindahan') === '0'),
                                Forms\Components\DatePicker::make('tanggal_pindah')
                                    ->label('Tanggal Pindahan')
                                    ->required(fn (Get $get): bool => $get('is_pindahan') === '1')
                                    ->hidden(fn (Get $get): bool => $get('is_pindahan') === '0'),
                                Forms\Components\TextInput::make('kelompok')
                                    ->label('Di Kelompok')
                                    ->maxLength(255)
                                    ,
                ]),
            ]),
                // Tambahkan step baru sebelum step Data Peserta
                Forms\Components\Wizard\Step::make('Data Pendahuluan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Textarea::make('latar_belakang')
                                    ->label('Latar Belakang Mendaftarkan Anak ke TKIT AL-Qolam')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('harapan_keislaman')
                                    ->label('Harapan dalam Bidang Keislaman')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('harapan_keilmuan')
                                    ->label('Harapan dalam Bidang Keilmuan')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('harapan_sosial')
                                    ->label('Harapan dalam Bidang Sosial')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('berapa_lama_bersekolah')
                                    ->label('Berapa Lama Berencana Menyekolahkan Anak di TKIT AL-Qolam')
                                    ->required()
                                    ->options([
                                        '1' => '1 Tahun',
                                        '2' => '2 Tahun',
                                        '3' => '3 Tahun',
                                    ]),
                            ]),
                    ]),
            Forms\Components\Wizard\Step::make('Data Keluarga')
            ->schema([
                Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('ayah.nama')
                    ->label('Nama Ayah')
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        // Debug untuk melihat nilai yang diambil
                        logger('Nama Ayah State:', [$state]);
                    })
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.alamat')
                    ->label('Alamat Ayah')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.tempat_lahir')
                    ->label('Tempat Lahir Ayah')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\DatePicker::make('ayah.tanggal_lahir')
                    ->label('Tanggal Lahir Ayah')
                    ->required(),
                    Forms\Components\TextInput::make('ayah.agama')
                    ->label('Agama Ayah')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.pendidikan_terakhir')
                    ->label('Pendidikan Terakhir Ayah')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.pekerjaan')
                    ->label('Pekerjaan')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.no_hp')
                    ->label('No. HP')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.alamat_kantor')
                    ->label('Alamat Kantor Ayah')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ayah.sosmed')
                    ->label('Sosial Media Ayah')
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.nama')
                    ->label('Nama Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.alamat')
                    ->label('Alamat Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.tempat_lahir')
                    ->label('Tempat Lahir Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\DatePicker::make('ibu.tanggal_lahir')
                    ->label('Tanggal Lahir Ibu')
                    ->required(),
                    Forms\Components\TextInput::make('ibu.agama')
                    ->label('Agama Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.pendidikan_terakhir')
                    ->label('Pendidikan Terakhir Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.pekerjaan')
                    ->label('Pekerjaan Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.no_hp')
                    ->label('No. HP Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.alamat_kantor')
                    ->label('Alamat Kantor Ibu')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('ibu.sosmed')
                    ->label('Sosial Media Ibu')
                    ->maxLength(255),
                    Forms\Components\Select::make('is_wali')
                    ->label('Mempunyai Wali')
                    ->options([
                        '1' => 'Ya',
                        '0' => 'Tidak',
                    ])
                    ->default('0')
                    ->live()
                    ->required()
                    ->afterStateHydrated(function ($component, $record) {
                        if ($record) {
                            // Cek apakah ada data wali yang valid
                            $hasWali = $record->keluarga?->wali && 
                                      $record->keluarga->wali->nama !== null && 
                                      !empty($record->keluarga->wali->nama);
                            
                            $component->state($hasWali ? '1' : '0');
                        } else {
                            // Jika ini adalah create form
                            $component->state('0');
                        }
                    }),
                    Forms\Components\TextInput::make('wali.nama')
                    ->label('Nama Wali')
                    ->live()
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.alamat')
                    ->label('Alamat Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.tempat_lahir')
                    ->label('Tempat Lahir Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\DatePicker::make('wali.tanggal_lahir')
                    ->label('Tanggal Lahir Wali')
                    ->required()
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.agama')
                    ->label('Agama Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.pendidikan_terakhir')
                    ->label('Pendidikan Terakhir Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.pekerjaan')
                    ->label('Pekerjaan Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.no_hp')
                    ->label('No. HP Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.alamat_kantor')
                    ->label('Alamat Kantor Wali')
                    ->required()
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                    Forms\Components\TextInput::make('wali.sosmed')
                    ->label('Sosial Media Wali')
                    ->maxLength(255)
                    ->hidden(fn (Get $get): bool => ! $get('is_wali')),
                ])
            ]),

            Forms\Components\Wizard\Step::make('Data Informasi Peserta')
            ->schema([
                //
            Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('informasi.tinggal_bersama')
                        ->required()
                        ->label('Tinggal Bersama')
                        ->options([
                            'Keluarga Sendiri' => 'Keluarga Sendiri',
                            'Keluarga Orang Lain' => 'Keluarga Orang Lain',
                        ]),
                    Forms\Components\TextInput::make('informasi.jumlah_penghuni_dewasa')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->label('Jumlah Penghuni Dewasa di Rumah')
                        ->validationMessages([
                            'min' => 'Jumlah penghuni dewasa minimal 1 orang',
                        ]),
                    Forms\Components\TextInput::make('informasi.jumlah_penghuni_anak')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->label('Jumlah Penghuni Anak di Rumah')
                        ->validationMessages([
                            'min' => 'Jumlah penghuni anak minimal 0',
                        ]),
                    Forms\Components\Select::make('informasi.halaman_bermain_dirumah')
                        ->required()
                        ->label('Halaman Bermain Dirumah')
                        ->options([
                            'Ada' => 'Ada',
                            'Tidak Ada' => 'Tidak Ada',
                        ]),
                    Forms\Components\Select::make('informasi.pergaulan_dengan_sebaya')
                        ->required()
                        ->label('Pergaulan Dengan Anak Sebayanya')
                        ->options([
                            'Aktif' => 'Aktif',
                            'Pasif' => 'Pasif',
                        ]),
                    Forms\Components\Select::make('informasi.kepatuhan_anak')
                        ->required()
                        ->label('Kepatuhan Anak')
                        ->options([
                            'Baik' => 'Baik',
                            'Cukup' => 'Cukup',
                            'Kurang' => 'Kurang',
                        ]),
                    Forms\Components\Select::make('informasi.hubungan_dengan_ayah')
                        ->required()
                        ->label('Hubungan Dengan Ayah')
                        ->options([
                            'Baik' => 'Baik',
                            'Cukup' => 'Cukup',
                            'Kurang' => 'Kurang',
                        ]),
                    Forms\Components\Select::make('informasi.hubungan_dengan_ibu')
                        ->required()
                        ->label('Hubungan Dengan Ibu')
                        ->options([
                            'Baik' => 'Baik',
                            'Cukup' => 'Cukup',
                            'Kurang' => 'Kurang',
                        ]),
                    Forms\Components\Select::make('informasi.kemampuan_buang_air')
                        ->required()
                        ->label('Kemampuan Buang Air Masih Harus Dibina')
                        ->options([
                            'Ya' => 'Ya',
                            'Tidak' => 'Tidak',
                            'Kadang-kadang' => 'Kadang-kadang',
                        ]),
                        Forms\Components\Select::make('informasi.kebiasaan_ngompol')
                        ->required()
                        ->label('Kebiasaan Ngompol')
                        ->options([
                            'Sering' => 'Sering',
                            'Kadang-kadang' => 'Kadang-kadang',
                            'Jarang' => 'Jarang',
                        ]),
                    
                    Forms\Components\TextArea::make('informasi.selera_makan')
                        ->required()
                        ->label('Selera Makan')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('informasi.kebiasan_tidur_malam')
                        ->required()
                        ->label('Kebiasaan Tidur Malam'),
                    Forms\Components\Textarea::make('informasi.kebiasan_tidur_siang')
                        ->required()
                        ->label('Kebiasaan Tidur Siang'),
                    Forms\Components\Textarea::make('informasi.kebiasan_bangun_siang')
                        ->required()
                        ->label('Kebiasaan Bangun Siang'),
                        
                    Forms\Components\Textarea::make('informasi.kebiasan_bangun_pagi')
                        ->required()
                        ->label('Kebiasaan Bangun Pagi'),
                    
                    Forms\Components\Textarea::make('informasi.hal_penting_waktu_tidur')
                        ->required()
                        ->label('Hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak'),

                        Forms\Components\Textarea::make('informasi.hal_mengenai_tingkah_anak')
                        ->required()
                        ->label('Hal-hal yang perlu dicatat atau dikemukakan mengenai tingkah anak'),
                    Forms\Components\Select::make('informasi.mudah_bergaul')
                        ->required()
                        ->label('Mudah Bergaul')
                        ->options([
                            'Ya' => 'Ya',
                            'Tidak' => 'Tidak',
                            'Kadang-kadang' => 'Kadang-kadang',
                        ]),
                    Forms\Components\Textarea::make('informasi.sifat_baik')
                        ->required()
                        ->label('Sifat Baik')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('informasi.sifat_buruk')
                        ->required()
                        ->label('Sifat Buruk')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('informasi.pembantu_rumah_tangga')
                        ->required()
                        ->label('Pembantu Rumah Tangga')
                        ->maxLength(255),
                    // ... existing code ...
                    Forms\Components\Select::make('informasi.peralatan_elektronik')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Peralatan Elektronik yang Dimiliki')
                    ->options([
                        'TV' => 'TV',
                        'Kulkas' => 'Kulkas',
                        'Mesin Cuci' => 'Mesin Cuci',
                        'AC' => 'AC',
                        'Microwave' => 'Microwave',
                        'Komputer' => 'Komputer',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->default([])
                    ->afterStateHydrated(function ($component, $state) {
                        // State di DB disimpan sebagai JSON string; normalisasi ke array untuk multiple select
                        if (is_string($state) && $state !== '') {
                            $decoded = json_decode($state, true);
                            // Jika double-encoded (mis. "[\"tv\",\"kulkas\"]"), decode dua kali
                            if (is_string($decoded)) {
                                $decoded = json_decode($decoded, true);
                            }
                            if (is_array($decoded)) {
                                $normalized = array_map(function ($value) {
                                    $v = strtolower((string) $value);
                                    return match ($v) {
                                        'tv' => 'TV',
                                        'kulkas' => 'Kulkas',
                                        'mesin cuci', 'mesin_cuci' => 'Mesin Cuci',
                                        'ac' => 'AC',
                                        'microwave' => 'Microwave',
                                        'komputer' => 'Komputer',
                                        'lainnya' => 'Lainnya',
                                        default => $value,
                                    };
                                }, $decoded);
                                $component->state(array_values(array_unique($normalized)));
                            } else {
                                $component->state([]);
                            }
                        } elseif (is_array($state)) {
                            // Pastikan case konsisten saat edit
                            $normalized = array_map(function ($value) {
                                $v = strtolower((string) $value);
                                return match ($v) {
                                    'tv' => 'TV',
                                    'kulkas' => 'Kulkas',
                                    'mesin cuci', 'mesin_cuci' => 'Mesin Cuci',
                                    'ac' => 'AC',
                                    'microwave' => 'Microwave',
                                    'komputer' => 'Komputer',
                                    'lainnya' => 'Lainnya',
                                    default => $value,
                                };
                            }, $state);
                            $component->state(array_values(array_unique($normalized)));
                        } else {
                            $component->state([]);
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        // Simpan sebagai JSON string dengan format snake_case lower untuk konsistensi lama
                        if (is_array($state)) {
                            $normalized = array_map(function ($value) {
                                $v = (string) $value;
                                return match ($v) {
                                    'TV' => 'tv',
                                    'Kulkas' => 'kulkas',
                                    'Mesin Cuci' => 'mesin_cuci',
                                    'AC' => 'ac',
                                    'Microwave' => 'microwave',
                                    'Komputer' => 'komputer',
                                    'Lainnya' => 'lainnya',
                                    default => strtolower(str_replace(' ', '_', $v)),
                                };
                            }, $state);
                            return json_encode(array_values(array_unique($normalized)));
                        }
                        return $state;
                    })
                    ->helperText('Pilih satu atau lebih peralatan elektronik yang dimiliki')
                    ->columnSpanFull()
                ])
            ]),
            Forms\Components\Wizard\Step::make('Data Keterangan Peserta')
                ->schema([
                                //
                            Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('keterangan.keterangan_membaca')
                                        ->required()
                                        ->label('Keterangan Membaca')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),
                                    Forms\Components\TextArea::make('keterangan.judulbuku_berlatihmembaca_latin')
                                        ->required()
                                        ->label('Judul Buku Berlatih Membaca Latin')
                                        ->maxLength(255),

                                    Forms\Components\Select::make('keterangan.keterangan_membaca_hijaiyah')
                                        ->required()
                                        ->label('Keterangan Membaca Hijaiyah')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),

                                    Forms\Components\TextArea::make('keterangan.judulbuku_berlatihmembaca_hijaiyah')
                                        ->required()
                                        ->label('Buku yang digunakan untuk Berlatih Membaca Hijaiyah')
                                        ->maxLength(255),

                                    Forms\Components\TextInput::make('keterangan.jilid_hijaiyah')
                                        ->required()
                                        ->label('Jilid Iqro')
                                        ->maxLength(255),


                                    Forms\Components\Select::make('keterangan.keterangan_menulis')
                                        ->required()
                                        ->label('Keterangan Menulis')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),

                                    Forms\Components\Select::make('keterangan.keterangan_angka')
                                        ->required()
                                        ->label('Anak Sudah Mengenal Angka ')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),

                                    Forms\Components\Select::make('keterangan.keterangan_menghitung')
                                        ->required()
                                        ->label('Keterangan Menghitung')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),
                                    Forms\Components\Select::make('keterangan.keterangan_menggambar')
                                        ->required()
                                        ->label('Keterangan Menggambar')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),
                                    Forms\Components\Select::make('keterangan.keterangan_berwudhu')
                                        ->required()
                                        ->label('Keterangan Berwudhu')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),
                                    Forms\Components\Select::make('keterangan.keterangan_tata_cara_shalat')
                                        ->required()
                                        ->label('Keterangan Tata Cara Shalat')
                                        ->options([
                                            'Belum bisa' => 'Belum bisa',
                                            'Sedikit bisa' => 'Sedikit bisa',
                                            'Sudah mampu' => 'Sudah mampu',
                                        ]),
                                    Forms\Components\Textarea::make('keterangan.keterangan_hafalan_juz_ama')
                                        ->required()
                                        ->label('Keterangan Hafalan Juz Amma'),

                                        
                                    Forms\Components\Select::make('keterangan.keterangan_hafalan_murottal')
                                        ->required()
                                        ->label('Keterangan Mendengar Murottal')
                                        ->options([
                                            'Sering' => 'Sering',
                                            'Kadang-kadang' => 'Kadang-kadang',
                                            'Jarang' => 'Jarang',
                                        ]),

                                    Forms\Components\Textinput::make('keterangan.hobi')
                                        ->required()
                                        ->label('Hobi Anak'),

                                    Forms\Components\Textarea::make('keterangan.keterangan_hafalan_doa')
                                        ->required()
                                        ->label('Keterangan Hafalan Doa'),

                                    Forms\Components\Textarea::make('keterangan.keterangan_hafal_surat')
                                        ->required()
                                        ->label('Keterangan Hafalan Surah'),

                                    Forms\Components\Textarea::make('keterangan.keterangan_majalah')
                                        ->required()
                                        ->label('Keterangan Berlangganan Majalah'),

                                    Forms\Components\Textarea::make('keterangan.keterangan_kisah_islami')
                                        ->required()
                                        ->label('Keterangan Mendengar Kisah Islami'),
                                    
                                ]),
                        ]),
            Forms\Components\Wizard\Step::make('Data Pendaanaan dan Survei Peserta')
                ->schema([
                            //
                            Grid::make(2)
                                ->schema([
                                    //
                                    Forms\Components\Select::make('pendanaan.pemasukan_perbulan_orang_tua')
                                        ->required()
                                        ->label('Pemasukan Perbulan Orang Tua')
                                        ->options([
                                            '1' => 'Rp 500.000 < Rp. 1.500.000',
                                            '2' => 'Rp. 1.500.000 < Rp. 2.500.000',
                                            '3' => '> Rp. 2.500.000',
                                        ]),
                                    Forms\Components\Textarea::make('pendanaan.keterangan_kenaikan_pendapatan')
                                        ->required()
                                        ->label('Apabila ditengah perjalanan kegiatan belajar mengajar (KBM) terjadi kenaikan harga bahan pokok yang berimbas pada biaya operasional terutama konsumsi, maka Tindakan apa yang harus dilakukan agar menu makanan yang diberikan pada anak-anak tetap stabil (berikan alasan) '),
                                    Forms\Components\Textarea::make('pendanaan.keterangan_infaq')
                                        ->required()
                                        ->label('Untuk mengatasi masalah kenaikan harga bahan pokok, bagaimana apabila orang tua/ wali murid yang mempunya kelebihan rezeki untuk menyisihkan hartanya/ berinfaq secara sukarela?(berikan alasan)'),
                                    Forms\Components\Textarea::make('survei.larangan_menunggu')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, peserta didik tidak boleh ditunggu orrang tua/wali/baby sitster kecuali awal masuk maksimal 2 pekan (berikan alasan) '),
                                    Forms\Components\Textarea::make('survei.larangan_perhiasan')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, peserta didik dilarang memakai perhiasan kecuali anting atau giwang (Berikan alasan)'),
                                    Forms\Components\Textarea::make('survei.berpakaian_islami')
                                        ->required()
                                        ->label('Setuju atau tidak setuju,orang tua wajib berpakaian Islami Ketika berada di lingkungan TKIT AL-Qolam (bagi ibu/penjemput putri di usahakan memakai jilbab). (berikan alasan) '),
                                    Forms\Components\Textarea::make('survei.menghadiri_pertemuan_wali')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, untuk menghadiri pertemuan wali murid 2 bulan sekali '),
                                    Forms\Components\Textarea::make('survei.kontrol_pengembangan')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, untuk melakukan kontrol perkembangan tiap 2 bulan sekali (berikan alasan) '),
                                    Forms\Components\Textarea::make('survei.larangan_merokok')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, untuk tidak merokok di lingkungan TKIT AL-Qolam (berikan alasan) '),
                                    Forms\Components\Textarea::make('survei.tidak_bekerjasama')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, untuk tidak bekerja sama dengan orang lain di lingkungan TKIT AL-Qolam (berikan alasan) '),
                                    Forms\Components\Textarea::make('survei.penjadwalan')
                                        ->required()
                                        ->label('Setuju atau tidak setuju, untuk melakukan penjadwalan tiap 2 bulan sekali (berikan alasan) '),
                                    
                                    

                                ]),
                        ]),
           
            ])
            ->columnSpan('full')
            ->skippable()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kodePendaftaran.kode')
                    ->label('Kode Pendaftaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_ajaran_masuk')
                    ->label('Masuk Pada Tahun Ajaran')
                    ->formatStateUsing(function ($state, $record) {
                        // Ambil tahun ajaran terlebih dahulu
                        $tahunAjaran = $record->kodePendaftaran?->pendaftaran?->tahunAjaran?->nama;
                        
                        if ($state == null) {
                            return $tahunAjaran ? $tahunAjaran . ' (Status: Pending)' : 'Status: Pending';
                        }
                        return $tahunAjaran ?? 'Tahun Ajaran Tidak Ditemukan';
                    })
                    ->searchable()
                    ->sortable()
                    ->description(function ($record) {
                        if ($record->status_peserta === 'pending') {
                            return 'Status: Siswa Masih Pending';
                        }
                        return $record->tanggal_diterima 
                            ? 'Diterima pada: ' . date('d F Y', strtotime($record->tanggal_diterima))
                            : 'Status: Pending';
                    })
                    ->color(function ($record) {
                        return $record->status_peserta === 'pending' ? 'danger' : 'success';
                    })
                    ->icon(function ($record) {
                        return $record->status_peserta === 'pending' ? 'heroicon-o-clock' : 'heroicon-o-check-circle';
                    }),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Peserta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_peserta')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'success',
                        'pending' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'diterima' => 'heroicon-o-check-circle',
                        'pending' => 'heroicon-o-clock',
                        default => 'heroicon-o-x-circle',
                    })
                    ->label('Status Peserta'),
                Tables\Columns\TextColumn::make('transaksi.status_pembayaran')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Sukses',
                        '0' => 'Pending',
                        '2' => 'Gagal',
                        '3' => 'Expired',
                        default => 'Unknown'
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'warning',
                        '2' => 'danger',
                        '3' => 'gray',
                        default => 'gray'
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-clock',
                        '2' => 'heroicon-o-x-circle',
                        '3' => 'heroicon-o-x-mark',
                        default => 'heroicon-o-question-mark-circle'
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                // Tambahkan filter tahun ajaran
                Tables\Filters\SelectFilter::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->relationship('kodePendaftaran.pendaftaran.tahunAjaran', 'nama')
                    ->preload()
                    ->multiple()
                    ->searchable(),
                // Filter status peserta
                Tables\Filters\SelectFilter::make('status_peserta')
                    ->label('Status Peserta')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Peserta $record) => route('peserta.print', $record->id))
                    ->openUrlInNewTab()
                    ->visible(fn (Peserta $record): bool => $record->status_peserta === 'diterima'),
                Tables\Actions\Action::make('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Peserta $record) {
                        try {
                            self::handlePesertaAcceptance($record);
                            Notification::make()
                                ->title('Berhasil')
                                ->body('Peserta berhasil diterima, notifikasi telah dikirim')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            self::handleError($e);
                        }
                    })
                    ->requiresConfirmation()
                    ->visible(function (Peserta $record): bool {
                        // Debug log
                        Log::info('Checking visibility for Terima button', [
                            'peserta_id' => $record->id,
                            'status_peserta' => $record->status_peserta,
                            'transaksi' => $record->transaksi,
                            'status_pembayaran' => $record->transaksi?->status_pembayaran
                        ]);
                        
                        return $record->status_peserta === 'pending' && 
                               $record->transaksi?->status_pembayaran === "1";
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('terima_peserta')
                        ->label('Terima Peserta')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            $successCount = 0;
                            $errorCount = 0;

                            foreach ($records as $record) {
                                try {
                                    if (empty($record->email)) {
                                        $errorCount++;
                                        continue;
                                    }

                                    // Update status dan tanggal diterima
                                    $record->update([
                                        'status_peserta' => 'diterima',
                                        'tanggal_diterima' => now()
                                    ]);
                                    
                                    // Refresh dan load relasi
                                    $record->refresh();
                                    $record->load(['pendaftaran']);
                                    
                                    // Kirim email
                                    Mail::to($record->email)
                                        ->send(new PenerimaanPeserta($record));
                                    
                                    $successCount++;
                                } catch (\Exception $e) {
                                    Log::error('Error pada peserta ' . $record->id . ': ' . $e->getMessage());
                                    $errorCount++;
                                }
                            }

                            // Notifikasi hasil
                            if ($successCount > 0) {
                                Notification::make()
                                    ->title('Berhasil')
                                    ->body("$successCount peserta berhasil diterima dan email telah dikirim" . 
                                          ($errorCount > 0 ? ". $errorCount peserta gagal diproses." : ''))
                                    ->success()
                                    ->send();
                            }

                            if ($errorCount > 0) {
                                Notification::make()
                                    ->title('Perhatian')
                                    ->body("$errorCount peserta gagal diproses. Silakan cek log untuk detail.")
                                    ->warning()
                                    ->send();
                            }
                        })
                        
                        ->visible(function () {
                            return Peserta::where('status_peserta', '!=', 'diterima')->exists();
                        })
                ])
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
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with([
                'kodePendaftaran.pendaftaran.tahunAjaran',
                'kodePendaftaran',
                'keluarga.ayah',
                'keluarga.ibu',
                'keluarga.wali',
                'informasi',
                'keterangan',
                'pendanaan',    
                'survei',
                'transaksi'
            ]);
    }

    // Fungsi untuk mengirim pesan WhatsApp
    private static function handlePesertaAcceptance(Peserta $record): void
    {
        if (empty($record->email)) {
            throw new \Exception('Email peserta tidak ditemukan');
        }

        // Update status
        $record->update([
            'status_peserta' => 'diterima',
            'tanggal_diterima' => now()
        ]);

        // Refresh dan load relasi
        $record->refresh();
        $record->load(['kodePendaftaran', 'keluarga.ayah', 'keluarga.ibu']);

        // Kirim notifikasi
        self::sendNotifications($record);
    }

    private static function sendNotifications(Peserta $record): void
    {
        try {
            // Kirim email
            Mail::to($record->email)->send(new PenerimaanPeserta($record));

            // Log sebelum pengiriman WhatsApp
            Log::info('Mencoba mengirim notifikasi WhatsApp', [
                'peserta' => $record->nama,
                'nomor_ayah' => $record->keluarga?->ayah?->no_hp
            ]);

            // Kirim WhatsApp jika ada nomor ayah
            if ($record->keluarga?->ayah?->no_hp) {
                $whatsapp = new WhatsAppService();
                $message = "Assalamualaikum Warahmatullahi Wabarakatuh, Kami dengan senang hati memberitahukan bahwa Anda telah diterima sebagai peserta di TKIT AL-Qolam. 
                Peserta atas nama {$record->nama} telah diterima di TKIT AL-Qolam.
                Untuk informasi penempatan kelas peserta, akan segera kami informasikan melalui email.
                Terima kasih atas kepercayaan Anda memilih TKIT AL-Qolam sebagai tempat pendidikan.";
                
                $result = $whatsapp->sendMessage($record->keluarga->ayah->no_hp, $message);
                
                // Log hasil pengiriman
                Log::info('Hasil pengiriman WhatsApp', [
                    'success' => $result,
                    'peserta' => $record->nama,
                    'nomor' => $record->keluarga->ayah->no_hp
                ]);
            } else {
                Log::warning('Tidak ada nomor WhatsApp untuk peserta', [
                    'peserta' => $record->nama
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error saat mengirim notifikasi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private static function handleError(\Exception $e): void
    {
        Log::error('Error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        Notification::make()
            ->title('Gagal')
            ->body('Terjadi kesalahan: ' . $e->getMessage())
            ->danger()
            ->send();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_peserta', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}

  

