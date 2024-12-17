<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendanaanPeserta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pemasukan_perbulan_orang_tua',
        'keterangan_kenaikan_pendapatan',
        'infaq',
        'keterangan_infaq',
    ];

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }
}
