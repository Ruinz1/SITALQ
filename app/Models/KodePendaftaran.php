<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class KodePendaftaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'pendaftaran_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope('showActive', function (Builder $builder) {
            $builder->where(function ($query) {
                $query->whereHas('pendaftaran', function ($q) {
                    $q->where('status', '1');
                })->orWhereHas('peserta');
            });
        });
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }
    
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'kode_pendaftaran_id');
    }

    // Scope untuk mengecek kode yang belum digunakan
    public function scopeBelumDigunakan($query)
    {
        return $query->where('status', 1); // Asumsikan 1 = belum digunakan
    }

}
