<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftaran extends Model
{
    use HasFactory, SoftDeletes;
    //
    protected $fillable = [
        'kode_pendaftaran',
        'status',
        'tahun_ajaran_id',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kodePendaftaran(): HasMany
    {
        return $this->hasMany(KodePendaftaran::class);
    }

   
}
