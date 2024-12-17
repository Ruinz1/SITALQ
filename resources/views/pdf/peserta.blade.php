<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DATA WAWANCARA CALON SISWA BARU</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .header img {
            width: 100px; /* Ganti dengan ukuran logo yang sesuai */
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section {
            margin-top: 20px;
            border: 1px solid #000;
            padding: 10px;
        }
        .field {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
                    $imagePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'logo-tk-circle.png');
                    if (file_exists($imagePath)) {
                        try {
                            $imageData = base64_encode(file_get_contents($imagePath));
                            echo '<img src="data:image/png;base64,' . $imageData . '" 
                                    ">';
                        } catch (\Exception $e) {
                            echo "Error loading image: " . $e->getMessage();
                        }
                    } else {
                        echo "File not found at: " . $imagePath;
                    }
        @endphp
      <!-- Ganti dengan path logo Anda -->
        <div class="title">YAYASAN KHAIRU UMMAH</div>
        <div>TAMAN KANAK-KANAK ISLAM TERPADU (TKIT) AL-QOLAM</div>
        <div>Alamat: Kompleks Perumans Tinggede, Kec. Marawola Kab. Sigi</div>
        <div>Email: tkit_alqolam@yahoo.com</div>
    </div>
    <div class="body">
        <div style="text-align: center; font-weight: bold; font-size: 24px; margin-top: 20px;">
            DATA WAWANCARA /
        </div>
        <div style="text-align: center; font-weight: bold; font-size: 24px;">
            CALON SISWA BARU
        </div>
        <div style="text-align: center; font-size: 20px;">
            {{ $peserta->tahun_ajaran_masuk }}
        </div>
        <p>Aassalamualikum Wr. Wb</p>
        <p>Selamat datang dan ahlan wasahlan Bapak/Ibu, Orang Tua  / Wali calon murid baru Taman
            Kanak-kanak islam Terpadu TKIT AL-QOLAM Tinggede, semoga jalinan silahturahmi kita bernilai
            ibadah dan dicatat Allah sebagai amal sholeh. Aaminn
        </p>
        <p>
            Untuk melengkapi data dan informasi mengenai putra-putri Bapak/ibu, kami mengharap Bapak/Ibu mengisi
            lembaran ini dengan jelas, benar dan lengkap. Keterangan dari Bapak/Ibu menjadi informasi yang berguna bagi kami,
            terutama dalam rangka meningkatkan pemahaman kami akan latar belakang anak.
        </p>
        <p>Tentu saja kami mohon maaf sekiranya hal ini akan merepotkan dan menggangu Bapak/Ibu sekalian, namun
            kami yakin Bapak/Ibu dapat memahaminya.
        </p>
        <p>Wassalamualaikum</p>
    </div>

    <div class="section">
        <div class="field">
            <span class="label">DATA WAWANCARA CALON SISWA BARU</span>
        </div>
        <div class="field">
            <span class="label">NAMA SISWA:</span> {{ $peserta->nama }}
        </div>
        <div class="field">
            <span class="label">NAMA ORANG TUA/WALI:</span> {{ $peserta->keluarga->ibu->nama }} / {{ $peserta->keluarga->ayah->nama }}
        </div>
        <div class="field">
            <span class="label">NO. URUT:</span> {{ $peserta->kodePendaftaran->kode }}
        </div>
    </div>

    <!-- Tambahkan pemisah halaman -->
    <div class="page-break"></div>

    <div class="section">
        <div class="field">
            <span class="label">KETERANGAN ANAK DIDIK</span>
        </div>
        <div class="subsection">
            <div>
                <span>Nama Lengkap:</span>
                {{ $peserta->nama }}
            </div>
            <div>
                <spa>Nama Panggilan:</span>
                {{ $peserta->nama_panggilan }}
            </div>
            <div>
                <span>Jenis Kelamin:</span>
                {{ $peserta->jenis_kelamin }}
            </div>
            <div>
                <span>Tempat, tanggal lahir:</span>
                {{ $peserta->tempat_lahir }}, {{$peserta->tanggal_lahir}}
            </div>
            <div>
                <span>Anak ke:</span>
                {{ $peserta->anak_ke }}
            </div>
            <div>
                <span>Agama dianut:</span>
                {{ $peserta->agama }}
            </div>
            <div>
                <span>Jumlah saudara kandung:</span>
                {{ $peserta->jumlah_saudara_kandung }}
            </div>
            <div>
                <span>Jumlah saudara tiri:</span>
                {{ $peserta->jumlah_saudara_tiri }}
            </div>
            <div>
                <span>Sebutkan nama saudara dan usia saat ini:</span>
                <ul>
                    @foreach ($peserta->saudara as $sod)
                        <li>Nama: {{ $sod->nama }}, </li>
                        <li>Hubungan: {{ $sod->hubungan }},</li>
                        <li>Umur: {{ $sod->umur }} tahun</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <span>Bahasa sehari-hari:</span>
                {{ $peserta->bahasa_sehari }}
            </div>
            <div>
                <span>Alamat tempat tinggal:</span>
                {{ $peserta->alamat }}
            </div>
        </div>
        <div class="field">
            <span class="label">Data kesehatan</span>
        </div>
        <div class="subsection">
            <div>
                <span>Tinggi Badan:</span>
                {{ $peserta->tinggi_badan }} cm
            </div>
            <div>
                <span>Berat Badan:</span>
                {{ $peserta->berat_badan }} kg
            </div>
            <div>
                <span>Penyakit diderita dengan perawatan:</span>
                @if ($peserta->penyakit_berapalama !== null)
                    <ul>
                        <li>{{ $peserta->penyakit_berapalama }}</li>
                        <li>{{ $peserta->penyakit_kapan }}</li>
                        <li>{{ $peserta->penyakit_pantangan }}</li>
                    </ul>
                @else
                    <span>Peserta tidak memiliki riwayat penyakit</span>
                @endif
            </div>
            <div>
                <span>Alergi pada (makanan,debu):</span>
                {{$peserta->mempunyai_alergi}}
            </div>
            <div>
                <span>Informasi penting yang mungkin berkenaan dengan putra/putri Bapak/Ibu:</span>
                <br>
                <span>Toilet Traning : </span>
                {{$peserta->toilet_traning}}
                <br>
                <span>Lainnya : </span>
                {{$peserta->lainnya}}
            </div>
        </div>
    </div>

    <!-- Tambahkan section baru untuk Data Pendahuluan -->
    <div class="section">
        <div class="field">
            <span class="label">DATA PENDAHULUAN</span>
        </div>
        <div class="subsection">
            <div>
                <span>Latar Belakang Mendaftarkan Anak:</span>
                {{ $peserta->latar_belakang }}
            </div>
            <div>
                <span>Harapan dalam Bidang Keislaman:</span>
                {{ $peserta->harapan_keislaman }}
            </div>
            <div>
                <span>Harapan dalam Bidang Keilmuan:</span>
                {{ $peserta->harapan_keilmuan }}
            </div>
            <div>
                <span>Harapan dalam Bidang Sosial:</span>
                {{ $peserta->harapan_sosial }}
            </div>
            <div>
                <span>Rencana Lama Bersekolah:</span>
                {{ $peserta->berapa_lama_bersekolah }}
            </div>
        </div>
    </div>

    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">DATA ORANG TUA</div>
        
        <!-- Data Ayah -->
        <div class="subsection">
            <h4>Data Ayah</h4>
            <div class="field">
                <span class="label">Nama:</span>
                {{ $peserta->keluarga->ayah->nama }}
            </div>
            <div class="field">
                <span class="label">Alamat:</span>
                {{ $peserta->keluarga->ayah->alamat }}
            </div>
            <div class="field">
                <span class="label">Tempat Lahir:</span>
                {{ $peserta->keluarga->ayah->tempat_lahir }}
            </div>
            <div class="field">
                <span class="label">Tanggal Lahir:</span>
                {{ $peserta->keluarga->ayah->tanggal_lahir }}
            </div>
            <div class="field">
                <span class="label">Agama:</span>
                {{ $peserta->keluarga->ayah->agama }}
            </div>
            <div class="field">
                <span class="label">Pendidikan Terakhir:</span>
                {{ $peserta->keluarga->ayah->pendidikan_terakhir }}
            </div>
            <div class="field">
                <span class="label">Pekerjaan:</span>
                {{ $peserta->keluarga->ayah->pekerjaan }}
            </div>
            <div class="field">
                <span class="label">No. HP:</span>
                {{ $peserta->keluarga->ayah->no_hp }}
            </div>
            <div class="field">
                <span class="label">Alamat Kantor:</span>
                {{ $peserta->keluarga->ayah->alamat_kantor }}
            </div>
            <div class="field">
                <span class="label">Sosial Media:</span>
                {{ $peserta->keluarga->ayah->sosmed }}
            </div>
        </div>

        <!-- Data Ibu -->
        <div class="subsection">
            <h4>Data Ibu</h4>
            <div class="field">
                <span class="label">Nama:</span>
                {{ $peserta->keluarga->ibu->nama }}
            </div>
            <div class="field">
                <span class="label">Alamat:</span>
                {{ $peserta->keluarga->ibu->alamat }}
            </div>
            <div class="field">
                <span class="label">Tempat Lahir:</span>
                {{ $peserta->keluarga->ibu->tempat_lahir }}
            </div>
            <div class="field">
                <span class="label">Tanggal Lahir:</span>
                {{ $peserta->keluarga->ibu->tanggal_lahir }}
            </div>
            <div class="field">
                <span class="label">Agama:</span>
                {{ $peserta->keluarga->ibu->agama }}
            </div>
            <div class="field">
                <span class="label">Pendidikan Terakhir:</span>
                {{ $peserta->keluarga->ibu->pendidikan_terakhir }}
            </div>
            <div class="field">
                <span class="label">Pekerjaan:</span>
                {{ $peserta->keluarga->ibu->pekerjaan }}
            </div>
            <div class="field">
                <span class="label">No. HP:</span>
                {{ $peserta->keluarga->ibu->no_hp }}
            </div>
            <div class="field">
                <span class="label">Alamat Kantor:</span>
                {{ $peserta->keluarga->ibu->alamat_kantor }}
            </div>
            <div class="field">
                <span class="label">Sosial Media:</span>
                {{ $peserta->keluarga->ibu->sosmed }}
            </div>
        </div>

        @if($peserta->keluarga->wali)
        <!-- Data Wali -->
        <div class="subsection">
            <h4>Data Wali</h4>
            <div class="field">
                <span class="label">Nama:</span>
                {{ $peserta->keluarga->wali->nama }}
            </div>
            <div class="field">
                <span class="label">Alamat:</span>
                {{ $peserta->keluarga->wali->alamat }}
            </div>
            <div class="field">
                <span class="label">Tempat Lahir:</span>
                {{ $peserta->keluarga->wali->tempat_lahir }}
            </div>
            <div class="field">
                <span class="label">Tanggal Lahir:</span>
                {{ $peserta->keluarga->wali->tanggal_lahir }}
            </div>
            <div class="field">
                <span class="label">Agama:</span>
                {{ $peserta->keluarga->wali->agama }}
            </div>
            <div class="field">
                <span class="label">Pendidikan Terakhir:</span>
                {{ $peserta->keluarga->wali->pendidikan_terakhir }}
            </div>
            <div class="field">
                <span class="label">Pekerjaan:</span>
                {{ $peserta->keluarga->wali->pekerjaan }}
            </div>
            <div class="field">
                <span class="label">No. HP:</span>
                {{ $peserta->keluarga->wali->no_hp }}
            </div>
            <div class="field">
                <span class="label">Alamat Kantor:</span>
                {{ $peserta->keluarga->wali->alamat_kantor }}
            </div>
            <div class="field">
                <span class="label">Sosial Media:</span>
                {{ $peserta->keluarga->wali->sosmed }}
            </div>
        </div>
        @endif
    </div>

    <!-- Tambahkan bagian lain sesuai kebutuhan -->

    <div class="section">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="section-title" style="text-align: left; flex: 1;">Mengetahui</div>
            <div class="section-title" style="text-align: right;">Tinggede, {{ \Carbon\Carbon::parse($peserta->tanggal_diterima)->format('d-m-Y') }}</div>
        </div>
        

        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    Kepala TKIT Al Qolam
                    <br>
                    @php
                    $imagePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'wadek.png');
                    if (file_exists($imagePath)) {
                        try {
                            $imageData = base64_encode(file_get_contents($imagePath));
                            echo '<img src="data:image/png;base64,' . $imageData . '" 
                                       alt="Tanda Tangan Kepsek" 
                                       style="max-width: 150px; max-height: 100px;">';
                        } catch (\Exception $e) {
                            echo "Error loading image: " . $e->getMessage();
                        }
                    } else {
                        echo "File not found at: " . $imagePath;
                    }
                    @endphp
                </td>
                <td style="width: 50%; text-align: center;">
                    Orang Tua/Wali
                    <br>
                    @php
                    $imagePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $peserta->ttd_ortu);
                    if (file_exists($imagePath)) {
                        try {
                            $imageData = base64_encode(file_get_contents($imagePath));
                            echo '<img src="data:image/png;base64,' . $imageData . '" 
                                       alt="Tanda Tangan Kepsek" 
                                       style="max-width: 150px; max-height: 100px;">';
                        } catch (\Exception $e) {
                            echo "Error loading image: " . $e->getMessage();
                        }
                    } else {
                        echo "File not found at: " . $imagePath;
                    }
                    @endphp
                </td>
            </tr>
        </table>
    </div>

    <!-- Tambahkan section untuk Informasi Peserta -->
    <div class="section">
        <div class="field">
            <span class="label">INFORMASI PESERTA</span>
        </div>
        <div class="subsection">
            <div>
                <span>Tinggal Bersama:</span>
                {{ $peserta->informasi->tinggal_bersama }}
            </div>
            <div>
                <span>Jumlah Penghuni Dewasa:</span>
                {{ $peserta->informasi->jumlah_penghuni_dewasa }} orang
            </div>
            <div>
                <span>Jumlah Penghuni Anak:</span>
                {{ $peserta->informasi->jumlah_penghuni_anak }} orang
            </div>
            <div>
                <span>Halaman Bermain di Rumah:</span>
                {{ $peserta->informasi->halaman_bermain_dirumah }}
            </div>
            <div>
                <span>Pergaulan dengan Sebaya:</span>
                {{ $peserta->informasi->pergaulan_dengan_sebaya }}
            </div>
            <div>
                <span>Kepatuhan Anak:</span>
                {{ $peserta->informasi->kepatuhan_anak }}
            </div>
            <div>
                <span>Hubungan dengan Ayah:</span>
                {{ $peserta->informasi->hubungan_dengan_ayah }}
            </div>
            <div>
                <span>Hubungan dengan Ibu:</span>
                {{ $peserta->informasi->hubungan_dengan_ibu }}
            </div>
            <div>
                <span>Kemampuan Buang Air:</span>
                {{ $peserta->informasi->kemampuan_buang_air }}
            </div>
            <div>
                <span>Kebiasaan Ngompol:</span>
                {{ $peserta->informasi->kebiasaan_ngompol }}
            </div>
            <div>
                <span>Selera Makan:</span>
                {{ $peserta->informasi->selera_makan }}
            </div>
            <div>
                <span>Kebiasaan Tidur:</span>
                <ul>
                    <li>Malam: {{ $peserta->informasi->kebiasan_tidur_malam }}</li>
                    <li>Siang: {{ $peserta->informasi->kebiasan_tidur_siang }}</li>
                    <li>Bangun Pagi: {{ $peserta->informasi->kebiasan_bangun_pagi }}</li>
                    <li>Bangun Siang: {{ $peserta->informasi->kebiasan_bangun_siang }}</li>
                </ul>
            </div>
            <div>
                <span>Hal Penting Waktu Tidur:</span>
                {{ $peserta->informasi->hal_penting_waktu_tidur }}
            </div>
            <div>
                <span>Hal Mengenai Tingkah Anak:</span>
                {{ $peserta->informasi->hal_mengenai_tingkah_anak }}
            </div>
        </div>
    </div>

    <!-- Tambahkan section untuk Keterangan Peserta -->
    <div class="section">
        <div class="field">
            <span class="label">KETERANGAN PESERTA</span>
        </div>
        <div class="subsection">
            <div>
                <span>Keterangan Membaca:</span>
                {{ $peserta->keterangan->keterangan_membaca }}
            </div>
            <div>
                <span>Buku Latihan Membaca Latin:</span>
                {{ $peserta->keterangan->judulbuku_berlatihmembaca_latin }}
            </div>
            <div>
                <span>Keterangan Membaca Hijaiyah:</span>
                {{ $peserta->keterangan->keterangan_membaca_hijaiyah }}
            </div>
            <div>
                <span>Buku Latihan Membaca Hijaiyah:</span>
                {{ $peserta->keterangan->judulbuku_berlatihmembaca_hijaiyah }}
            </div>
            <div>
                <span>Jilid Hijaiyah:</span>
                {{ $peserta->keterangan->jilid_hijaiyah }}
            </div>
            <div>
                <span>Keterangan Menulis:</span>
                {{ $peserta->keterangan->keterangan_menulis }}
            </div>
            <div>
                <span>Keterangan Angka:</span>
                {{ $peserta->keterangan->keterangan_angka }}
            </div>
            <div>
                <span>Keterangan Menghitung:</span>
                {{ $peserta->keterangan->keterangan_menghitung }}
            </div>
            <div>
                <span>Keterangan Menggambar:</span>
                {{ $peserta->keterangan->keterangan_menggambar }}
            </div>
            <div>
                <span>Keterangan Berwudhu:</span>
                {{ $peserta->keterangan->keterangan_berwudhu }}
            </div>
            <div>
                <span>Keterangan Tata Cara Shalat:</span>
                {{ $peserta->keterangan->keterangan_tata_cara_shalat }}
            </div>
            <div>
                <span>Hafalan Juz Amma:</span>
                {{ $peserta->keterangan->keterangan_hafalan_juz_ama }}
            </div>
            <div>
                <span>Mendengar Murottal:</span>
                {{ $peserta->keterangan->keterangan_hafalan_murottal }}
            </div>
            <div>
                <span>Hobi:</span>
                {{ $peserta->keterangan->hobi }}
            </div>
            <div>
                <span>Hafalan Doa:</span>
                {{ $peserta->keterangan->keterangan_hafalan_doa }}
            </div>
            <div>
                <span>Hafalan Surah:</span>
                {{ $peserta->keterangan->keterangan_hafal_surat }}
            </div>
            <div>
                <span>Berlangganan Majalah:</span>
                {{ $peserta->keterangan->keterangan_majalah }}
            </div>
            <div>
                <span>Mendengar Kisah Islami:</span>
                {{ $peserta->keterangan->keterangan_kisah_islami }}
            </div>
        </div>
    </div>

    <!-- Tambahkan section untuk Pendanaan dan Survei -->
    <div class="section">
        <div class="field">
            <span class="label">PENDANAAN DAN SURVEI</span>
        </div>
        <div class="subsection">
            <div>
                <span>Pemasukan Perbulan Orang Tua:</span>
                {{ $peserta->pendanaan->pemasukan_perbulan_orang_tua }}
            </div>
            <div>
                <span>Keterangan Kenaikan Pendapatan:</span>
                {{ $peserta->pendanaan->keterangan_kenaikan_pendapatan }}
            </div>
            <div>
                <span>Keterangan Infaq:</span>
                {{ $peserta->pendanaan->keterangan_infaq }}
            </div>
            
            <h4>Hasil Survei:</h4>
            <div>
                <span>Larangan Menunggu:</span>
                {{ $peserta->survei->larangan_menunggu }}
            </div>
            <div>
                <span>Larangan Perhiasan:</span>
                {{ $peserta->survei->larangan_perhiasan }}
            </div>
            <div>
                <span>Berpakaian Islami:</span>
                {{ $peserta->survei->berpakaian_islami }}
            </div>
            <div>
                <span>Menghadiri Pertemuan Wali:</span>
                {{ $peserta->survei->menghadiri_pertemuan_wali }}
            </div>
            <div>
                <span>Kontrol Pengembangan:</span>
                {{ $peserta->survei->kontrol_pengembangan }}
            </div>
            <div>
                <span>Larangan Merokok:</span>
                {{ $peserta->survei->larangan_merokok }}
            </div>
            <div>
                <span>Tidak Bekerjasama:</span>
                {{ $peserta->survei->tidak_bekerjasama }}
            </div>
            <div>
                <span>Penjadwalan:</span>
                {{ $peserta->survei->penjadwalan }}
            </div>
        </div>
    </div>

  

    
</body>
</html> 