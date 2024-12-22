<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiPeserta extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'tinggal_bersama',
        'jumlah_penghuni_dewasa',
        'jumlah_penghuni_anak',
        'halaman_bermain_dirumah',
        'pergaulan_dengan_sebaya',
        'selera_makan',
        'hubungan_dengan_ayah',
        'hubungan_dengan_ibu',
        'kemampuan_buang_air',
        'kebiasan_tidur_malam',
        'kebiasan_tidur_siang',
        'kebiasan_bangun_pagi',
        'kebiasaan_ngompol',
        'hal_penting_waktu_tidur',
        'kepatuhan_anak',
        'hal_penting_waktu_tidur',
        'kebiasan_bangun_siang',
        'hal_mengenai_tingkah_anak',
        'mudah_bergaul',
        'sifat_baik',
        'sifat_buruk',
        'pembantu_rumah_tangga',
        'peralatan_elektronik',
    ];

    protected $casts = [
        'peralatan_elektronik' => 'array'
    ];

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }
}
