<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saudara extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'saudara';

    protected $fillable = [
        'peserta_id',
        'nama',
        'hubungan',
        'umur'
    ];

    /**
     * Relasi ke model Peserta
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
} 