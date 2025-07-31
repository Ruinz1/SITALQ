<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaksi;
use App\Models\Pagu_anggaran;
use App\Models\Kas;
use App\Models\Peserta;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ObserverTest extends TestCase
{
    use RefreshDatabase;

    protected TahunAjaran $tahunAjaran;
    protected Peserta $peserta;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat data yang diperlukan untuk testing secara manual
        $this->tahunAjaran = TahunAjaran::create([
            'tahun_ajaran' => '2024/2025',
            'semester' => 'Ganjil',
            'status' => 'aktif'
        ]);
        
        $this->peserta = Peserta::create([
            'nama' => 'John Doe',
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'nis' => '12345',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-01-01',
            'alamat' => 'Jakarta',
            'agama' => 'Islam',
            'status' => 'aktif'
        ]);
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }

    public function test_transaksi_observer_creates_kas_entry_when_status_pembayaran_is_1()
    {
        // Buat transaksi dengan status pembayaran = 1
        $transaksi = Transaksi::create([
            'peserta_id' => $this->peserta->id,
            'tahun_masuk' => '2024',
            'total_bayar' => 1000000,
            'status_pembayaran' => 1,
            'kode_transaksi' => 'TRX001'
        ]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'transaksi_id' => $transaksi->id,
            'pagu_anggaran_id' => null,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'tipe' => 'masuk',
            'sumber' => 'Transaksi',
            'jumlah' => 1000000,
            'keterangan' => 'Transaksi Pembayaran - ' . $this->peserta->nama,
            'kategori' => 'Pendaftaran'
        ]);
    }

    public function test_transaksi_observer_creates_kas_entry_when_status_pembayaran_changes_to_1()
    {
        // Buat transaksi dengan status pembayaran = 0
        $transaksi = Transaksi::create([
            'peserta_id' => $this->peserta->id,
            'tahun_masuk' => '2024',
            'total_bayar' => 1000000,
            'status_pembayaran' => 0,
            'kode_transaksi' => 'TRX002'
        ]);

        // Verifikasi bahwa belum ada entri kas
        $this->assertDatabaseMissing('kas', [
            'transaksi_id' => $transaksi->id
        ]);

        // Update status pembayaran menjadi 1
        $transaksi->update(['status_pembayaran' => 1]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'transaksi_id' => $transaksi->id,
            'pagu_anggaran_id' => null,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'tipe' => 'masuk',
            'sumber' => 'Transaksi',
            'jumlah' => 1000000,
            'keterangan' => 'Transaksi Pembayaran - ' . $this->peserta->nama,
            'kategori' => 'Pendaftaran'
        ]);
    }

    public function test_pagu_anggaran_observer_creates_kas_entry_when_status_is_approved()
    {
        // Buat pagu anggaran dengan status approved
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Laptop',
            'harga_satuan' => 5000000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 5000000,
            'status' => 'approved',
            'keterangan' => 'Pembelian laptop untuk lab komputer',
            'disetujui_oleh' => $this->user->id
        ]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'transaksi_id' => null,
            'pagu_anggaran_id' => $paguAnggaran->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'tipe' => 'masuk',
            'sumber' => 'Anggaran/Pengadaan',
            'jumlah' => 5000000,
            'keterangan' => 'Pembelian laptop untuk lab komputer',
            'kategori' => 'Peralatan',
            'user_id' => $this->user->id
        ]);
    }

    public function test_pagu_anggaran_observer_creates_kas_entry_when_status_changes_to_approved()
    {
        // Buat pagu anggaran dengan status pending
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Printer',
            'harga_satuan' => 2000000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 2000000,
            'status' => 'pending',
            'keterangan' => 'Pembelian printer untuk kantor',
            'disetujui_oleh' => null
        ]);

        // Verifikasi bahwa belum ada entri kas
        $this->assertDatabaseMissing('kas', [
            'pagu_anggaran_id' => $paguAnggaran->id
        ]);

        // Update status menjadi approved
        $paguAnggaran->update([
            'status' => 'approved',
            'disetujui_oleh' => $this->user->id
        ]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'transaksi_id' => null,
            'pagu_anggaran_id' => $paguAnggaran->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'tipe' => 'masuk',
            'sumber' => 'Anggaran/Pengadaan',
            'jumlah' => 2000000,
            'keterangan' => 'Pembelian printer untuk kantor',
            'kategori' => 'Peralatan',
            'user_id' => $this->user->id
        ]);
    }

    public function test_observer_does_not_create_duplicate_kas_entries()
    {
        // Buat transaksi dengan status pembayaran = 1
        $transaksi = Transaksi::create([
            'peserta_id' => $this->peserta->id,
            'tahun_masuk' => '2024',
            'total_bayar' => 1000000,
            'status_pembayaran' => 1,
            'kode_transaksi' => 'TRX003'
        ]);

        // Hitung jumlah entri kas untuk transaksi ini
        $kasCount = Kas::where('transaksi_id', $transaksi->id)->count();
        $this->assertEquals(1, $kasCount);

        // Update transaksi tanpa mengubah status pembayaran
        $transaksi->update(['total_bayar' => 1500000]);

        // Verifikasi bahwa tidak ada entri kas tambahan
        $kasCountAfterUpdate = Kas::where('transaksi_id', $transaksi->id)->count();
        $this->assertEquals(1, $kasCountAfterUpdate);
    }

    public function test_transaksi_observer_deletes_kas_entry_when_transaksi_is_deleted()
    {
        // Buat transaksi dengan status pembayaran = 1
        $transaksi = Transaksi::create([
            'peserta_id' => $this->peserta->id,
            'tahun_masuk' => '2024',
            'total_bayar' => 1000000,
            'status_pembayaran' => 1,
            'kode_transaksi' => 'TRX004'
        ]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'transaksi_id' => $transaksi->id
        ]);

        // Hapus transaksi
        $transaksi->delete();

        // Verifikasi bahwa entri kas terhapus
        $this->assertDatabaseMissing('kas', [
            'transaksi_id' => $transaksi->id
        ]);
    }

    public function test_pagu_anggaran_observer_deletes_kas_entry_when_pagu_anggaran_is_deleted()
    {
        // Buat pagu anggaran dengan status approved
        $paguAnggaran = Pagu_anggaran::create([
            'user_id' => $this->user->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'kategori' => 'Peralatan',
            'nama_item' => 'Monitor',
            'harga_satuan' => 1500000,
            'satuan' => 'unit',
            'jumlah' => 1,
            'total_harga' => 1500000,
            'status' => 'approved',
            'keterangan' => 'Pembelian monitor untuk lab',
            'disetujui_oleh' => $this->user->id
        ]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'pagu_anggaran_id' => $paguAnggaran->id
        ]);

        // Hapus pagu anggaran
        $paguAnggaran->delete();

        // Verifikasi bahwa entri kas terhapus
        $this->assertDatabaseMissing('kas', [
            'pagu_anggaran_id' => $paguAnggaran->id
        ]);
    }

    public function test_observer_handles_soft_delete_correctly()
    {
        // Buat transaksi dengan status pembayaran = 1
        $transaksi = Transaksi::create([
            'peserta_id' => $this->peserta->id,
            'tahun_masuk' => '2024',
            'total_bayar' => 1000000,
            'status_pembayaran' => 1,
            'kode_transaksi' => 'TRX005'
        ]);

        // Verifikasi bahwa entri kas dibuat
        $this->assertDatabaseHas('kas', [
            'transaksi_id' => $transaksi->id
        ]);

        // Soft delete transaksi (karena menggunakan SoftDeletes)
        $transaksi->delete();

        // Verifikasi bahwa entri kas terhapus (soft delete)
        $this->assertDatabaseMissing('kas', [
            'transaksi_id' => $transaksi->id
        ]);

        // Verifikasi bahwa transaksi masih ada di database (soft delete)
        $this->assertSoftDeleted('transaksis', [
            'id' => $transaksi->id
        ]);
    }
} 