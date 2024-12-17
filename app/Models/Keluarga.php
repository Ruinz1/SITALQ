<?php

namespace App\Models;

use App\Models\Ibu;
use App\Models\Ayah;
use App\Models\Wali;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keluarga extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ayah_id',
        'ibu_id',
        'wali_id',
    ];

    public function ayah(): BelongsTo
    {
        return $this->belongsTo(Ayah::class, 'ayah_id');
    }

    public function ibu(): BelongsTo
    {
        return $this->belongsTo(Ibu::class, 'ibu_id');
    }

    public function wali(): BelongsTo
    {
        return $this->belongsTo(Wali::class, 'wali_id');
    }

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }
}
