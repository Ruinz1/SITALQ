@extends('layouts.master')
@section('title', 'Pendaftaran - TKIT Al Qolam')
@section('content')
<section id="Content" class="bg-portto-black flex min-h-screen flex-wrap justify-center">
    <div class="w-full lg:w-1/2 flex flex-col gap-[50px] items-center justify-center mx-auto py-4 bg-[url('assets/images/Ellipse.svg')] bg-center bg-no-repeat bg-[length:540px]">
        <div class="form-header w-full">
            <div class="logo-container">
                <img src="{{ asset('assets/images/thumbnails/header-3.png') }}" alt="Logo Siswa">
            </div>
            <h1 class="text-white text-2xl font-bold mt-4">! PASTIKAN SUDAH MEMILIKI KODE PENDAFTARAN !</h1>
            <p class="text-white mt-2">Kode pendaftaran didapatkan dari sekolah. Jika tidak memiliki kode pendaftaran atau kode pendaftaran bermasalah, silahkan hubungi nomor berikut: <a href="https://wa.me/6282193734482" class="text-portto-purple">081234567890</a></p>
        </div>
        
        <!-- Progress indicator -->
        <div class="flex justify-between w-full lg:w-[550px] mb-8 px-4 lg:px-0">
            <div class="step active cursor-pointer flex flex-col items-center w-[20%]" data-step="1">
                <div class="step-circle bg-portto-purple text-white rounded-full w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs lg:text-base">1</div>
                <span class="text-white text-xs lg:text-sm mt-2 text-center">Data Peserta</span>
            </div>
            <div class="step cursor-pointer flex flex-col items-center w-[20%]" data-step="2">
                <div class="step-circle bg-white text-portto-purple rounded-full w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs lg:text-base">2</div>
                <span class="text-white text-xs lg:text-sm mt-2 text-center">Data Pendahuluan</span>
            </div>
            <div class="step cursor-pointer flex flex-col items-center w-[20%]" data-step="3">
                <div class="step-circle bg-white text-portto-purple rounded-full w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs lg:text-base">3</div>
                <span class="text-white text-xs lg:text-sm mt-2 text-center">Data Keluarga Peserta</span>
            </div>
            <div class="step cursor-pointer flex flex-col items-center w-[20%]" data-step="4">
                <div class="step-circle bg-white text-portto-purple rounded-full w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs lg:text-base">4</div>
                <span class="text-white text-xs lg:text-sm mt-2 text-center">Data Informasi Peserta</span>
            </div>
            <div class="step cursor-pointer flex flex-col items-center w-[20%]" data-step="5">
                <div class="step-circle bg-white text-portto-purple rounded-full w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs lg:text-base">5</div>
                <span class="text-white text-xs lg:text-sm mt-2 text-center">Data Keterangan Peserta</span>
            </div>
            <div class="step cursor-pointer flex flex-col items-center w-[20%]" data-step="6">
                <div class="step-circle bg-white text-portto-purple rounded-full w-6 h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs lg:text-base">6</div>
                <span class="text-white text-xs lg:text-sm mt-2 text-center">Data Pendanaan & Survey Peserta</span>
            </div>
        </div>

        <form id="multiStepForm" action="{{ route('peserta.store') }}" method="POST" class="flex flex-col gap-5 w-[550px]">
            @csrf
            <!-- Step 1: Data Peserta -->
            <div class="step-content" data-step="1">
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kode Pendaftaran</span>
                    <input type="text" name="kode_pendaftaran_id" id="kode_pendaftaran_id" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kode pendaftaran" required>
                    <div id="kode_error" class="text-red-500 text-sm hidden">Kode pendaftaran tidak valid</div>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Nama Lengkap</span>
                    <input type="text" name="nama_peserta" id="nama" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama lengkap" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold mt-5">
                    <span class="text-white">Alamat Email</span>
                    <input type="email" name="email" id="email" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat email" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold mt-5">
                    <span class="text-white">Alamat</span>
                    <input type="text" name="alamat" id="alamat" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Agama</span>
                    <input type="text" name="agama" id="agama" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan agama" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Tempat Lahir</span>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tempat lahir" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Tanggal Lahir</span>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tanggal lahir" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Jenis Kelamin</span>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan jenis kelamin" required>
                        <option value="" class="text-[#878C9C]" selected disabled>Select category</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Nama Panggilan</span>
                    <input type="text" name="nama_panggilan" id="nama_panggilan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama panggilan" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Bahasa sehari yang digunakan</span>
                    <input type="text" name="bahasa_sehari" id="bahasa_sehari" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan agama" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Tinggi Badan</span>
                    <input type="number" name="tinggi_badan" id="tinggi_badan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tinggi badan" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Berat Badan</span>
                    <input type="number" name="berat_badan" id="berat_badan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan berat badan" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Jumlah Saudara Tiri (Opsional)</span>
                    <input type="number" value="0" name="jumlah_saudara_tiri" id="jumlah_saudara_tiri" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan jumlah saudara tiri" oninput="updateNamaSaudaraForms()">
                    <p class="text-white text-sm mt-2">* Jika tidak memiliki saudara maka isi 0</p>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Jumlah Saudara Kandung</span>
                    <input type="number" name="jumlah_saudara_kandung" id="jumlah_saudara_kandung" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan jumlah saudara kandung" oninput="updateNamaSaudaraForms()">
                    <p class="text-white text-sm mt-2">* Jika tidak memiliki saudara maka isi 0</p>
                </label>
                <div id="namaSaudaraContainer" class="hidden"></div>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Anak ke</span>
                    <input type="number" name="anak_ke" id="anak_ke" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan anak ke" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold col-span-2">
                    <span class="text-white">Apakah memiliki Penyakit yang Diderita dengan perawatan ?</span>
                    <select name="has_penyakit" id="has_penyakit" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="ya">Ya</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </label>

                <!-- Form untuk detail penyakit, disembunyikan secara default -->
                <div id="penyakitDetails" class="hidden">
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Berapa lama penyakit yang diderita ?</span>
                        <input type="text" name="penyakit_berapalama" id="penyakit_berapalama" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan lama penyakit">
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Kapan Penyakit diderita ?</span>
                        <input type="text" name="penyakit_kapan" id="penyakit_kapan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kapan penyakit diderita">
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Apakah Penyakit mempunyai pantangan ?</span>
                        <input type="text" name="penyakit_pantangan" id="penyakit_pantangan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pantangan penyakit">
                    </label>
                </div>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Mempunyai Alergi (Opsional)</span>
                    <input type="textarea" name="mempunyai_alergi" id="mempunyai_alergi" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan memiliki alergi">
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Toilet Training (kemampuan ke kamar mandi untuk buang air dll)</span>
                    <input type="textarea" name="toilet_traning" id="toilet_traning" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan memiliki alergi">
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Tanda Tangan Orang Tua</span>
                    <input type="file" name="tanda_tangan" id="tanda_tangan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" accept=".png" required>
                    <p class="text-white text-sm mt-1">* File harus format PNG</p>
                </label>
                <div class="flex justify-end mt-12">
                    <button type="button" class="next-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Next Step</button>
                </div>
            </div>

            <!-- Add Step: Data Pendahuluan -->

            <div class="step-content hidden" data-step="2">
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Latar Belakang Mendaftarkan Anak ke TKIT AL-Qolam</span>
                        <input type="textarea" name="latar_belakang" id="latar_belakang" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan memiliki alergi">
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Apa Harapan Bapak/Ibu Mendaftarkan anak ke TKIT AL-Qolam dalam Bidang Keislaman</span>
                        <input type="textarea" name="harapan_keislaman" id="harapan_keislaman" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan memiliki alergi">
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Apa Harapan Bapak/Ibu Mendaftarkan anak ke TKIT AL-Qolam dalam Bidang Keilmuan</span>
                        <input type="textarea" name="harapan_keilmuan" id="harapan_keilmuan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan memiliki alergi">
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Apa Harapan Bapak/Ibu Mendaftarkan anak ke TKIT AL-Qolam dalam Bidang Sosial</span>
                        <input type="textarea" name="harapan_sosial" id="harapan_sosial" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan memiliki alergi">
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold col-span-2">
                        <span class="text-white">Berapa Lama Bapak/Ibu Berencana Menyekolahkan Anak di TKIT AL-Qolam ?</span>
                        <select name="berapa_lama_bersekolah" id="berapa_lama_bersekolah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                            <option value="" selected disabled>Pilih opsi</option>
                            <option value="1 tahun">1 Tahun</option>
                            <option value="2 tahun">2 Tahun</option>
                            <option value="3 tahun">3 Tahun</option>
                        </select>
                    </label>
                <div class="flex justify-between mt-12">
                    <button type="button" class="prev-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Previous</button>
                    <button type="button" class="next-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Next Step</button>
                </div>
            </div>

            <!-- Step 2: Data Keluarga -->
            <div class="step-content hidden" data-step="3">
                <div class="grid grid-cols-2 gap-5">
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Nama Ayah</span>
                        <input type="text" name="nama_ayah" id="nama_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama ayah" required>
                    </label>
                   <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Nama Ibu</span>
                        <input type="text" name="nama_ibu" id="nama_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama ibu" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Alamat Ayah</span>
                        <input type="text" name="alamat_ayah" id="alamat_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Agama Ayah</span>
                        <input type="text" name="agama_ayah" id="agama_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan agama" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Agama Ibu</span>
                        <input type="text" name="agama_ibu" id="agama_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan agama" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Alamat Ibu</span>
                        <input type="text" name="alamat_ibu" id="alamat_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Tempat Lahir Ayah</span>
                        <input type="text" name="tempat_lahir_ayah" id="tempat_lahir_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tempat lahir" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Tempat Lahir Ibu</span>
                        <input type="text" name="tempat_lahir_ibu" id="tempat_lahir_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tempat lahir" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Tanggal Lahir Ayah</span>
                        <input type="date" name="tanggal_lahir_ayah" id="tanggal_lahir_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tanggal lahir" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Tanggal Lahir Ibu</span>
                        <input type="date" name="tanggal_lahir_ibu" id="tanggal_lahir_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tanggal lahir" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Pekerjaan Ayah</span>
                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pekerjaan" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Pendidikan terakhir Ayah</span>
                        <input type="text" name="pendidikan_terakhir_ayah" id="pendidikan_terakhir_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pekerjaan" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Pekerjaan Ibu</span>
                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pekerjaan" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Pendidikan terakhir Ibu</span>
                        <input type="text" name="pendidikan_terakhir_ibu" id="pendidikan_terakhir_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pekerjaan" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Alamat Kantor Ayah</span>
                        <input type="text" name="alamat_kantor_ayah" id="alamat_kantor_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat kantor" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Alamat Kantor Ibu</span>
                        <input type="text" name="alamat_kantor_ibu" id="alamat_kantor_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat kantor" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">No Telepon / Handphone Ayah</span>
                        <input type="text" name="no_hp_ayah" id="no_hp_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan no telepon / handphone" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">No Telepon / Handphone Ibu</span>
                        <input type="text" name="no_hp_ibu" id="no_hp_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan no telepon / handphone" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Sosial Media Ayah</span>
                        <input type="text" name="sosmed_ayah" id="sosmed_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan no telepon / handphone" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Sosial Media Ibu</span>
                        <input type="text" name="sosmed_ibu" id="sosmed_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan no telepon / handphone" required>
                    </label>
                    <label class="flex flex-col gap-[10px] font-semibold col-span-2">
                        <span class="text-white">Apakah memiliki wali?</span>
                        <select name="has_wali" id="has_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                            <option value="" selected disabled>Pilih opsi</option>
                            <option value="ya">Ya</option>
                            <option value="tidak">Tidak</option>
                        </select>
                    </label>

                    <!-- Form Wali -->
                    <div id="waliForm" class="hidden contents">
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Nama Wali</span>
                            <input type="text" name="nama_wali" id="nama_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Alamat Wali</span>
                            <input type="text" name="alamat_wali" id="alamat_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Agama Wali</span>
                            <input type="text" name="agama_wali" id="agama_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan agama wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Tempat Lahir Wali</span>
                            <input type="text" name="tempat_lahir_wali" id="tempat_lahir_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tempat lahir wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Tanggal Lahir Wali</span>
                            <input type="date" name="tanggal_lahir_wali" id="tanggal_lahir_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan tanggal lahir wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Pekerjaan Wali</span>
                            <input type="text" name="pekerjaan_wali" id="pekerjaan_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pekerjaan wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Pendidikan Terakhir Wali</span>
                            <input type="text" name="pendidikan_terakhir_wali" id="pendidikan_terakhir_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan pekerjaan wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Alamat Kantor Wali</span>
                            <input type="text" name="alamat_kantor_wali" id="alamat_kantor_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alamat kantor wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">No Telepon / Handphone Wali</span>
                            <input type="text" name="no_hp_wali" id="no_hp_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan no telepon / handphone wali">
                        </label>
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Sosial Media Wali</span>
                            <input type="text" name="sosmed_wali" id="sosmed_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan no telepon / handphone" required>
                        </label>
                    </div>
                </div>
                <div class="flex justify-between mt-12">
                    <button type="button" class="prev-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Previous</button>
                    <button type="button" class="next-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Next Step</button>
                </div>
            </div>

            <!-- Step 3: Data Informasi Peserta -->
            <div class="step-content hidden" data-step="4">
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Tinggal bersama</span>
                    <select name="tinggal_bersama" id="tinggal_bersama" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Keluarga Sendiri">Keluarga Sendiri</option>
                        <option value="Keluarga Orang Lain">Keluarga Orang Lain</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Jumlah Penghuni Dewasa di Rumah</span>
                    <input type="number" name="jumlah_penghuni_dewasa" id="jumlah_penghuni_dewasa" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan jumlah penghuni dewasa" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Jumlah Penghuni Anak di Rumah</span>
                    <input type="number" name="jumlah_penghuni_anak" id="jumlah_penghuni_anak" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan jumlah penghuni anak" required>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Halaman Bermain Dirumah</span>
                    <select name="halaman_bermain" id="halaman_bermain_dirumah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Ada">Ada</option>
                        <option value="Tidak Ada">Tidak Ada</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Pergaulan dengan anak seumuran</span>
                    <select name="pergaulan_dengan_sebaya" id="pergaulan_dengan_sebaya" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Pasif">Pasif</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kepatuhan anak</span>
                    <select name="kepatuhan_anak" id="kepatuhan_anak" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Kurang">Kurang</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Hubungan dengan ayah</span>
                    <select name="hubungan_dengan_ayah" id="hubungan_dengan_ayah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Kurang">Kurang</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Hubungan dengan ibu</span>
                    <select name="hubungan_dengan_ibu" id="hubungan_dengan_ibu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Kurang">Kurang</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kemampuan Buang Air Masih Harus Dibina ?</span>
                    <select name="kemampuan_buang_air" id="kemampuan_buang_air" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                        <option value="Kadang-kadang">Kadang-kadang</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Apakah anak memiliki kebiasaan ngompol?</span>
                    <select name="kebiasaan_ngompol" id="kebiasaan_ngompol" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Sering">Sering</option>
                        <option value="Kadang-kadang">Kadang-kadang</option>
                        <option value="Jarang">Jarang</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Selera Makan</span>
                    <textarea name="selera_makan" id="selera_makan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan selera makan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kebiasaan Tidur Malam</span>
                    <textarea name="kebiasaan_tidur_malam" id="kebiasaan_tidur_malam" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kebiasaan tidur malam"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kebiasaan Tidur Siang</span>
                    <textarea name="kebiasaan_tidur_siang" id="kebiasaan_tidur_siang" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kebiasaan tidur siang"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kebiasaan Bangun Pagi</span>
                    <textarea name="kebiasaan_bangun_pagi" id="kebiasaan_bangun_pagi" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kebiasaan bangun pagi"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Kebiasaan Bangun Siang</span>
                    <textarea name="kebiasan_bangun_siang" id="kebiasan_bangun_siang" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kebiasaan tidur siang"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Hal-hal yang perlu dicatat atau dikemukakan mengenai tingkah anak</span>
                    <textarea name="hal_mengenai_tingkah_anak" id="hal_mengenai_tingkah_anak" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan kebiasaan tidur siang"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak</span>
                    <textarea name="hal_penting_waktu_tidur" id="hal_penting_waktu_tidur" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Apakah anak mudah bergaul ?</span>
                    <select name="mudah_bergaul" id="mudah_bergaul" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Ya">Ya</option>
                        <option value="Kadang-kadang">Kadang-kadang</option>
                        <option value="Tidak">Tidak</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sifat Baik anak</span>
                    <textarea name="sifat_baik" id="sifat_baik" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sifat Buruk anak</span>
                    <textarea name="sifat_buruk" id="sifat_buruk" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Pembantu Rumah Tangga</span>
                    <textarea name="pembantu_rumah_tangga" id="pembantu_rumah_tangga" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Alat Elektronik di Rumah</span>
                    <select name="peralatan_elektronik[]" id="peralatan_elektronik" class="bg-white rounded-[20px] p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" multiple required>
                        <option value="Televisi">Televisi</option>
                        <option value="Radio">Radio</option>
                        <option value="Komputer/Laptop">Komputer/Laptop</option>
                        <option value="Smartphone">Smartphone</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Game Console">Game Console</option>
                        <option value="Smart TV">Smart TV</option>
                        <option value="Speaker Bluetooth">Speaker Bluetooth</option>
                    </select>
                    <p class="text-white text-sm mt-1">* Tekan CTRL (Windows) atau Command (Mac) untuk memilih lebih dari satu</p>
                </label>
                <div class="flex justify-between mt-12">
                    <button type="button" class="prev-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Previous</button>
                    <button type="button" class="next-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Next Step</button>
                    {{-- <button type="submit" class="font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Submit</button> --}}
                </div>
            </div>

            <!-- Step 4: Data Keterangan Peserta -->
            <div class="step-content hidden" data-step="5">
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Membaca</span>
                    <select name="keterangan_membaca" id="keterangan_membaca" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Judul Buku yang digunakan untuk berlatih membaca Latin</span>
                    <textarea name="judulbuku_berlatihmembaca_latin" id="judulbuku_berlatihmembaca_latin" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Judul Buku yang digunakan untuk berlatih membaca Latin"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Membaca Hijaiyah</span>
                    <select name="keterangan_membaca_hijaiyah" id="keterangan_membaca_hijaiyah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Judul Buku yang digunakan untuk berlatih membaca Hijaiyah</span>
                    <select name="judulbuku_berlatihmembaca_hijaiyah" id="judulbuku_berlatihmembaca_hijaiyah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Iqro">Iqro</option>
                        <option value="Al Quran">Al Quran</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah jilid berapa dari buku berlatih membaca Hijaiyah</span>
                    <textarea name="jilid_hijaiyah" id="jilid_hijaiyah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Sudah jilid berapa dari buku berlatih membaca Hijaiyah"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Menulis</span>
                    <select name="keterangan_menulis" id="keterangan_menulis" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mengenal Angka</span>
                    <select name="keterangan_angka" id="keterangan_angka" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Menghitung</span>
                    <select name="keterangan_menghitung" id="keterangan_menghitung" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Menggambar</span>
                    <select name="keterangan_menggambar" id="keterangan_menggambar" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Berwudhu</span>
                    <select name="keterangan_berwudhu" id="keterangan_berwudhu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Sudah Mampu Melakukan Tata Cara Shalat</span>
                    <select name="keterangan_tata_cara_shalat" id="keterangan_tata_carashalat" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Belum bisa">Belum bisa</option>
                        <option value="Sedikit bisa">Sedikit bisa</option>
                        <option value="Sudah mampu">Sudah mampu</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Keterangan Hafalan Juz Amma</span>
                    <textarea name="keterangan_hafalan_juz_ama" id="keterangan_hafalan_juz_ama" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan keterangan hafalan juz amma"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Keterangan Hafalan Murottal</span>
                    <textarea name="keterangan_hafalan_murottal" id="keterangan_hafalan_murottal" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan keterangan hafalan murottal"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Keterangan Hafalan Doa</span>
                    <textarea name="keterangan_hafalan_doa" id="keterangan_hafalan_doa" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan keterangan hafalan doa"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Keterangan Hafalan Surah</span>
                    <textarea name="keterangan_hafal_surat" id="keterangan_hafal_surat" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Sudah jilid berapa dari buku berlatih membaca Hijaiyah"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Hobi Anak</span>
                    <textarea name="hobi" id="hobi" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Sudah jilid berapa dari buku berlatih membaca Hijaiyah"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Cerita Islami yang sering di dengar anak</span>
                    <textarea name="keterangan_kisah_islami" id="keterangan_kisah_islami" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Sudah jilid berapa dari buku berlatih membaca Hijaiyah"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Anak Berlangganan majalah</span>
                    <textarea name="keterangan_majalah" id="keterangan_majalah" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Sudah jilid berapa dari buku berlatih membaca Hijaiyah"></textarea>
                </label>
                <div class="flex justify-between mt-12">
                    <button type="button" class="prev-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Previous</button>
                    <button type="button" class="next-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Next Step</button>
                </div>
            </div>

            <!-- Step 5: Data Pendanaan & Survey Peserta -->
            <div class="step-content hidden" data-step="6">
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Pemasukan Perbulan Orang Tua</span>
                    <select name="pemasukan_perbulan_orang_tua" id="pemasukan_perbulan_orang_tua" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" required>
                        <option value="" selected disabled>Pilih opsi</option>
                        <option value="Rp 500.000 < Rp. 1.500.000">Rp 500.000 < Rp. 1.500.000</option>
                        <option value="Rp. 1.500.000 < Rp. 2.500.000"> Rp. 1.500.000 < Rp. 2.500.000</option>
                        <option value="> Rp. 2.500.000"> > Rp. 2.500.000</option>
                    </select>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold"> 
                    <span class="text-white">Apabila ditengah perjalanan kegiatan belajar mengajar (KBM) terjadi kenaikan harga bahan pokok yang berimbas pada biaya operasional terutama konsumsi, maka Tindakan apa yang harus dilakukan agar menu makanan yang diberikan pada anak-anak tetap stabil (berikan alasan)</span>
                    <textarea name="keterangan_kenaikan_pendapatan" id="keterangan_kenaikan_pendapatan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan keterangan kenaikan pendapatan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Untuk mengatasi masalah kenaikan harga bahan pokok, bagaimana apabila orang tua/ wali murid yang mempunya kelebihan rezeki untuk menyisihkan hartanya/ berinfaq secara sukarela?(berikan alasan)</span>
                    <textarea name="keterangan_infaq" id="keterangan_infaq" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan keterangan infaq"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, peserta didik tidak boleh ditunggu orrang tua/wali/baby sitster kecuali awal masuk maksimal 2 pekan (berikan alasan)</span>
                    <textarea name="larangan_menunggu" id="larangan_menunggu" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, peserta didik dilarang memakai perhiasan kecuali anting atau giwang (Berikan alasan)</span>
                    <textarea name="larangan_perhiasan" id="larangan_perhiasan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju,orang tua wajib berpakaian Islami Ketika berada di lingkungan TKIT AL-Qolam (bagi ibu/penjemput putri di usahakan memakai jilbab). (berikan alasan)</span>
                    <textarea name="berpakaian_islami" id="berpakaian_islami" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, untuk menghadiri pertemuan wali murid 2 bulan sekali (berikan alasan)</span>
                    <textarea name="menghadiri_pertemuan_wali" id="menghadiri_pertemuan_wali" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, untuk kontrol_perkembangan (berikan alasan)</span>
                    <textarea name="kontrol_pengembangan" id="kontrol_pengembangan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, untuk larangan_merokok (berikan alasan)</span>
                    <textarea name="larangan_merokok" id="larangan_merokok" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, untuk tidak_bekerjasama (berikan alasan)</span>
                    <textarea name="tidak_bekerjasama" id="tidak_bekerjasama" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <label class="flex flex-col gap-[10px] font-semibold">
                    <span class="text-white">Setuju atau tidak setuju, untuk penjadwalan (berikan alasan)</span>
                    <textarea name="penjadwalan" id="penjadwalan" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan alasan"></textarea>
                </label>
                <div class="flex justify-between mt-12">
                    <button type="button" class="prev-step font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Previous</button>
                     <button type="submit" class="font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Submit</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Tambahkan script di bawah ini -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('multiStepForm');
    const steps = document.querySelectorAll('.step-content');
    const stepIndicators = document.querySelectorAll('.step');
    let currentStep = 1;

    function validateStep(stepNumber) {
        const currentInputs = steps[stepNumber-1].querySelectorAll('input:not([type="hidden"]), select, textarea');
        let isValid = true;
        
        currentInputs.forEach(input => {
            if (input.closest('#waliForm') && document.getElementById('waliForm').classList.contains('hidden')) {
                return;
            }
            
            if (input.required && !input.value) {
                isValid = false;
                input.reportValidity();
            }
        });
        
        return isValid;
    }

    function updateStepStyles(newStep) {
        stepIndicators.forEach((indicator, index) => {
            const stepCircle = indicator.querySelector('.step-circle');
            
            // Reset semua step ke default style
            indicator.classList.remove('active', 'completed');
            stepCircle.classList.remove('bg-portto-purple', 'text-white');
            stepCircle.classList.add('bg-white', 'text-portto-purple');
            
            // Set style untuk step yang aktif
            if (index + 1 === newStep) {
                indicator.classList.add('active');
                stepCircle.classList.remove('bg-white', 'text-portto-purple');
                stepCircle.classList.add('bg-portto-purple', 'text-white');
            }
        });
    }

    function changeStep(newStep) {
        if (!validateStep(currentStep)) {
            return;
        }

        // Sembunyikan step saat ini
        steps[currentStep-1].classList.add('hidden');
        
        // Tampilkan step baru
        steps[newStep-1].classList.remove('hidden');
        
        // Update styles untuk indicators
        updateStepStyles(newStep);
        
        currentStep = newStep;
    }

    // Event listener untuk indikator step yang dapat diklik
    stepIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            const targetStep = index + 1;
            if (targetStep !== currentStep) {
                changeStep(targetStep);
            }
        });
    });

    // Next button handler
    form.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < steps.length) {
                changeStep(currentStep + 1);
            }
        });
    });

    // Previous button handler
    form.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 1) {
                changeStep(currentStep - 1);
            }
        });
    });

    // Form submit handler
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah pengiriman form default

        if (!validateStep(currentStep)) {
            return;
        }

        // Tampilkan loading indicator
        Swal.fire({
            title: 'Mohon Tunggu',
            text: 'Sedang memproses pendaftaran...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json()) // Ambil JSON dari respons
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4920E5'
                }).then(() => {
                    window.location.href = '/'; // Arahkan ke halaman awal setelah menutup alert
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat memproses pendaftaran',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4920E5'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengirim data',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4920E5'
            });
        });
    });

    // Inisialisasi tampilan awal
    updateStepStyles(currentStep);

    // Tambahkan script untuk menangani form wali
    const hasWaliSelect = document.getElementById('has_wali');
    const waliForm = document.getElementById('waliForm');
    const waliInputs = waliForm.querySelectorAll('input');

    hasWaliSelect.addEventListener('change', function() {
        if (this.value === 'ya') {
            waliForm.classList.remove('hidden');
            // Aktifkan validasi untuk input wali
            waliInputs.forEach(input => {
                input.required = true;
            });
        } else {
            waliForm.classList.add('hidden');
            // Nonaktifkan validasi untuk input wali
            waliInputs.forEach(input => {
                input.required = false;
                input.value = ''; // Reset nilai input
            });
        }
    });

    // Ubah validasi kode pendaftaran untuk menggunakan SweetAlert
    const kodeInput = document.getElementById('kode_pendaftaran_id');
    const kodeError = document.getElementById('kode_error');

    kodeInput.addEventListener('blur', async function() {
        let kode = this.value;
        if (kode) {
            if (kode.startsWith('#')) {
                kode = kode.substring(1);
            }
            
            try {
                const encodedKode = encodeURIComponent(kode);
                const response = await fetch(`/api/check-kode/${encodedKode}`);
                const data = await response.json();
                
                if (!data.valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nomor Urut Tidak Valid',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4920E5'
                    });
                    this.setCustomValidity(data.message);
                    kodeError.classList.remove('hidden');
                } else {
                    kodeError.classList.add('hidden');
                    this.setCustomValidity('');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memeriksa Nomor Urut',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4920E5'
                });
            }
        }
    });

    const hasPenyakitSelect = document.getElementById('has_penyakit');
    const penyakitDetails = document.getElementById('penyakitDetails');

    hasPenyakitSelect.addEventListener('change', function() {
        if (this.value === 'ya') {
            penyakitDetails.classList.remove('hidden'); // Tampilkan detail penyakit
        } else {
            penyakitDetails.classList.add('hidden'); // Sembunyikan detail penyakit
        }
    });
});

