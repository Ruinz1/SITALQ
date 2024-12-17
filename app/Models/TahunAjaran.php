<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjaran extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'status',
    ];


    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class);
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    public static function rules($id = null)
    {
        return [
            'nama' => [
                'required',
                'string',
                'unique:tahun_ajarans,nama,' . $id,
            ],
            // ... aturan validasi lainnya jika ada
        ];
    }
}
