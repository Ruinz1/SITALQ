<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pagu_anggaran extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tahun_ajaran_id',
        'kategori',
        'nama_item',
        'harga_satuan',
        'satuan',
        'jumlah',
        'total_harga',
        'status',
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'disetujui_oleh',
        'alasan_penolakan',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    protected static function booted()
    {
        static::observe(\App\Observers\PaguAnggaranObserver::class);
    }
}