function updateNamaSaudaraForms() {
    const jumlahSaudaraTiri = document.getElementById('jumlah_saudara_tiri').value;
    const jumlahSaudaraKandung = document.getElementById('jumlah_saudara_kandung').value;
    const namaSaudaraContainer = document.getElementById('namaSaudaraContainer');

    // Menghitung total saudara
    const totalSaudara = parseInt(jumlahSaudaraTiri) + parseInt(jumlahSaudaraKandung);
    
    // Menghapus form nama saudara yang ada
    namaSaudaraContainer.innerHTML = '';

    if (totalSaudara > 0) {
        namaSaudaraContainer.classList.remove('hidden');
        for (let i = 0; i < totalSaudara; i++) {
            const label = document.createElement('label');
            label.className = 'flex flex-col gap-[10px] font-semibold';
            label.innerHTML = `
                <span class="text-white">Nama Saudara ${i + 1}</span>
                <input type="text" name="nama_saudara" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama saudara">
                <span class="text-white">Hubungan Saudara ${i + 1}</span>
                <input type="text" name="hubungan_saudara" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan hubungan saudara">
                <span class="text-white">Umur Saudara ${i + 1}</span>
                <input type="text" name="umur_saudara" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukan nama umur saudara">
            `;
            namaSaudaraContainer.appendChild(label);
        }
    } else {
        namaSaudaraContainer.classList.add('hidden');
    }
}
</script>
@endpush

