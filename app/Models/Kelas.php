<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_kelas',
        'guru_id',
        'kapasitas',
        'status',
        'tahun_ajaran_id'
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelasHasPeserta(): HasMany
    {
        return $this->hasMany(KelasHasPeserta::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function jadwal()
    {
        return $this->hasMany(jadwal::class, 'kelas_id');
    }
}
