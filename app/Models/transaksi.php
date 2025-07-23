<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'peserta_id',
        'tahun_masuk',
        'total_bayar',
        'status_pembayaran',
        'kode_transaksi',
        'snap_token',
        'midtrans_transaction_id',
        'midtrans_payment_type',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    protected static function booted()
    {
        static::observe(\App\Observers\TransaksiObserver::class);
    }

   
}