@push('styles')
<style>
/* Base styles */
#multiStepForm {
    width: 100%;
    max-width: 550px;
    margin: 0 auto;
}

/* Grid container untuk desktop */
.grid.grid-cols-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    width: 100%;
}

/* Container utama */
.w-full.lg\:w-1/2 {
    width: 50%; /* Tetapkan lebar 50% untuk desktop */
    max-width: 550px;
    margin: 0 auto;
}

/* Form content */
.step-content {
    width: 100%;
}

/* Input fields */
input[type="text"],
input[type="email"],
input[type="date"],
input[type="number"],
select,
textarea {
    width: 100%;
    border-radius: 999px;
    padding: 14px 30px;
}

/* Responsive styles */
@media (max-width: 1024px) {
    .w-full.lg\:w-1/2 {
        width: 100%; /* Full width untuk mobile */
        padding: 0 1rem;
    }

    .grid.grid-cols-2 {
        grid-template-columns: 1fr; /* Single column untuk mobile */
        gap: 1rem;
    }

    #multiStepForm {
        padding: 0;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="number"],
    select,
    textarea {
        padding: 12px 20px;
        font-size: 14px;
    }

    .step-content {
        padding: 1rem;
    }

    .flex.flex-col.gap-[10px] {
        margin-bottom: 1rem;
    }
}

