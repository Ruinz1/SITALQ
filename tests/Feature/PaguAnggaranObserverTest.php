<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pagu_anggaran;
use App\Models\Kas;
use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PaguAnggaranObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $tahunAjaran;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat user dan tahun ajaran untuk testing
        $this->user = User::factory()->create();
        $this->tahunAjaran = TahunAjaran::factory()->create();
    }

    /** @test */
    public function pagu_anggaran_ditolak_jika_saldo_kas_tidak_mencukupi()
    {
        // Buat kas dengan saldo terbatas
        Kas::create([
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'user_id' => $this->user->id,
            'tipe' => 'masuk',
            'sumber' => 'sumbangan',
            'jumlah' => 5000000, // Saldo 5 juta
            'kategori' => 'Sumbangan',
            'keterangan' => 'Sumbangan awal',
            'tanggal' => now(),
        ]);

        // Buat pagu anggaran dengan total harga melebihi saldo
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Laptop',
            'harga_satuan' => 8000000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 8000000, // 8 juta, melebihi saldo
            'status' => 'pending',
            'tanggal_pengajuan' => now(),
            'keterangan' => 'Pengadaan laptop untuk guru',
        ]);

        // Coba ubah status menjadi approved
        $paguAnggaran->status = 'approved';
        $paguAnggaran->disetujui_oleh = $this->user->id;
        $paguAnggaran->save();

        // Refresh model dari database
        $paguAnggaran->refresh();

        // Assert bahwa status berubah menjadi rejected
        $this->assertEquals('rejected', $paguAnggaran->status);
        
        // Assert bahwa alasan penolakan berisi informasi saldo
        $this->assertStringContainsString('Saldo kas tidak mencukupi', $paguAnggaran->alasan_penolakan);
        $this->assertStringContainsString('Rp 5.000.000', $paguAnggaran->alasan_penolakan);
        $this->assertStringContainsString('Rp 8.000.000', $paguAnggaran->alasan_penolakan);
    }

    /** @test */
    public function pagu_anggaran_disetujui_jika_saldo_kas_mencukupi()
    {
        // Buat kas dengan saldo yang mencukupi
        Kas::create([
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'user_id' => $this->user->id,
            'tipe' => 'masuk',
            'sumber' => 'sumbangan',
            'jumlah' => 10000000, // Saldo 10 juta
            'kategori' => 'Sumbangan',
            'keterangan' => 'Sumbangan awal',
            'tanggal' => now(),
        ]);

        // Buat pagu anggaran dengan total harga yang mencukupi
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Printer',
            'harga_satuan' => 3000000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 3000000, // 3 juta, cukup dengan saldo
            'status' => 'pending',
            'tanggal_pengajuan' => now(),
            'keterangan' => 'Pengadaan printer',
        ]);

        // Ubah status menjadi approved
        $paguAnggaran->status = 'approved';
        $paguAnggaran->disetujui_oleh = $this->user->id;
        $paguAnggaran->save();

        // Refresh model dari database
        $paguAnggaran->refresh();

        // Assert bahwa status berubah menjadi approved
        $this->assertEquals('approved', $paguAnggaran->status);
        
        // Assert bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'pagu_anggaran_id' => $paguAnggaran->id,
            'tipe' => 'keluar',
            'jumlah' => 3000000,
            'sumber' => 'Anggaran/Pengadaan',
        ]);
    }

    /** @test */
    public function pagu_anggaran_baru_ditolak_jika_saldo_tidak_mencukupi()
    {
        // Buat kas dengan saldo terbatas
        Kas::create([
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'user_id' => $this->user->id,
            'tipe' => 'masuk',
            'sumber' => 'sumbangan',
            'jumlah' => 2000000, // Saldo 2 juta
            'kategori' => 'Sumbangan',
            'keterangan' => 'Sumbangan awal',
            'tanggal' => now(),
        ]);

        // Coba buat pagu anggaran langsung dengan status approved
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Proyektor',
            'harga_satuan' => 5000000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 5000000, // 5 juta, melebihi saldo
            'status' => 'approved', // Langsung approved
            'tanggal_pengajuan' => now(),
            'disetujui_oleh' => $this->user->id,
            'keterangan' => 'Pengadaan proyektor',
        ]);

        // Refresh model dari database
        $paguAnggaran->refresh();

        // Assert bahwa status berubah menjadi rejected
        $this->assertEquals('rejected', $paguAnggaran->status);
        
        // Assert bahwa alasan penolakan berisi informasi saldo
        $this->assertStringContainsString('Saldo kas tidak mencukupi', $paguAnggaran->alasan_penolakan);
    }

    /** @test */
    public function saldo_kas_dihitung_dengan_benar()
    {
        // Buat beberapa transaksi kas
        Kas::create([
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'user_id' => $this->user->id,
            'tipe' => 'masuk',
            'sumber' => 'sumbangan',
            'jumlah' => 10000000, // Pemasukan 10 juta
            'kategori' => 'Sumbangan',
            'keterangan' => 'Sumbangan awal',
            'tanggal' => now(),
        ]);

        Kas::create([
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'user_id' => $this->user->id,
            'tipe' => 'keluar',
            'sumber' => 'pengeluaran',
            'jumlah' => 3000000, // Pengeluaran 3 juta
            'kategori' => 'Operasional',
            'keterangan' => 'Pengeluaran operasional',
            'tanggal' => now(),
        ]);

        // Buat pagu anggaran dengan total harga yang mencukupi (saldo = 7 juta)
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Komputer',
            'harga_satuan' => 6000000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 6000000, // 6 juta, cukup dengan saldo 7 juta
            'status' => 'pending',
            'tanggal_pengajuan' => now(),
            'keterangan' => 'Pengadaan komputer',
        ]);

        // Ubah status menjadi approved
        $paguAnggaran->status = 'approved';
        $paguAnggaran->disetujui_oleh = $this->user->id;
        $paguAnggaran->save();

        // Refresh model dari database
        $paguAnggaran->refresh();

        // Assert bahwa status berubah menjadi approved
        $this->assertEquals('approved', $paguAnggaran->status);
        
        // Assert bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'pagu_anggaran_id' => $paguAnggaran->id,
            'tipe' => 'keluar',
            'jumlah' => 6000000,
        ]);
    }
} 