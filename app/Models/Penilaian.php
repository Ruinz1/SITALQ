<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;   
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'kelas_has_peserta_id',
        'mapel_id',
        'nilai',
        'keterangan',
        'semester',
        'status',
    ];

    protected $casts = [
        'keterangan' => 'array'
    ];

    public function kelasHasPeserta()
    {
        return $this->belongsTo(KelasHasPeserta::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
