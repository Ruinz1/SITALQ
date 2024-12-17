<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ibu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'pendidikan_terakhir',
        'pekerjaan',
        'alamat',
        'alamat_kantor',
        'no_hp',
        'sosmed',
    ];

    public function keluarga(): HasMany
    {
        return $this->hasMany(Keluarga::class);
    }
}
