<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use auth;
use App\Models\Ibu;
use App\Models\Ayah;
use App\Models\Wali;
use Filament\Actions;
use App\Models\Peserta;
use App\Models\Keluarga;
use App\Models\SurveiPeserta;
use App\Models\InformasiPeserta;
use App\Models\PendanaanPeserta;
use App\Models\KeteranganPeserta;
use App\Models\Pendahuluan;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PesertaResource;

class CreatePeserta extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = PesertaResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
{
    DB::beginTransaction();
    
    try {
        // Tambahkan logging untuk debug
        \Log::info('Data yang diterima:', [
            'semua_data' => $data,
            'data_pendahuluan' => $data['pendahuluan'] ?? 'Tidak ada data pendahuluan'
        ]);

        // Buat record keluarga
        $ayah = Ayah::create([
            'nama' => $data['ayah']['nama'],
            'tempat_lahir' => $data['ayah']['tempat_lahir'],
            'tanggal_lahir' => $data['ayah']['tanggal_lahir'],
            'agama' => $data['ayah']['agama'],
            'pendidikan_terakhir' => $data['ayah']['pendidikan_terakhir'],
            'pekerjaan' => $data['ayah']['pekerjaan'],
            'alamat' => $data['ayah']['alamat'],
            'alamat_kantor' => $data['ayah']['alamat_kantor'],
            'no_hp' => $data['ayah']['no_hp'],
            'sosmed' => $data['ayah']['sosmed'],
        ]);

        $ibu = Ibu::create([
            'nama' => $data['ibu']['nama'],
            'tempat_lahir' => $data['ibu']['tempat_lahir'],
            'tanggal_lahir' => $data['ibu']['tanggal_lahir'],
            'agama' => $data['ibu']['agama'],
            'pendidikan_terakhir' => $data['ibu']['pendidikan_terakhir'],
            'pekerjaan' => $data['ibu']['pekerjaan'],
            'alamat' => $data['ibu']['alamat'],
            'alamat_kantor' => $data['ibu']['alamat_kantor'],
            'no_hp' => $data['ibu']['no_hp'],
            'sosmed' => $data['ibu']['sosmed'],
        ]);

        $wali = Wali::create([
            'nama' => $data['wali']['nama'] ?? null,
            'tempat_lahir' => $data['wali']['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['wali']['tanggal_lahir'] ?? null,
            'agama' => $data['wali']['agama'] ?? null,
            'pendidikan_terakhir' => $data['wali']['pendidikan_terakhir'] ?? null,
            'pekerjaan' => $data['wali']['pekerjaan'] ?? null,
            'alamat' => $data['wali']['alamat'] ?? null,
            'alamat_kantor' => $data['wali']['alamat_kantor'] ?? null,
            'no_hp' => $data['wali']['no_hp'] ?? null,
            'sosmed' => $data['wali']['sosmed'] ?? null,
        ]);

        $keluarga = Keluarga::create([
            'ayah_id' => $ayah->id,
            'ibu_id' => $ibu->id,
            'wali_id' => $wali->id,
        ]);

        // Buat record data informasi peserta
        $informasiPeserta = InformasiPeserta::create([
            'tinggal_bersama' => $data['informasi']['tinggal_bersama'],
            'jumlah_penghuni_dewasa' => $data['informasi']['jumlah_penghuni_dewasa'],
            'jumlah_penghuni_anak' => $data['informasi']['jumlah_penghuni_anak'],
            'halaman_bermain_dirumah' => $data['informasi']['halaman_bermain_dirumah'],
            'pergaulan_dengan_sebaya' => $data['informasi']['pergaulan_dengan_sebaya'],
            'kepatuhan_anak' => $data['informasi']['kepatuhan_anak'],
            'selera_makan' => $data['informasi']['selera_makan'],
            'hubungan_dengan_ayah' => $data['informasi']['hubungan_dengan_ayah'],
            'hubungan_dengan_ibu' => $data['informasi']['hubungan_dengan_ibu'],
            'kemampuan_buang_air' => $data['informasi']['kemampuan_buang_air'],
            'kebiasaan_ngompol' => $data['informasi']['kebiasaan_ngompol'],
            'kebiasan_tidur_malam' => $data['informasi']['kebiasan_tidur_malam'],
            'kebiasan_tidur_siang' => $data['informasi']['kebiasan_tidur_siang'],
            'kebiasan_bangun_pagi' => $data['informasi']['kebiasan_bangun_pagi'],
            'hal_penting_waktu_tidur' => $data['informasi']['hal_penting_waktu_tidur'],
            'kebiasan_bangun_siang' => $data['informasi']['kebiasan_bangun_siang'],
            'hal_mengenai_tingkah_anak' => $data['informasi']['hal_mengenai_tingkah_anak'],
            'mudah_bergaul' => $data['informasi']['mudah_bergaul'],
            'sifat_baik' => $data['informasi']['sifat_baik'],
            'sifat_buruk' => $data['informasi']['sifat_buruk'],
            'pembantu_rumah_tangga' => $data['informasi']['pembantu_rumah_tangga'],
            'peralatan_elektronik' => $data['informasi']['peralatan_elektronik'],
        ]);

        $keteranganPeserta = KeteranganPeserta::create([
            'keterangan_membaca' => $data['keterangan']['keterangan_membaca'],
            'keterangan_membaca_hijaiyah' => $data['keterangan']['keterangan_membaca_hijaiyah'],
            'keterangan_menulis' => $data['keterangan']['keterangan_menulis'],
            'keterangan_menghitung' => $data['keterangan']['keterangan_menghitung'],
            'keterangan_menggambar' => $data['keterangan']['keterangan_menggambar'],
            'keterangan_berwudhu' => $data['keterangan']['keterangan_berwudhu'],
            'keterangan_tata_cara_shalat' => $data['keterangan']['keterangan_tata_cara_shalat'],
            'keterangan_hafalan_juz_ama' => $data['keterangan']['keterangan_hafalan_juz_ama'],
            'keterangan_hafalan_murottal' => $data['keterangan']['keterangan_hafalan_murottal'],
            'keterangan_hafalan_doa' => $data['keterangan']['keterangan_hafalan_doa'],
            'judulbuku_berlatihmembaca_latin' => $data['keterangan']['judulbuku_berlatihmembaca_latin'],
            'judulbuku_berlatihmembaca_hijaiyah' => $data['keterangan']['judulbuku_berlatihmembaca_hijaiyah'],
            'jilid_hijaiyah' => $data['keterangan']['jilid_hijaiyah'],
            'keterangan_angka' => $data['keterangan']['keterangan_angka'],
            'keterangan_hafal_surat' => $data['keterangan']['keterangan_hafal_surat'],
            'hobi' => $data['keterangan']['hobi'],
            'keterangan_kisah_islami' => $data['keterangan']['keterangan_kisah_islami'],
            'keterangan_majalah' => $data['keterangan']['keterangan_majalah'],
        ]);

        $pendanaan = PendanaanPeserta::create([
            'pemasukan_perbulan_orang_tua' => $data['pendanaan']['pemasukan_perbulan_orang_tua'],
            'keterangan_kenaikan_pendapatan' => $data['pendanaan']['keterangan_kenaikan_pendapatan'],
            'keterangan_infaq' => $data['pendanaan']['keterangan_infaq'],
        ]);

        $survei = SurveiPeserta::create([
            'larangan_menunggu' => $data['survei']['larangan_menunggu'],
            'larangan_perhiasan' => $data['survei']['larangan_perhiasan'],
            'berpakaian_islami' => $data['survei']['berpakaian_islami'],
            'menghadiri_pertemuan_wali' => $data['survei']['menghadiri_pertemuan_wali'],
            'kontrol_pengembangan' => $data['survei']['kontrol_pengembangan'],
            'larangan_merokok' => $data['survei']['larangan_merokok'],
            'tidak_bekerjasama' => $data['survei']['tidak_bekerjasama'],
            'penjadwalan' => $data['survei']['penjadwalan'],
        ]);

        // Ambil tahun ajaran aktif melalui kode pendaftaran
        $tahunAjaranAktif = \App\Models\KodePendaftaran::with(['pendaftaran.tahunAjaran'])
            ->whereHas('pendaftaran.tahunAjaran', function ($query) {
                $query->where('status', '1');
            })
            ->find($data['kode_pendaftaran_id'])
            ->pendaftaran
            ->tahunAjaran
            ->first();

        // Buat record peserta
        $peserta = Peserta::create([
            'kode_pendaftaran_id' => $data['kode_pendaftaran_id'],
            'keluarga_id' => $keluarga->id,
            'id_informasi' => $informasiPeserta->id,
            'id_keterangan' => $keteranganPeserta->id,
            'id_pendanaan' => $pendanaan->id,
            'id_survei' => $survei->id,
            'nama' => $data['nama'],
            'email' => $data['email'],
            'agama' => $data['agama'],
            'alamat' => $data['alamat'],
            'bahasa_sehari' => $data['bahasa_sehari'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'nama_panggilan' => $data['nama_panggilan'],
            'berat_badan' => $data['berat_badan'],
            'tinggi_badan' => $data['tinggi_badan'],
            'jumlah_saudara_kandung' => $data['jumlah_saudara_kandung'],
            'jumlah_saudara_tiri' => $data['jumlah_saudara_tiri'],
            'anak_ke' => $data['anak_ke'],
            'mempunyai_alergi' => $data['mempunyai_alergi'],
            'tanggal_diterima' => null,
            'tahun_ajaran_masuk' => $tahunAjaranAktif ? $tahunAjaranAktif->nama : null,
            'status_peserta' => $data['status_peserta'],
            'is_pindahan' => $data['is_pindahan'] ?? true,
            'asal_tk' => $data['asal_tk'] ?? null,
            'tanggal_pindah' => $data['tanggal_pindah'] ?? null,
            'kelompok' => $data['kelompok'] ?? null,
            'penyakit_berapalama' => $data['penyakit_berapalama'] ?? null,
            'penyakit_kapan' => $data['penyakit_kapan'] ?? null,
            'penyakit_pantangan' => $data['penyakit_pantangan'] ?? null,
            'toilet_traning' => $data['toilet_traning'] ?? null,
            'lainnya' => $data['lainnya'] ?? null,
            'ttd_ortu' => $data['ttd_ortu'],
            'latar_belakang' => $data['latar_belakang'],
            'harapan_keislaman' => $data['harapan_keislaman'] ,
            'harapan_keilmuan' => $data['harapan_keilmuan'],
            'harapan_sosial' => $data['harapan_sosial'] ,
            'berapa_lama_bersekolah' => $data['berapa_lama_bersekolah'],
        ]);

            // Tambahkan logging untuk debug peserta
            \Log::info('Peserta created:', ['peserta_id' => $peserta->id]);

            // Simpan data pendahuluan dengan logging
            

            DB::commit();
            return $peserta;

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saat menyimpan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }


} 