<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SurveiPeserta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'larangan_menunggu',
        'larangan_perhiasan',
        'berpakaian_islami',
        'menghadiri_pertemuan_wali',
        'kontrol_pengembangan',
        'larangan_merokok',
        'tidak_bekerjasama',
        'penjadwalan',
    ];

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }
}
