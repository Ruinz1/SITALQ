<?php

namespace App\Http\Controllers;

use App\Models\Ibu;
use App\Models\Ayah;
use App\Models\Wali;
use App\Models\Peserta;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use App\Models\SurveiPeserta;
use App\Models\KodePendaftaran;
use App\Models\InformasiPeserta;
use App\Models\PendanaanPeserta;
use App\Models\KeteranganPeserta;
use App\Models\Saudara;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCreatedMail;

class PesertaController extends Controller
{
    public function create()
    {
        $kode_pendaftaran = KodePendaftaran::with(['pendaftaran' => function($query) {
                $query->where('status', 1);
            }])
            ->whereHas('pendaftaran', function ($query) {
                $query->where('status', 1);
            })
            ->belumDigunakan()
            ->get();
        
        // Hanya ambil yang benar-benar memiliki pendaftaran aktif
        $kode_pendaftaran = $kode_pendaftaran->filter(function ($item) {
            return $item->pendaftaran && $item->pendaftaran->status == 1;
        });
        
        return view('peserta.create', compact('kode_pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanda_tangan' => 'required|image|mimes:png|max:2048', // Validasi file
            // ... other validations ...
        ]);

        $fileName = null; // Inisialisasi nama file
        if ($request->hasFile('tanda_tangan')) {
            $file = $request->file('tanda_tangan');
            $fileName = $file->getClientOriginalName(); // Ambil nama asli file
            $file->storeAs($fileName, 'public'); // Simpan file dengan nama asli
        }

        try {
            // Log request data
            Log::info('Form submission data:', $request->all());


            $tahunajaran = KodePendaftaran::with(['pendaftaran.tahunAjaran'])
            ->where('kode', $request->kode_pendaftaran_id)
            ->whereHas('pendaftaran', function ($query) {
                $query->whereHas('tahunAjaran', function ($query) {
                    $query->where('status', 1);
                });
            })
            ->first();

            $tahunAjaranNama = $tahunajaran && $tahunajaran->pendaftaran && $tahunajaran->pendaftaran->tahunAjaran
            ? $tahunajaran->pendaftaran->tahunAjaran->nama
            : null;

        // Sekarang Anda bisa menggunakan $tahunAjaranNama sesuai kebutuhan
            // // Validasi data
            // $validated = $request->validate([
            //     'kode_pendaftaran_id' => 'required|exists:kode_pendaftarans,kode',
            //     'nama_peserta' => 'required',
            //     // tambahkan validasi lainnya
            // ]);

            // // Cari kode pendaftaran berdasarkan nama
            // $kodePendaftaran = KodePendaftaran::where('kode', $request->kode_pendaftaran_id)
            //     ->whereHas('pendaftaran', function ($query) {
            //         $query->where('status', 1);
            //     })
            //     ->first();

            // if (!$kodePendaftaran) {
            //     throw new \Exception('Kode pendaftaran tidak valid atau tidak aktif');
            // }

            // 1. Buat data Ayah
            $ayah = Ayah::create([
                'nama' => $request->nama_ayah,
                'tempat_lahir' => $request->tempat_lahir_ayah,
                'tanggal_lahir' => $request->tanggal_lahir_ayah,
                'pekerjaan' => $request->pekerjaan_ayah,
                'pendidikan_terakhir' => $request->pendidikan_terakhir_ayah,
                'alamat' => $request->alamat_ayah,
                'alamat_kantor' => $request->alamat_kantor_ayah,
                'no_hp' => $request->no_hp_ayah,
                'agama' => $request->agama_ayah,
                'sosmed' => $request->sosmed_ayah,
            ]);

            // 2. Buat data Ibu
            $ibu = Ibu::create([
                'nama' => $request->nama_ibu,
                'tempat_lahir' => $request->tempat_lahir_ibu,
                'tanggal_lahir' => $request->tanggal_lahir_ibu,
                'pekerjaan' => $request->pekerjaan_ibu,
                'pendidikan_terakhir' => $request->pendidikan_terakhir_ibu,
                'alamat' => $request->alamat_ibu,
                'alamat_kantor' => $request->alamat_kantor_ibu,
                'agama' => $request->agama_ibu,
                'no_hp' => $request->no_hp_ibu,
                'sosmed' => $request->sosmed_ibu,
            ]);

            // 3. Buat data Wali (jika ada)
            $wali = null;
            if ($request->has_wali === 'ya') {
                $wali = Wali::create([
                    'nama' => $request->nama_wali,
                    'tempat_lahir' => $request->tempat_lahir_wali,
                    'tanggal_lahir' => $request->tanggal_lahir_wali,
                    'pekerjaan' => $request->pekerjaan_wali,
                    'pendidikan_terakhir' => $request->pendidikan_terakhir_wali,
                    'alamat' => $request->alamat_wali,
                    'alamat_kantor' => $request->alamat_kantor_wali,
                    'agama' => $request->agama_wali,
                    'no_hp' => $request->no_hp_wali,
                    'sosmed' => $request->sosmed_wali,
                ]);
            }

            // 4. Buat data Keluarga
            $keluarga = Keluarga::create([
                'ayah_id' => $ayah->id,
                'ibu_id' => $ibu->id,
                'wali_id' => $wali ? $wali->id : null,
            ]);

            //add to saudara
            

            // 5. Buat data Informasi Peserta
            $informasi = InformasiPeserta::create([
                'tinggal_bersama' => $request->tinggal_bersama,
                'jumlah_penghuni_dewasa' => $request->jumlah_penghuni_dewasa,
                'jumlah_penghuni_anak' => $request->jumlah_penghuni_anak,
                'halaman_bermain_dirumah' => $request->halaman_bermain,
                'pergaulan_dengan_sebaya' => $request->pergaulan_dengan_sebaya,
                'kepatuhan_anak' => $request->kepatuhan_anak,
                'hubungan_dengan_ayah' => $request->hubungan_dengan_ayah,
                'hubungan_dengan_ibu' => $request->hubungan_dengan_ibu,
                'kemampuan_buang_air' => $request->kemampuan_buang_air,
                'kebiasaan_ngompol' => $request->kebiasaan_ngompol,
                'selera_makan' => $request->selera_makan,
                'kebiasan_tidur_malam' => $request->kebiasaan_tidur_malam,
                'kebiasan_tidur_siang' => $request->kebiasaan_tidur_siang,
                'kebiasan_bangun_pagi' => $request->kebiasaan_bangun_pagi,
                'hal_penting_waktu_tidur' => $request->hal_penting_waktu_tidur,
                'kebiasan_bangun_siang' => $request->kebiasan_bangun_siang,
                'hal_mengenai_tingkah_anak' => $request->hal_mengenai_tingkah_anak,
                'mudah_bergaul' => $request->mudah_bergaul,
                'sifat_baik' => $request->sifat_baik,
                'sifat_buruk' => $request->sifat_buruk,
                'pembantu_rumah_tangga' => $request->pembantu_rumah_tangga,
                'peralatan_elektronik' =>  json_encode(array_values(array_unique($request->peralatan_elektronik))),
            ]);

            // 6. Buat data Keterangan Peserta
            $keterangan = KeteranganPeserta::create([
                'keterangan_membaca' => $request->keterangan_membaca,
                'keterangan_membaca_hijaiyah' => $request->keterangan_membaca_hijaiyah,
                'keterangan_menulis' => $request->keterangan_menulis,
                'keterangan_menghitung' => $request->keterangan_menghitung,
                'keterangan_menggambar' => $request->keterangan_menggambar,
                'keterangan_berwudhu' => $request->keterangan_berwudhu,
                'keterangan_tata_cara_shalat' => $request->keterangan_tata_cara_shalat,
                'keterangan_hafalan_juz_ama' => $request->keterangan_hafalan_juz_ama,
                'keterangan_hafalan_murottal' => $request->keterangan_hafalan_murottal,
                'keterangan_hafalan_doa' => $request->keterangan_hafalan_doa,
                'judulbuku_berlatihmembaca_latin' => $request->judulbuku_berlatihmembaca_latin,
                'judulbuku_berlatihmembaca_hijaiyah' => $request->judulbuku_berlatihmembaca_hijaiyah,
                'jilid_hijaiyah' => $request->jilid_hijaiyah,
                'keterangan_angka' => $request->keterangan_angka,
                'keterangan_hafal_surat' => $request->keterangan_hafal_surat,
                'hobi' => $request->hobi,
                'keterangan_kisah_islami' => $request->keterangan_kisah_islami,
                'keterangan_majalah' => $request->keterangan_majalah,
            ]);

            $pendanaan = PendanaanPeserta::create([
                'pemasukan_perbulan_orang_tua' => $request->pemasukan_perbulan_orang_tua,
                'keterangan_kenaikan_pendapatan' => $request->keterangan_kenaikan_pendapatan,
                'keterangan_infaq' => $request->keterangan_infaq,
            ]);

            // 7. Buat data Survei Peserta
            $survei = SurveiPeserta::create([
                'larangan_menunggu' => $request->larangan_menunggu,
                'larangan_perhiasan' => $request->larangan_perhiasan,
                'berpakaian_islami' => $request->berpakaian_islami,
                'menghadiri_pertemuan_wali' => $request->menghadiri_pertemuan_wali,
                'kontrol_pengembangan' => $request->kontrol_pengembangan,
                'larangan_merokok' => $request->larangan_merokok,
                'tidak_bekerjasama' => $request->tidak_bekerjasama,
                'penjadwalan' => $request->penjadwalan,
            ]);

            // 8. Buat data Peserta
            $peserta = Peserta::create([
                'kode_pendaftaran_id' => $request->kode_pendaftaran_id,
                'tahun_ajaran_masuk' => $tahunAjaranNama,
                'keluarga_id' => $keluarga->id,
                'id_informasi' => $informasi->id,
                'id_keterangan' => $keterangan->id,
                'id_pendanaan' => $pendanaan->id,
                'id_survei' => $survei->id,
                'nama' => $request->nama_peserta,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'agama' => $request->agama,
                'bahasa_sehari' => $request->bahasa_sehari,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nama_panggilan' => $request->nama_panggilan,
                'berat_badan' => $request->berat_badan,
                'tinggi_badan' => $request->tinggi_badan,
                'jumlah_saudara_kandung' => $request->jumlah_saudara_kandung,
                'jumlah_saudara_tiri' => $request->jumlah_saudara_tiri,
                'anak_ke' => $request->anak_ke,
                'mempunyai_alergi' => $request->mempunyai_alergi,
                'status_peserta' => 'pending',
                'penyakit_berapalama' => $request->penyakit_berapalama,
                'penyakit_kapan' => $request->penyakit_kapan,
                'penyakit_pantangan' => $request->penyakit_pantangan,
                'toilet_traning' => $request->toilet_traning,
                'ttd_ortu' => $fileName, // Simpan nama file
                'latar_belakang' => $request->latar_belakang,
                'harapan_keislaman' => $request->harapan_keislaman,
                'harapan_keilmuan' => $request->harapan_keilmuan,
                'harapan_sosial' => $request->harapan_sosial,
                'berapa_lama_bersekolah' => $request->berapa_lama_bersekolah,
            
            ]);

            $saudara = Saudara::create([
                'peserta_id' => $peserta->id,
                'nama' => $request->nama_saudara,
                'hubungan' => $request->hubungan_saudara,
                'umur' => $request->umur_saudara,
            ]);

            // Setelah membuat peserta, tambahkan pembuatan transaksi
            if ($peserta->status_peserta === 'pending') {
                // Tentukan total bayar berdasarkan pemasukan perbulan
                $biayaPerBulan = match($request->pemasukan_perbulan_orang_tua) {
                    '1' => 500000,
                    '2' => 750000,
                    '3' => 1000000,
                    default => 500000,
                };

                // Hitung total bayar berdasarkan lama bersekolah
                $lamaBersekolah = (int) $request->berapa_lama_bersekolah;
                $totalBayar = $biayaPerBulan * $lamaBersekolah;

                $kodeTransaksi = 'TRX-' . time() . '-' . $peserta->id;
                
                // Buat transaksi
                $transaksi = \App\Models\Transaksi::create([
                    'peserta_id' => $peserta->id,
                    'tahun_masuk' => $peserta->tahun_ajaran_masuk,
                    'total_bayar' => $totalBayar,
                    'status_pembayaran' => 0,
                    'kode_transaksi' => $kodeTransaksi,
                    'midtrans_transaction_id' => null,
                    'midtrans_payment_type' => null,
                ]);

                // Kirim email notifikasi
                Mail::to($peserta->email)->send(new TransactionCreatedMail(
                    $peserta,
                    $kodeTransaksi,
                    $totalBayar
                ));
            }

            // Update status kode pendaftaran menjadi sudah digunakan
            // $kodePendaftaran->update(['status' => 0]); // Asumsikan 0 = sudah digunakan

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Pendaftaran telah berhasil diproses']); // Kembalikan JSON saat sukses

        } catch (\Exception $e) {
            DB::rollback();
            

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkKode($kode)
    {
        Log::info('Accessing checkKode with code: ' . $kode);
        
        try {
            // Decode URL jika diperlukan
           
            
            $kodePendaftaran = KodePendaftaran::where('kode', $kode)
                ->whereHas('pendaftaran', function ($query) {
                    $query->where('status', '1');
                })
                ->first();

            if (!$kodePendaftaran) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kode tidak valid'
                ]);
            }

            // Cek apakah kode sudah digunakan oleh peserta
            if ($kodePendaftaran->peserta()->exists()) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Kode sudah digunakan'
                ]);
            }

            Log::info('KodePendaftaran query result:', ['exists' => true, 'used' => false]);
            
            return response()->json([
                'valid' => true,
                'message' => 'Kode ditemukan dan dapat digunakan'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in checkKode: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 