<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'kode_pendaftaran_id',
        'nama',
        'email',
        'agama',
        'tempat_lahir',
        'tanggal_lahir',
        'keluarga_id',
        'id_informasi',
        'id_keterangan',
        'id_pendanaan',
        'id_survei',
        'jenis_kelamin',
        'nama_panggilan',
        'berat_badan',
        'tinggi_badan',
        'jumlah_saudara_kandung',
        'jumlah_saudara_tiri',
        'anak_ke',
        'mempunyai_alergi',
        'pindahan_dari',
        'tanggal_pindah',
        'tanggal_diterima',
        'tahun_ajaran_masuk',
        'status_peserta',
        'bahasa_sehari',
        'alamat',
        'asal_tk',
        'kelompok',
        'penyakit_berapalama',
        'penyakit_kapan',
        'penyakit_pantangan',
        'toilet_traning',
        'lainnya',
        'ttd_ortu',
        'latar_belakang',
        'harapan_keislaman',
        'harapan_keilmuan',
        'harapan_sosial',
        'berapa_lama_bersekolah',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_masuk');
    }
    
    public function kodePendaftaran(): BelongsTo
    {
        return $this->belongsTo(KodePendaftaran::class, 'kode_pendaftaran_id');
    }

    // Relasi ke Pendaftaran melalui KodePendaftaran
    public function pendaftaran()
    {
        return $this->hasOneThrough(
            Pendaftaran::class,
            KodePendaftaran::class,
            'id', // Foreign key on kode_pendafta
            'id', // Foreign key on kode_pendaftaran
            'kode_pendaftaran_id', // Local key on kode_pendaftaran
            'id' // Local key on pendaftaran
        );
    }


    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id');
    }

    public function saudara(): HasMany
    {
        return $this->hasMany(Saudara::class);
    }

    public function informasi(): BelongsTo
    {
        return $this->belongsTo(InformasiPeserta::class, 'id_informasi');
    }

    public function keterangan(): BelongsTo
    {
        return $this->belongsTo(KeteranganPeserta::class, 'id_keterangan');
    }

    public function pendanaan(): BelongsTo
    {
        return $this->belongsTo(PendanaanPeserta::class, 'id_pendanaan' );
    }

    public function survei(): BelongsTo
    {
        return $this->belongsTo(SurveiPeserta::class, 'id_survei');
    }

    public function kelasHasPeserta(): HasMany
    {
        return $this->hasMany(KelasHasPeserta::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($peserta) {
            // Hapus data keluarga dan relasinya
            if ($peserta->keluarga) {
                if ($peserta->keluarga->ayah) {
                    $peserta->keluarga->ayah->delete();
                }
                if ($peserta->keluarga->ibu) {
                    $peserta->keluarga->ibu->delete();
                }
                if ($peserta->keluarga->wali) {
                    $peserta->keluarga->wali->delete();
                }
                $peserta->keluarga->delete();
            }

            // Hapus data informasi
            if ($peserta->informasi) {
                $peserta->informasi->delete();
            }

            // Hapus data keterangan
            if ($peserta->keterangan) {
                $peserta->keterangan->delete();
            }

            // Hapus data pendanaan
            if ($peserta->pendanaan) {
                $peserta->pendanaan->delete();
            }

            // Hapus data survei
            if ($peserta->survei) {
                $peserta->survei->delete();
            }
        });

        static::saved(function ($peserta) {
            if (request()->has('Pendahuluan')) {
                $pendahuluanData = request()->input('Pendahuluan');
                $peserta->pendahuluan()->updateOrCreate(
                    ['peserta_id' => $peserta->id],
                    $pendahuluanData
                );
            }
        });
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }

}
