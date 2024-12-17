<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KeteranganPeserta extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'keterangan_membaca',
        'keterangan_membaca_hijaiyah',
        'keterangan_menulis',
        'keterangan_menghitung',
        'keterangan_menggambar',
        'keterangan_berwudhu',
        'keterangan_tata_cara_shalat',
        'keterangan_hafalan_juz_ama',
        'keterangan_hafalan_murottal',
        'keterangan_hafalan_doa',
        'judulbuku_berlatihmembaca_latin',
        'judulbuku_berlatihmembaca_hijaiyah',
        'jilid_hijaiyah',
        'keterangan_angka',
        'keterangan_hafal_surat',
        'hobi',
        'keterangan_kisah_islami',
        'keterangan_majalah',
    ];

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }

}