/* Desktop-specific styles */
@media (min-width: 1025px) {
    #Content {
        display: flex;
        justify-content: center;
    }

    .w-full.lg\:w-1/2 {
        width: 50%;
        padding: 0 2rem;
    }

    .step-content {
        max-width: 550px;
        margin: 0 auto;
    }
}

/* Button container */
.flex.justify-between.mt-12 {
    width: 100%;
    gap: 1rem;
    margin-top: 3rem;
}

/* Responsive buttons */
@media (max-width: 480px) {
    .flex.justify-between.mt-12 {
        flex-direction: column;
    }

    button {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Tambahkan style untuk responsif */
@media (max-width: 1024px) {
    #Content {
        flex-direction: column;
    }
    
    .lg\:w-1/2 {
        width: 100%;
    }
    
    .w-[550px] {
        width: 100%;
    }
    
    /* Sesuaikan padding untuk mobile */
    .p-[30px_40px] {
        padding: 20px;
    }
}

/* Style untuk form agar hidden di tampilan web */
@media (min-width: 1025px) {
    .form-header {
        display: none;
    }
}

/* Style untuk container form agar responsif */
.flex.flex-col.gap-[50px] {
    width: 100%;
    max-width: 550px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Sesuaikan ukuran form untuk mobile */
@media (max-width: 640px) {
    .w-[550px] {
        width: 100%;
    }
    
    .step-content {
        width: 100%;
    }
    
    /* Sesuaikan ukuran progress indicator */
    .flex.justify-between.w-[550px] {
        width: 100%;
        padding: 0 1rem;
    }
}

@media (max-width: 1024px) {
    #Content > div:first-child {
        min-height: 400px; /* Atur tinggi minimum untuk layar mobile */
    }
}

.flex.justify-between.w-full {
    justify-content: center; /* Pusatkan tombol step */
}

.step {
    flex: 1; /* Membuat setiap step memiliki lebar yang sama */
    max-width: 100px; /* Atur lebar maksimum untuk setiap step */
    position: relative; /* Untuk memastikan garis step berada di posisi yang benar */
}

.step::after {
    display: none; /* Menghilangkan garis lurus di bawah step */
    left: 50%; /* Pusatkan garis step */
    transform: translateX(-50%); /* Pusatkan garis step */
    width: 100%; /* Sesuaikan lebar garis step */
}

@media (max-width: 640px) {
    .step {
        max-width: 80px; /* Sesuaikan lebar maksimum untuk tampilan mobile */
    }
}

/* Tambahkan atau update style untuk container tombol */
.flex.justify-end.mt-12,
.flex.justify-between.mt-12 {
    margin-top: 3rem;      /* Jarak dari form */
    margin-bottom: 2rem;   /* Jarak ke footer */
    padding: 1rem 0;       /* Padding container tombol */
    display: flex;
    justify-content: space-between; /* Membuat jarak antara Previous dan Next */
    width: 100%;           /* Memastikan container mengambil lebar penuh */
}

/* Style untuk tombol Previous dan Next Step */
.prev-step,
.next-step,
button[type="submit"] {
    padding: 1.25rem 2rem;  /* Padding tombol */
    border-radius: 20px;    /* Border radius */
    transition: all 0.3s ease; /* Animasi hover */
    min-width: 120px;       /* Lebar minimum tombol */
}

/* Style untuk container form */
.step-content {
    padding-bottom: 2rem;   /* Padding bawah konten */
}

/* Style untuk container judul */
.form-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 2rem;
    width: 100%;
}

.form-header h1 {
    font-size: 24px;
    margin-top: 1.5rem;
    font-weight: bold;
}

.form-header p {
    margin-top: 0.5rem;
    color: #fff;
}

/* Responsive styles */
@media (max-width: 768px) {
    .form-header {
        margin-bottom: 1.5rem;
    }
    
    .form-header .logo-container {
        max-width: 400px;
    }

    .form-header h1 {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .form-header {
        margin-bottom: 1rem;
    }
    
    .form-header .logo-container {
        max-width: 350px;
    }

    .form-header h1 {
        font-size: 18px;
    }
}
</style>
@endpush
@endsection