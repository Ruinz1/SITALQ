<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tahun_ajaran_id',
        'transaksi_id', // id dari transaksi (opsional)
        'pagu_anggaran_id', // id dari pagu_anggaran (opsional)
        'user_id',
        'tipe',         // 'masuk' atau 'keluar'
        'sumber',       // 'transaksi', 'sumbangan', 'pagu_anggaran'
        'jumlah',  
        'kategori',
        'keterangan',
        'tanggal'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date'
    ];

    // Relasi ke Tahun Ajaran
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Transaksi
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke Pagu Anggaran
    public function paguAnggaran(): BelongsTo
    {
        return $this->belongsTo(Pagu_anggaran::class);
    }

    // Scope untuk filter berdasarkan tahun ajaran
    public function scopeTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    // Scope untuk filter berdasarkan sumber
    public function scopeSumber($query, $sumber)
    {
        return $query->where('sumber', $sumber);
    }

    // Method untuk mendapatkan jumlah pemasukan
    public function getjumlahPemasukan()
    {
        return $this->where('tipe', 'masuk')->sum('jumlah');
    }

    // Method untuk mendapatkan jumlah pengeluaran
    public function getjumlahPengeluaran()
    {
        return $this->where('tipe', 'keluar')->sum('jumlah');
    }

    // Method untuk mendapatkan saldo
    public function getSaldo()
    {
        return $this->getjumlahPemasukan() - $this->getjumlahPengeluaran();
    }

    // Method untuk mendapatkan laporan kas
    public function getLaporanKas($startDate = null, $endDate = null)
    {
        $query = $this->newQuery();

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        return [
            'jumlah_pemasukan' => $query->where('tipe', 'masuk')->sum('jumlah'),
            'jumlah_pengeluaran' => $query->where('tipe', 'keluar')->sum('jumlah'),
            'saldo' => $query->where('tipe', 'masuk')->sum('jumlah') - $query->where('tipe', 'keluar')->sum('jumlah'),
            'transactions' => $query->with(['user', 'tahunAjaran'])->get()
        ];
    }

    // Method untuk menambah pemasukan
    public function tambahPemasukan($jumlah, $keterangan, $sumber = 'sumbangan', $transaksiId = null)
    {
        return $this->create([
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
            'tipe' => 'masuk',
            'sumber' => $sumber,
            'transaksi_id' => $transaksiId,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'tanggal' => now()
        ]);
    }

    // Method untuk menambah pengeluaran
    public function tambahPengeluaran($jumlah, $keterangan, $paguAnggaranId = null)
    {
        return $this->create([
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
            'tipe' => 'keluar',
            'sumber' => 'pagu_anggaran',
            'pagu_anggaran_id' => $paguAnggaranId,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'tanggal' => now()
        ]);
    }

    // Method untuk mengurangi kas otomatis dari pagu anggaran
    public static function kurangiKasDariPaguAnggaran($paguAnggaran)
    {
        // Cek apakah saldo kas mencukupi
        $saldoKas = self::where('tahun_ajaran_id', $paguAnggaran->tahun_ajaran_id)
            ->getSaldo();

        if ($saldoKas < $paguAnggaran->jumlah) {
            throw new \Exception('Saldo kas tidak mencukupi untuk melakukan pengeluaran ini.');
        }

        // Buat record pengeluaran kas
        return self::create([
            'tahun_ajaran_id' => $paguAnggaran->tahun_ajaran_id,
            'tipe' => 'keluar',
            'sumber' => 'pagu_anggaran',
            'pagu_anggaran_id' => $paguAnggaran->id,
            'jumlah' => $paguAnggaran->jumlah,
            'kategori' => $paguAnggaran->kategori,
            'keterangan' => 'Pengeluaran untuk ' . $paguAnggaran->keterangan,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'tanggal' => now()
        ]);
    }

    // Method untuk mendapatkan saldo kas berdasarkan tahun ajaran
    public static function getSaldoByTahunAjaran($tahunAjaranId)
    {
        $query = self::where('tahun_ajaran_id', $tahunAjaranId);
        return $query->getSaldo();
    }
}