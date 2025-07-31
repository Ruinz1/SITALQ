<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran Siswa Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #3B82F6;
            --primary-dark: #2563EB;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
        }

        .step-active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-color: var(--primary);
        }

        .step-completed {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        .step-inactive {
            background: white;
            color: var(--gray-500);
            border-color: var(--gray-300);
        }

        .progress-line {
            height: 2px;
            background: var(--gray-200);
            transition: all 0.3s ease;
        }

        .progress-line.completed {
            background: var(--success);
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .file-upload-area {
            border: 2px dashed var(--gray-300);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }

        .file-upload-area.dragover {
            border-color: var(--primary);
            background: var(--gray-50);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--gray-400);
            background: var(--gray-50);
        }

        .btn-success {
            background: var(--success);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .input-error {
            border-color: var(--danger) !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .border-green-500 {
            border-color: var(--success) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .error-message {
            color: var(--danger);
            font-size: 14px;
            margin-top: 4px;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-left: 4px solid var(--success);
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .toast.show {
            transform: translateX(0);
        }

        .confirmation-card {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .confirmation-section {
            margin-bottom: 20px;
        }

        .confirmation-section h4 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 12px;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 4px;
            display: inline-block;
        }

        .confirmation-item {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .confirmation-label {
            color: var(--gray-600);
            width: 40%;
            flex-shrink: 0;
        }

        .confirmation-value {
            color: var(--gray-800);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-wrap: wrap;
                gap: 8px;
            }
            
            .step-item {
                flex: 1;
                min-width: calc(50% - 4px);
            }

            .progress-line {
                display: none;
            }

            .confirmation-item {
                flex-direction: column;
                margin-bottom: 12px;
            }

            .confirmation-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 text-center">
                <h1 class="text-3xl font-bold mb-2">Formulir Pendaftaran Siswa Baru</h1>
                <p class="text-blue-100">Lengkapi semua data dengan benar dan teliti</p>
            </div>

            <!-- Progress Indicator -->
            <div class="p-6 pb-0">
                <div class="flex items-center justify-between step-indicator" id="stepIndicator">
                    <!-- Step indicators will be generated by JavaScript -->
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2" id="stepTitle">Data Peserta</h2>
                    <p class="text-gray-600 text-sm" id="stepDescription">Langkah 1 dari 6</p>
                </div>

                <form id="registrationForm" action="{{ route('peserta.store') }}" method="POST" >
                    @csrf
                    <!-- Step 1: Data Siswa -->
                    <div class="form-step active" data-step="1">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="nama_peserta" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                                    <input type="text" id="nama_peserta" name="nama_peserta" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan nama lengkap">
                                    <div class="error-message" id="nama_peserta-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="kode_pendaftaran_id" class="block text-sm font-medium text-gray-700">Kode Pendaftaran *</label>
                                    <input type="text" id="kode_pendaftaran_id" name="kode_pendaftaran_id" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Kode Pendaftaran"
                                           oninput="checkKodePendaftaran(this.value)"
                                           onblur="checkKodePendaftaran(this.value)">
                                    <div class="error-message" id="kode_pendaftaran_id-error"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                    <input type="email" id="email" name="email" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan email">
                                    <div class="error-message" id="email-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat *</label>
                                    <input type="text" id="alamat" name="alamat" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="Masukkan Alamat">
                                    <div class="error-message" id="alamat-error"></div>
                                </div>
                                
                            </div>
                            <!-- Jenis Kelamin -->
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <div class="error-message" id="jenis_kelamin-error"></div>
                            </div>
                            <div class="space-y-2">
                                    <label for="agama" class="block text-sm font-medium text-gray-700">Agama *</label>
                                    <input type="text" id="agama" name="agama" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Agama">
                                    <div class="error-message" id="agama-error"></div>
                                </div>
                            </div>
                            <!-- #endregion Tanggal & Tempat Lahir --> 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir *</label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan tempat lahir">
                                    <div class="error-message" id="tempat_lahir-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir *</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <div class="error-message" id="tanggal_lahir-error"></div>
                                </div>
                            </div>
                             <!-- Nama Panggilan & Bahasa Sehari --> 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="nama_panggilan" class="block text-sm font-medium text-gray-700">Nama Panggilan *</label>
                                    <input type="text" id="nama_panggilan" name="nama_panggilan" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Nama Panggilan">
                                    <div class="error-message" id="nama_panggilan-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="bahasa_sehari" class="block text-sm font-medium text-gray-700">Bahasa yang digunakan sehari *</label>
                                    <input type="text" id="bahasa_sehari" name="bahasa_sehari" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Nama Panggilan">
                                    <div class="error-message" id="bahasa_sehari-error"></div>
                                </div>
                            </div>
                            <!-- Tinggi & Berat Badan --> 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="tinggi_badan" class="block text-sm font-medium text-gray-700">Tinggi Badan *</label>
                                    <input type="number" id="tinggi_badan" name="tinggi_badan" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Tinggi Badan">
                                    <div class="error-message" id="tinggi_badan-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="berat_badan" class="block text-sm font-medium text-gray-700">Berat Badan *</label>
                                    <input type="number" id="berat_badan" name="berat_badan" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Berat Badan">
                                    <div class="error-message" id="berat_badan-error"></div>
                                </div>
                            </div>
                            <!-- Jumlah Saudara Tiri & Kandung --> 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="jumlah_saudara_tiri" class="block text-sm font-medium text-gray-700">Jumlah Saudara Tiri (Opsional)</label>
                                    <input type="number" id="jumlah_saudara_tiri" name="jumlah_saudara_tiri" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Jumlah Saudara Tiri" min="0" onchange="updateSiblingForm()" oninput="updateSiblingForm()">
                                    <div class="error-message" id="jumlah_saudara_tiri-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="jumlah_saudara_kandung" class="block text-sm font-medium text-gray-700">Jumlah Saudara Kandung *</label>
                                    <input type="number" id="jumlah_saudara_kandung" name="jumlah_saudara_kandung" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Jumlah Saudara Kandung" min="0" onchange="updateSiblingForm()" oninput="updateSiblingForm()">
                                    <div class="error-message" id="jumlah_saudara_kandung-error"></div>
                                </div>
                            </div>
                            
                            <!-- Dynamic Sibling Form Section -->
                            <div id="sibling-form-section" class="hidden" style="transition: opacity 0.3s ease-in-out;">
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Saudara Kandung & Tiri</h3>
                                    <div id="sibling-form-container" class="space-y-4">
                                        <!-- Dynamic sibling forms will be generated here -->
                                    </div>
                                </div>
                            </div>
                            <!-- Anak ke & Toilet Training  --> 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="anak_ke" class="block text-sm font-medium text-gray-700">Anak ke *</label>
                                    <input type="number" id="anak_ke" name="anak_ke" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Anak ke berapa">
                                    <div class="error-message" id="anak_ke-error"></div>
                                </div>
                                <div class="space-y-2">
                                <label for="toilet_traning" class="block text-sm font-medium text-gray-700">Toilet Training *</label>
                                <textarea id="toilet_traning" name="toilet_traning" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Apakah Anak Sudah Mampu Untuk Pergi Toilet Sendiri"></textarea>
                                <div class="error-message" id="toilet_traning-error"></div>
                            </div>
                            </div>
                            <!-- Penyakit -->
                            <div class="space-y-2">
                                <label for="has_penyakit" class="block text-sm font-medium text-gray-700">Apakah memiliki Penyakit yang Diderita dengan perawatan *</label>
                                <select id="has_penyakit" name="has_penyakit" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        onchange="updatePenyakitForm()">
                                    <option value="">Pilih Opsi</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                                <div class="error-message" id="has_penyakit-error"></div>
                            </div>
                            
                            <!-- Dynamic Disease Form Section -->
                            <div id="penyakit-form-section" class="hidden" style="transition: opacity 0.3s ease-in-out;">
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Penyakit</h3>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label for="penyakit_berapalama" class="block text-sm font-medium text-gray-700">Berapa Lama Menderita Penyakit *</label>
                                                <input type="text" id="penyakit_berapalama" name="penyakit_berapalama" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                       placeholder="Contoh: 2 tahun, 6 bulan">
                                                <div class="error-message" id="penyakit_berapalama-error"></div>
                                            </div>
                                            <div class="space-y-2">
                                                <label for="penyakit_kapan" class="block text-sm font-medium text-gray-700">Kapan Mulai Menderita *</label>
                                                <input type="text" id="penyakit_kapan" name="penyakit_kapan" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                       placeholder="Contoh: Januari 2023">
                                                <div class="error-message" id="penyakit_kapan-error"></div>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label for="penyakit_pantangan" class="block text-sm font-medium text-gray-700">Pantangan Makanan/Minuman *</label>
                                            <textarea id="penyakit_pantangan" name="penyakit_pantangan" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                      placeholder="Jelaskan pantangan makanan atau minuman yang harus dihindari"></textarea>
                                            <div class="error-message" id="penyakit_pantangan-error"></div>
                                        </div>
                                        <div class="space-y-2">
                                            <label for="mempunyai_alergi" class="block text-sm font-medium text-gray-700">Apakah Mempunyai Alergi *</label>
                                            <select id="mempunyai_alergi" name="mempunyai_alergi" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Pilih Opsi</option>
                                                <option value="ya">Ya</option>
                                                <option value="tidak">Tidak</option>
                                            </select>
                                            <div class="error-message" id="mempunyai_alergi-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                               <label class="block text-sm font-medium text-gray-700">Upload Tanda Tangan Digital Orang Tua *</label>
                                    <div class="file-upload-area" onclick="document.getElementById('tanda_tangan').click()">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600" id="tanda_tangan-label">Pilih file untuk Tanda Tangan</p>
                                        <p class="text-xs text-gray-500 mt-1">Format: Wajib PNG (max 2MB)</p>
                                    </div>
                                    <input type="file" id="tanda_tangan" name="tanda_tangan" accept="image/*" class="hidden" onchange="handleFileSelect(this, 'tanda_tangan-label')">
                                    <div class="error-message" id="tanda_tangan-error"></div>
                                
                            </div>

                        </div>
                    </div>

                    <!-- Step 2: Data Pendahuluan -->
                    <div class="form-step" data-step="2">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label for="latar_belakang" class="block text-sm font-medium text-gray-700">Latar Belakang Pendaftaran *</label>
                                <textarea id="latar_belakang" name="latar_belakang" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Masukkan Latar Belakang anda ingin memasukan anak anda ke TKIT Al Qolam"></textarea>
                                <div class="error-message" id="latar_belakang-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="harapan_keislaman" class="block text-sm font-medium text-gray-700">Harapan Bidang Keislaman *</label>
                                <textarea id="harapan_keislaman" name="harapan_keislaman" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Masukkan Harapan anda ingin memasukan anak ke TKIT Al Qolam dalam bidang Keislaman"></textarea>
                                <div class="error-message" id="harapan_keislaman-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="harapan_keilmuan" class="block text-sm font-medium text-gray-700">Harapan Bidang Keilmuan *</label>
                                <textarea id="harapan_keilmuan" name="harapan_keilmuan" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Masukkan Harapan anda ingin memasukan anak ke TKIT Al Qolam dalam bidang Keilmuan"></textarea>
                                <div class="error-message" id="harapan_keilmuan-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="harapan_sosial" class="block text-sm font-medium text-gray-700">Harapan Bidang Sosial *</label>
                                <textarea id="harapan_sosial" name="harapan_sosial" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Masukkan Harapan anda ingin memasukan anak ke TKIT Al Qolam dalam bidang Sosial"></textarea>
                                <div class="error-message" id="harapan_sosial-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="berapa_lama_bersekolah" class="block text-sm font-medium text-gray-700">Berapa Lama Bapak/Ibu Berencana Menyekolahkan Anak di TKIT AL-Qolam ? *</label>
                                <select id="berapa_lama_bersekolah" name="berapa_lama_bersekolah" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="1">1 Tahun</option>
                                    <option value="2">2 Tahun</option>
                                    <option value="3">3 Tahun</option>
                                </select>
                                <div class="error-message" id="berapa_lama_bersekolah-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Data Orang Tua/Wali -->
                    <div class="form-step" data-step="3">
                        <div class="space-y-6">
                            <!-- Nama Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="nama_ayah" class="block text-sm font-medium text-gray-700">Nama Ayah *</label>
                                    <input type="text" id="nama_ayah" name="nama_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan nama ayah">
                                    <div class="error-message" id="nama_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="nama_ibu" class="block text-sm font-medium text-gray-700">Nama Ibu *</label>
                                    <input type="text" id="nama_ibu" name="nama_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan nama ibu">
                                    <div class="error-message" id="nama_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Agama Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="agama_ayah" class="block text-sm font-medium text-gray-700">Agama Ayah *</label>
                                    <input type="text" id="agama_ayah" name="agama_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Agama ayah">
                                    <div class="error-message" id="agama_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="agama_ibu" class="block text-sm font-medium text-gray-700">Agama Ibu *</label>
                                    <input type="text" id="agama_ibu" name="agama_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Agama ibu">
                                    <div class="error-message" id="agama_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Tempat Lahir Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="tempat_lahir_ayah" class="block text-sm font-medium text-gray-700">Tempat Lahir Ayah *</label>
                                    <input type="text" id="tempat_lahir_ayah" name="tempat_lahir_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Tempat Lahir ayah">
                                    <div class="error-message" id="tempat_lahir_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="tempat_lahir_ibu" class="block text-sm font-medium text-gray-700">Tempat Lahir Ibu *</label>
                                    <input type="text" id="tempat_lahir_ibu" name="tempat_lahir_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Tempat Lahir ibu">
                                    <div class="error-message" id="tempat_lahir_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Tanggal Lahir Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="tanggal_lahir_ayah" class="block text-sm font-medium text-gray-700">Tanggal Lahir Ayah *</label>
                                    <input type="date" id="tanggal_lahir_ayah" name="tanggal_lahir_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Tanggal Lahir ayah">
                                    <div class="error-message" id="tanggal_lahir_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="tanggal_lahir_ibu" class="block text-sm font-medium text-gray-700">Tanggal Lahir Ibu *</label>
                                    <input type="date" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Tanggal Lahir ibu">
                                    <div class="error-message" id="tanggal_lahir_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Alamat Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="alamat_ayah" class="block text-sm font-medium text-gray-700">Alamat Ayah *</label>
                                    <input type="text" id="alamat_ayah" name="alamat_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Alamat ayah">
                                    <div class="error-message" id="alamat_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="alamat_ibu" class="block text-sm font-medium text-gray-700">Alamat Ibu *</label>
                                    <input type="text" id="alamat_ibu" name="alamat_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Alamat ibu">
                                    <div class="error-message" id="alamat_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Alamat Kantor Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="alamat_kantor_ayah" class="block text-sm font-medium text-gray-700">Alamat Kantor Ayah </label>
                                    <input type="text" id="alamat_kantor_ayah" name="alamat_kantor_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Alamat Kantor ayah">
                                    <div class="error-message" id="alamat_kantor_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="alamat_kantor_ibu" class="block text-sm font-medium text-gray-700">Alamat Kantor Ibu </label>
                                    <input type="text" id="alamat_kantor_ibu" name="alamat_kantor_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Alamat Kantor ibu">
                                    <div class="error-message" id="alamat_kantor_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Pendidikan Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="pendidikan_terakhir_ayah" class="block text-sm font-medium text-gray-700">Pendidikan Terakhir Ayah *</label>
                                    <select id="pendidikan_terakhir_ayah" name="pendidikan_terakhir_ayah" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                    <div class="error-message" id="pendidikan_terakhir_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="pendidikan_terakhir_ibu" class="block text-sm font-medium text-gray-700">Pendidikan Terakhir Ibu *</label>
                                    <select id="pendidikan_terakhir_ibu" name="pendidikan_terakhir_ibu" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                    </select>
                                    <div class="error-message" id="pendidikan_terakhir_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Pekerjaan Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700">Pekerjaan Ayah *</label>
                                    <select id="pekerjaan_ayah" name="pekerjaan_ayah" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="PNS">PNS</option>
                                        <option value="TNI/POLRI">TNI/POLRI</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div class="error-message" id="pekerjaan_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700">Pekerjaan Ibu *</label>
                                    <select id="pekerjaan_ibu" name="pekerjaan_ibu" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="PNS">PNS</option>
                                        <option value="IRT">Ibu Rumah Tangga</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div class="error-message" id="pekerjaan_ibu-error"></div>
                                </div>
                            </div>

                            <!-- No HP Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="no_hp_ayah" class="block text-sm font-medium text-gray-700">No HP Ayah </label>
                                    <input type="text" id="no_hp_ayah" name="no_hp_ayah" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan No HP ayah">
                                    <div class="error-message" id="no_hp_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="no_hp_ibu" class="block text-sm font-medium text-gray-700">No HP Ibu </label>
                                    <input type="text" id="no_hp_ibu" name="no_hp_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan No HP ibu">
                                    <div class="error-message" id="no_hp_ibu-error"></div>
                            </div>
                            </div>

                            <!-- Sosial Media Ayah dan Ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="sosmed_ayah" class="block text-sm font-medium text-gray-700">Sosial Media Ayah </label>
                                    <input type="text" id="sosmed_ayah" name="sosmed_ayah" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Sosial Media ayah">
                                    <div class="error-message" id="sosmed_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="sosmed_ibu" class="block text-sm font-medium text-gray-700">Sosial Media Ibu </label>
                                    <input type="text" id="sosmed_ibu" name="sosmed_ibu" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Sosial Media ibu">
                                    <div class="error-message" id="sosmed_ibu-error"></div>
                                </div>
                            </div>
                           
                            <div class="space-y-2">
                                <label for="has_wali" class="block text-sm font-medium text-gray-700">Mempunyai Wali *</label>
                                <select id="has_wali" name="has_wali" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="updateWaliForm()">
                                    <option value="">Pilih Opsi</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                                <div class="error-message" id="has_wali-error"></div>
                            </div>
                            
                            <!-- Dynamic Wali Form Section -->
                            <div id="wali-form-section" class="hidden" style="transition: opacity 0.3s ease-in-out;">
                                <div class="border-t border-gray-200 pt-6 mt-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Wali</h3>
                                    <div class="space-y-4">
                                        <!-- Nama Wali -->
                                        <div class="space-y-2">
                                            <label for="nama_wali" class="block text-sm font-medium text-gray-700">Nama Wali *</label>
                                            <input type="text" id="nama_wali" name="nama_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan nama wali">
                                            <div class="error-message" id="nama_wali-error"></div>
                                        </div>

                                        <!-- Agama Wali -->
                                        <div class="space-y-2">
                                            <label for="agama_wali" class="block text-sm font-medium text-gray-700">Agama Wali *</label>
                                            <input type="text" id="agama_wali" name="agama_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan Agama wali">
                                            <div class="error-message" id="agama_wali-error"></div>
                                        </div>

                                        <!-- Tempat Lahir Wali -->
                                        <div class="space-y-2">
                                            <label for="tempat_lahir_wali" class="block text-sm font-medium text-gray-700">Tempat Lahir Wali *</label>
                                            <input type="text" id="tempat_lahir_wali" name="tempat_lahir_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan Tempat Lahir wali">
                                            <div class="error-message" id="tempat_lahir_wali-error"></div>
                                        </div>

                                        <!-- Tanggal Lahir Wali -->
                                        <div class="space-y-2">
                                            <label for="tanggal_lahir_wali" class="block text-sm font-medium text-gray-700">Tanggal Lahir Wali *</label>
                                            <input type="date" id="tanggal_lahir_wali" name="tanggal_lahir_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan Tanggal Lahir wali">
                                            <div class="error-message" id="tanggal_lahir_wali-error"></div>
                                        </div>

                                        <!-- Alamat Wali -->
                                        <div class="space-y-2">
                                            <label for="alamat_wali" class="block text-sm font-medium text-gray-700">Alamat Wali *</label>
                                            <input type="text" id="alamat_wali" name="alamat_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan Alamat wali">
                                            <div class="error-message" id="alamat_wali-error"></div>
                                        </div>

                                        <!-- Alamat Kantor Wali -->
                                        <div class="space-y-2">
                                            <label for="alamat_kantor_wali" class="block text-sm font-medium text-gray-700">Alamat Kantor Wali *</label>
                                            <input type="text" id="alamat_kantor_wali" name="alamat_kantor_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan Alamat Kantor wali">
                                            <div class="error-message" id="alamat_kantor_wali-error"></div>
                                        </div>

                                        <!-- Hubungan dengan Wali -->
                                        <div class="space-y-2">
                                            <label for="hubungan_wali" class="block text-sm font-medium text-gray-700">Hubungan dengan Wali *</label>
                                            <select id="hubungan_wali" name="hubungan_wali" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Pilih Hubungan</option>
                                                <option value="Kakek">Kakek</option>
                                                <option value="Nenek">Nenek</option>
                                                <option value="Paman">Paman</option>
                                                <option value="Bibi">Bibi</option>
                                                <option value="Kakak">Kakak</option>
                                                <option value="Adik">Adik</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                            <div class="error-message" id="hubungan_wali-error"></div>
                                        </div>
                                        
                                        <!-- Pendidikan Terakhir Wali -->
                                        <div class="space-y-2">
                                            <label for="pendidikan_terakhir_wali" class="block text-sm font-medium text-gray-700">Pendidikan Terakhir Wali *</label>
                                            <select id="pendidikan_terakhir_wali" name="pendidikan_terakhir_wali" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Pilih Opsi</option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA/SMK">SMA/SMK</option>
                                                <option value="D3">D3</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                                <option value="S3">S3</option>
                                            </select>
                                            <div class="error-message" id="pendidikan_terakhir_wali-error"></div>
                                        </div>
                                        
                                        <!-- Pekerjaan Wali -->
                                        <div class="space-y-2">
                                            <label for="pekerjaan_wali" class="block text-sm font-medium text-gray-700">Pekerjaan Wali *</label>
                                            <select id="pekerjaan_wali" name="pekerjaan_wali" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Pilih Opsi</option>
                                                <option value="PNS">PNS</option>
                                                <option value="TNI/POLRI">TNI/POLRI</option>
                                                <option value="Wiraswasta">Wiraswasta</option>
                                                <option value="IRT">Ibu Rumah Tangga</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                            <div class="error-message" id="pekerjaan_wali-error"></div>
                                        </div>
                                        
                                        <!-- No HP Wali -->
                                        <div class="space-y-2">
                                            <label for="no_hp_wali" class="block text-sm font-medium text-gray-700">No HP Wali *</label>
                                            <input type="text" id="no_hp_wali" name="no_hp_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan No HP wali">
                                            <div class="error-message" id="no_hp_wali-error"></div>
                                        </div>
                                        
                                        <!-- Sosial Media Wali -->
                                        <div class="space-y-2">
                                            <label for="sosmed_wali" class="block text-sm font-medium text-gray-700">Sosial Media Wali</label>
                                            <input type="text" id="sosmed_wali" name="sosmed_wali" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                   placeholder="Masukkan Sosial Media wali">
                                            <div class="error-message" id="sosmed_wali-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Data Informasi Peserta -->
                    <div class="form-step" data-step="4">
                        <div class="space-y-6">
                            <!-- Tinggal Bersama & Halaman Bermain -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="tinggal_bersama" class="block text-sm font-medium text-gray-700">Tinggal Bersama *</label>
                                    <select id="tinggal_bersama" name="tinggal_bersama" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Keluarga Sendiri">Keluarga Sendiri</option>
                                        <option value="Keluarga Orang Lain">Keluarga Orang Lain</option>
                                    </select>
                                    <div class="error-message" id="tinggal_bersama-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="halaman_bermain" class="block text-sm font-medium text-gray-700">Halaman Bermain *</label>
                                    <select id="halaman_bermain" name="halaman_bermain" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Ada">Ada</option>
                                    <option value="Tidak Ada">Tidak Ada</option>
                                    </select>
                                    <div class="error-message" id="halaman_bermain-error"></div>
                                </div>
                            </div>

                            <!-- Jumlah penghuni rumah -->
                            <div class="space-y-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="jumlah_penghuni_dewasa" class="block text-sm font-medium text-gray-700">Jumlah Penghuni Dewasa di Rumah *</label>
                                        <input type="number" id="jumlah_penghuni_dewasa" name="jumlah_penghuni_dewasa" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan Jumlah Penghuni Dewasa di Rumah">
                            </div>
                            <div class="space-y-2">
                                        <label for="jumlah_penghuni_anak" class="block text-sm font-medium text-gray-700">Jumlah Penghuni Anak di Rumah *</label>
                                        <input type="number" id="jumlah_penghuni_anak" name="jumlah_penghuni_anak" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Masukkan Jumlah Penghuni Anak di Rumah">
                            </div>
                                </div>
                            </div>

                            <!-- Kepatuhan kepada orang tua -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="kepatuhan_anak" class="block text-sm font-medium text-gray-700">Kepatuhan kepada orang tua *</label>
                                    <select id="kepatuhan_anak" name="kepatuhan_anak" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Cukup">Cukup</option>
                                        <option value="Kurang">Kurang</option>
                                    </select>
                                    <div class="error-message" id="kepatuhan_anak-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="pergaulan_dengan_sebaya" class="block text-sm font-medium text-gray-700">Pergaulan dengan sebaya *</label>
                                    <select id="pergaulan_dengan_sebaya" name="pergaulan_dengan_sebaya" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Pasif">Pasif</option>
                                    </select>
                                    <div class="error-message" id="pergaulan_dengan_sebaya-error"></div>
                                </div>
                            </div>
                            
                            <!-- Hubungan dengan ayah dan ibu -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="hubungan_dengan_ayah" class="block text-sm font-medium text-gray-700">Hubungan dengan ayah *</label>
                                    <select id="hubungan_dengan_ayah" name="hubungan_dengan_ayah" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Cukup">Cukup</option>
                                        <option value="Kurang">Kurang</option>
                                    </select>
                                    <div class="error-message" id="hubungan_dengan_ayah-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="hubungan_dengan_ibu" class="block text-sm font-medium text-gray-700">Hubungan dengan ibu *</label>
                                    <select id="hubungan_dengan_ibu" name="hubungan_dengan_ibu" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup">Cukup</option>
                                    <option value="Kurang">Kurang</option>
                                    </select>
                                    <div class="error-message" id="hubungan_dengan_ibu-error"></div>
                                </div>
                            </div>

                            <!-- Kemampuan Buang Air & Kebiasaan Ngompol -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="kemampuan_buang_air" class="block text-sm font-medium text-gray-700">Kemampuan Buang Air Masih Harus Dibina ? *</label>
                                    <select id="kemampuan_buang_air" name="kemampuan_buang_air" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                        <option value="Kadang-kadang">Kadang-kadang</option>
                                    </select>
                                    <div class="error-message" id="kemampuan_buang_air-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="kebiasaan_ngompol" class="block text-sm font-medium text-gray-700">Apakah anak memiliki kebiasaan ngompol? *</label>
                                    <select id="kebiasaan_ngompol" name="kebiasaan_ngompol" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Sering">Sering</option>
                                    <option value="Kadang-kadang">Kadang-kadang</option>
                                    <option value="Jarang">Jarang</option>
                                    </select>
                                    <div class="error-message" id="kebiasaan_ngompol-error"></div>
                                </div>
                            </div>

                            <!-- Selera Makan Anak -->
                            <div class="space-y-2">
                                <label for="selera_makan" class="block text-sm font-medium text-gray-700">Selera Makanan Anak *</label>
                                <textarea id="selera_makan" name="selera_makan" rows="3"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Apakah ada makanan anak yang harus diperhatikan"></textarea>
                                <div class="error-message" id="selera_makan-error"></div>
                                </div>
                            
                            <!-- Kebiasaan Tidur Anak -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="kebiasaan_tidur_malam" class="block text-sm font-medium text-gray-700">Kebiasaan Tidur Malam Anak *</label>
                                    <textarea rows="3" id="kebiasaan_tidur_malam" name="kebiasaan_tidur_malam" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan kebiasaan anak ketika tidur pada malam hari"></textarea>
                                    <div class="error-message" id="kebiasaan_tidur_malam-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="kebiasaan_tidur_siang" class="block text-sm font-medium text-gray-700">Kebiasaan Tidur Siang Anak *</label>
                                    <textarea rows="3" id="kebiasaan_tidur_siang" name="kebiasaan_tidur_siang" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan kebiasaan anak ketika tidur pada siang hari"></textarea>
                                    <div class="error-message" id="kebiasaan_tidur_siang-error"></div>
                                </div>
                            </div>

                            <!-- Kebiasaan Bangun Anak -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="kebiasaan_bangun_pagi" class="block text-sm font-medium text-gray-700">Kebiasaan Bangun Pagi Anak *</label>
                                    <textarea rows="3" id="kebiasaan_bangun_pagi" name="kebiasaan_bangun_pagi" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan kebiasaan anak bangun pada pagi hari"></textarea>
                                    <div class="error-message" id="kebiasaan_bangun_pagi-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="kebiasan_bangun_siang" class="block text-sm font-medium text-gray-700">Kebiasaan Bangun Siang Anak *</label>
                                    <textarea rows="3" id="kebiasan_bangun_siang" name="kebiasan_bangun_siang" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan kebiasaan anak ketika bangun pada siang hari"></textarea>
                                    <div class="error-message" id="kebiasan_bangun_siangn_tidur_siang-error"></div>
                                </div>
                            </div>
                          
                            <!-- Tingkah Laku Anak -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="hal_mengenai_tingkah_anak" class="block text-sm font-medium text-gray-700">Hal-hal yang perlu dicatat atau dikemukakan mengenai tingkah anak *</label>
                                    <textarea rows="3" id="hal_mengenai_tingkah_anak" name="hal_mengenai_tingkah_anak" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Tingkah laku anak"></textarea>
                                    <div class="error-message" id="hal_mengenai_tingkah_anak-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="hal_penting_waktu_tidur" class="block text-sm font-medium text-gray-700">Hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak *</label>
                                    <textarea rows="3" id="hal_penting_waktu_tidur" name="hal_penting_waktu_tidur" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan Hal-hal yang perlu dicatat atau dikemukakan pada waktu tidur anak"></textarea>
                                    <div class="error-message" id="hal_penting_waktu_tidur-error"></div>
                                </div>
                            </div>

                            <!-- Mudah Bergaul -->
                            <div class="space-y-2">
                                <label for="mudah_bergaul" class="block text-sm font-medium text-gray-700">Apakah anak mudah bergaul ? *</label>
                                <select id="mudah_bergaul" name="mudah_bergaul" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                        <option value="Kadang-kadang">Kadang-kadang</option>
                                    </select>
                                <div class="error-message" id="mudah_bergaul-error"></div>
                                </div>
                            
                            <!-- Sifat Atau Kebiasaan Baik Anak -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="sifat_baik" class="block text-sm font-medium text-gray-700">Sifat Atau Kebiasaan Baik Anak *</label>
                                    <textarea rows="3" id="sifat_baik" name="sifat_baik" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan sifat Atau Kebiasaan Baik Anak"></textarea>
                                    <div class="error-message" id="sifat_baik-error"></div>
                            </div>
                                <div class="space-y-2">
                                    <label for="sifat_buruk" class="block text-sm font-medium text-gray-700">Sifat Atau Kebiasaan Buruk Anak*</label>
                                    <textarea rows="3" id="sifat_buruk" name="sifat_buruk" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Masukkan sifat Atau Kebiasaan Baik Anak"></textarea>
                                    <div class="error-message" id="sifat_buruk-error"></div>
                                </div>
                            </div>

                            <!-- Pembantu Rumah Tangga & Peralatan Elektronik -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="pembantu_rumah_tangga" class="block text-sm font-medium text-gray-700">Apakah memiliki pembantu rumah tangga ? *</label>
                                    <select id="pembantu_rumah_tangga" name="pembantu_rumah_tangga" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                    <div class="error-message" id="pembantu_rumah_tangga-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Peralatan elektronik yang berada di rumah *
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">
                                        * Anda dapat memilih lebih dari satu.
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="tv" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>TV</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="kulkas" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Kulkas</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="mesin_cuci" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Mesin Cuci</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="ac" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>AC</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="microwave" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Microwave</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="komputer" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Komputer</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="peralatan_elektronik[]" value="setrika" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Setrika</span>
                                        </label>
                                    </div>
                                    <div class="error-message" id="peralatan_elektronik-error"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Step 5: Data Keterangan Peserta -->
                    <div class="form-step" data-step="5">
                        <div class="space-y-6">
                           <!-- Keterangan Membaca -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="keterangan_membaca" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu membaca ? *</label>
                                    <select id="keterangan_membaca" name="keterangan_membaca" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Belum bisa">Belum bisa</option>
                                        <option value="Sedikit bisa">Sedikit bisa</option>
                                        <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_membaca-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="keterangan_membaca_hijaiyah" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu membaca Hijaiyah ? *</label>
                                    <select id="keterangan_membaca_hijaiyah" name="keterangan_membaca_hijaiyah" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Belum bisa">Belum bisa</option>
                                    <option value="Sedikit bisa">Sedikit bisa</option>
                                    <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_membaca_hijaiyah-error"></div>
                                </div>
                           </div>

                           <!-- Buku Membaca -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="judulbuku_berlatihmembaca_latin" class="block text-sm font-medium text-gray-700">Apa judul buku digunakan untuk berlatih membaca ? *</label>
                                    <textarea rows="3" id="judulbuku_berlatihmembaca_latin" name="judulbuku_berlatihmembaca_latin" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan judul buku yang digunakan untuk berlatih membaca Latin"></textarea>
                                    <div class="error-message" id="judulbuku_berlatihmembaca_latin-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="judulbuku_berlatihmembaca_hijaiyah" class="block text-sm font-medium text-gray-700">Apa judul buku digunakan untuk berlatih membaca Hijaiyah ? *</label>
                                <select id="judulbuku_berlatihmembaca_hijaiyah" name="judulbuku_berlatihmembaca_hijaiyah" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Opsi</option>
                                <option value="Iqro">Iqro</option>
                                <option value="Al Quran">Al Quran</option>
                                </select>
                                <div class="error-message" id="judulbuku_berlatihmembaca_hijaiyah-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="jilid_hijaiyah" class="block text-sm font-medium text-gray-700">Sudah jilid berapa dari buku berlatih membaca Hijaiyah ? *</label>
                                <textarea rows="3" id="jilid_hijaiyah" name="jilid_hijaiyah" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan jilid keberapa dari buku berlatih membaca Hijaiyah"></textarea>
                                <div class="error-message" id="jilid_hijaiyah-error"></div>
                            </div>
                               
                           </div>

                           <!-- Keterangan Kemampuan anak -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="keterangan_menulis" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu menulis ? *</label>
                                    <select id="keterangan_menulis" name="keterangan_menulis" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Pilih Opsi</option>
                                        <option value="Belum bisa">Belum bisa</option>
                                        <option value="Sedikit bisa">Sedikit bisa</option>
                                        <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_menulis-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="keterangan_menggambar" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu menggambar ? *</label>
                                    <select id="keterangan_menggambar" name="keterangan_menggambar" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Belum bisa">Belum bisa</option>
                                    <option value="Sedikit bisa">Sedikit bisa</option>
                                    <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_menggambar-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="keterangan_angka" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu mengenal angka ? *</label>
                                    <select id="keterangan_angka" name="keterangan_angka" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Belum bisa">Belum bisa</option>
                                    <option value="Sedikit bisa">Sedikit bisa</option>
                                    <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_angka-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="keterangan_menghitung" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu menghitung ? *</label>
                                    <select id="keterangan_menghitung" name="keterangan_menghitung" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Belum bisa">Belum bisa</option>
                                    <option value="Sedikit bisa">Sedikit bisa</option>
                                    <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_menghitung-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="keterangan_berwudhu" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu berwudhu ? *</label>
                                    <select id="keterangan_berwudhu" name="keterangan_berwudhu" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Belum bisa">Belum bisa</option>
                                    <option value="Sedikit bisa">Sedikit bisa</option>
                                    <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_berwudhu-error"></div>
                                </div>
                                <div class="space-y-2">
                                    <label for="keterangan_tata_cara_shalat" class="block text-sm font-medium text-gray-700">Apakah anak sudah mampu mengenal tata cara shalat ? *</label>
                                    <select id="keterangan_tata_cara_shalat" name="keterangan_tata_cara_shalat" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Opsi</option>
                                    <option value="Belum bisa">Belum bisa</option>
                                    <option value="Sedikit bisa">Sedikit bisa</option>
                                    <option value="Sudah mampu">Sudah mampu</option>
                                    </select>
                                    <div class="error-message" id="keterangan_tata_cara_shalat-error"></div>
                                </div>
                           </div>

                           <!-- Keterangan Menghafal -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="keterangan_hafalan_doa" class="block text-sm font-medium text-gray-700">Apa anak sudah memiliki atau sering mendengar hafalan doa ? *</label>
                                    <textarea rows="3" id="keterangan_hafalan_doa" name="keterangan_hafalan_doa" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan doa yang sudah di hafal"></textarea>
                                    <div class="error-message" id="keterangan_hafalan_doa-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="keterangan_hafalan_juz_ama" class="block text-sm font-medium text-gray-700">Apa anak sudah memiliki hafalan atau sering mendengar juz amma  ? *</label>
                                <textarea rows="3" id="keterangan_hafalan_juz_ama" name="keterangan_hafalan_juz_ama" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan jilid keberapa dari buku berlatih membaca Hijaiyah"></textarea>
                                <div class="error-message" id="keterangan_hafalan_juz_ama-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="keterangan_hafalan_murottal" class="block text-sm font-medium text-gray-700">Apa anak sudah memiliki hafalan atau sering mendengar murotal ? *</label>
                                <textarea rows="3" id="keterangan_hafalan_murottal" name="keterangan_hafalan_murottal" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan jilid keberapa dari buku berlatih membaca Hijaiyah"></textarea>
                                <div class="error-message" id="keterangan_hafalan_murottal-error"></div>
                            </div>
                           </div>

                           <!-- Hobi Anak -->
                           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                    <label for="hobi" class="block text-sm font-medium text-gray-700">Apa hobi anak ? *</label>
                                    <textarea rows="3" id="hobi" name="hobi" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Masukkan hobi anak"></textarea>
                                    <div class="error-message" id="hobi-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="keterangan_kisah_islami" class="block text-sm font-medium text-gray-700">Apakah anak sering mendengar cerita islami atau kisah para nabi ? *</label>
                                <textarea rows="3" id="keterangan_kisah_islami" name="keterangan_kisah_islami" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan kisah yang sering anak dengarkan"></textarea>
                                <div class="error-message" id="keterangan_kisah_islami-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="keterangan_majalah" class="block text-sm font-medium text-gray-700">Apakah berlangganan majalah ? *</label>
                                <textarea rows="3" id="keterangan_majalah" name="keterangan_majalah" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan kisah majalah yang sering anak baca"></textarea>
                                <div class="error-message" id="keterangan_majalah-error"></div>
                            </div>
                           </div>

                        </div>
                    </div>

                    <!-- Step 6: Konfirmasi & Submit -->
                    <div class="form-step" data-step="6">
                        <div class="space-y-6">

                            <!-- Pemasukan Perbulan Orang Tua -->
                            <div class="space-y-2">
                                        <label for="pemasukan_perbulan_orang_tua" class="block text-sm font-medium text-gray-700">Masukan Perbulan Orang Tua *</label>
                                        <select id="pemasukan_perbulan_orang_tua" name="pemasukan_perbulan_orang_tua" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Pilih Opsi</option>
                                            <option value="1">Rp 500.000 < Rp. 1.500.000</option>
                                            <option value="2"> Rp. 1.500.000 < Rp. 2.500.000</option>
                                            <option value="3"> > Rp. 2.500.000</option>
                                        </select>
                                        <div class="error-message" id="pemasukan_perbulan_orang_tua-error"></div>
                            </div>

                            <!-- Keterangan Dana-->
                            <div class="space-y-2">
                                <label for="keterangan_kenaikan_pendapatan" class="block text-sm font-medium text-gray-700">Apabila ditengah perjalanan kegiatan belajar mengajar (KBM) terjadi kenaikan harga bahan pokok yang berimbas pada biaya operasional terutama konsumsi, maka Tindakan apa yang harus dilakukan agar menu makanan yang diberikan pada anak-anak tetap stabil (berikan alasan) *</label>
                                <textarea rows="3" id="keterangan_kenaikan_pendapatan" name="keterangan_kenaikan_pendapatan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="keterangan_kenaikan_pendapatan-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="keterangan_infaq" class="block text-sm font-medium text-gray-700">Untuk mengatasi masalah kenaikan harga bahan pokok, bagaimana apabila orang tua/ wali murid yang mempunya kelebihan rezeki untuk menyisihkan hartanya/ berinfaq secara sukarela?(berikan alasan) *</label>
                                <textarea rows="3" id="keterangan_infaq" name="keterangan_infaq" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="keterangan_infaq-error"></div>
                            </div>

                            <!-- Keterangan Survei-->
                            <div class="space-y-2">
                                <label for="larangan_menunggu" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, peserta didik tidak boleh ditunggu orrang tua/wali/baby sitster kecuali awal masuk maksimal 2 pekan (berikan alasan) *</label>
                                <textarea rows="3" id="larangan_menunggu" name="larangan_menunggu" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="larangan_menunggu-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="larangan_perhiasan" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, peserta didik dilarang memakai perhiasan kecuali anting atau giwang (Berikan alasan) *</label>
                                <textarea rows="3" id="larangan_perhiasan" name="larangan_perhiasan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="larangan_perhiasan-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="berpakaian_islami" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju,orang tua wajib berpakaian Islami Ketika berada di lingkungan TKIT AL-Qolam (bagi ibu/penjemput putri di usahakan memakai jilbab). (berikan alasan) *</label>
                                <textarea rows="3" id="berpakaian_islami" name="berpakaian_islami" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="berpakaian_islami-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="menghadiri_pertemuan_wali" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, untuk menghadiri pertemuan wali murid 2 bulan sekali (berikan alasan) *</label>
                                <textarea rows="3" id="menghadiri_pertemuan_wali" name="menghadiri_pertemuan_wali" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="menghadiri_pertemuan_wali-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="kontrol_pengembangan" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, wali murid mengikuti kontrol perkembangan secara rutin selama di TKIT AL-Qolam. (berikan alasan) *</label>
                                <textarea rows="3" id="kontrol_pengembangan" name="kontrol_pengembangan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="kontrol_pengembangan-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="larangan_merokok" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, orang tua/wali mematuhi larangan merokok di lingkungan TKIT AL-Qolam. (berikan alasan) *</label>
                                <textarea rows="3" id="larangan_merokok" name="larangan_merokok" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="larangan_merokok-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="tidak_bekerjasama" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, orang tua/wali tidak bekerja sama dengan pihak TKIT AL-Qolam dalam proses pendidikan anak. (berikan alasan) *</label>
                                <textarea rows="3" id="larangan_merokok" name="tidak_bekerjasama" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="tidak_bekerjasama-error"></div>
                            </div>
                            <div class="space-y-2">
                                <label for="penjadwalan" class="block text-sm font-medium text-gray-700">Setuju atau tidak setuju, orang tua/wali bersedia mengikuti penjadwalan kegiatan yang telah ditetapkan oleh TKIT AL-Qolam. (berikan alasan) *</label>
                                <textarea rows="3" id="penjadwalan" name="penjadwalan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan pendapat anda"></textarea>
                                <div class="error-message" id="penjadwalan-error"></div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>Perhatian:</strong> Pastikan semua data yang Anda masukkan sudah benar. 
                                    Setelah submit, data tidak dapat diubah kembali.
                                </p>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                    <button type="button" id="prevBtn" class="btn-secondary" onclick="changeStep(-1)" disabled>
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </button>

                    <button type="button" id="nextBtn" class="btn-primary" onclick="changeStep(1)">
                        Selanjutnya<i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <button type="button" id="submitBtn" class="btn-success hidden" onclick="submitForm()">
                        <i class="fas fa-check mr-2"></i>Submit Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <div>
                <div class="font-semibold">Pendaftaran Berhasil!</div>
                <div class="text-sm text-gray-600">Data pendaftaran siswa telah berhasil dikirim.</div>
            </div>
        </div>
    </div>

    <script src="daftar.js"></script>
    
    <script>
        // Function to check kode pendaftaran
        let checkKodeTimeout;
        
        function checkKodePendaftaran(kode) {
            if (!kode || kode.trim() === '') {
                clearKodeValidation();
                return;
            }
            
            // Clear previous timeout
            clearTimeout(checkKodeTimeout);
            
            // Set new timeout to avoid too many requests
            checkKodeTimeout = setTimeout(() => {
                fetch(`/api/check-kode/${encodeURIComponent(kode.trim())}`)
                    .then(response => response.json())
                    .then(data => {
                        const input = document.getElementById('kode_pendaftaran_id');
                        const errorDiv = document.getElementById('kode_pendaftaran_id-error');
                        
                        if (data.valid) {
                            // Kode valid
                            input.classList.remove('input-error');
                            input.classList.add('border-green-500');
                            errorDiv.textContent = '';
                            errorDiv.style.display = 'none';
                        } else {
                            // Kode tidak valid atau sudah digunakan
                            input.classList.add('input-error');
                            input.classList.remove('border-green-500');
                            errorDiv.textContent = data.message;
                            errorDiv.style.display = 'block';
                            
                            // Show SweetAlert if code is already used
                            if (data.message === 'Kode sudah digunakan') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Kode Sudah Digunakan!',
                                    text: 'Kode pendaftaran yang Anda masukkan sudah digunakan oleh peserta lain. Silakan gunakan kode pendaftaran yang berbeda.',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#F59E0B',
                                    showClass: {
                                        popup: 'animate__animated animate__fadeInDown'
                                    },
                                    hideClass: {
                                        popup: 'animate__animated animate__fadeOutUp'
                                    }
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error checking kode:', error);
                        const input = document.getElementById('kode_pendaftaran_id');
                        const errorDiv = document.getElementById('kode_pendaftaran_id-error');
                        
                        input.classList.add('input-error');
                        input.classList.remove('border-green-500');
                        errorDiv.textContent = 'Terjadi kesalahan saat memeriksa kode';
                        errorDiv.style.display = 'block';
                    });
            }, 500); // Delay 500ms after user stops typing
        }
        
        function clearKodeValidation() {
            const input = document.getElementById('kode_pendaftaran_id');
            const errorDiv = document.getElementById('kode_pendaftaran_id-error');
            
            input.classList.remove('input-error', 'border-green-500');
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }
        
        // Function to update sibling form based on input values
        function updateSiblingForm() {
            const jumlahSaudaraTiri = parseInt(document.getElementById('jumlah_saudara_tiri').value) || 0;
            const jumlahSaudaraKandung = parseInt(document.getElementById('jumlah_saudara_kandung').value) || 0;
            const totalSaudara = jumlahSaudaraTiri + jumlahSaudaraKandung;
            
            const siblingFormSection = document.getElementById('sibling-form-section');
            const siblingFormContainer = document.getElementById('sibling-form-container');
            
            // Validate input values
            if (jumlahSaudaraTiri < 0) {
                document.getElementById('jumlah_saudara_tiri').value = 0;
            }
            if (jumlahSaudaraKandung < 0) {
                document.getElementById('jumlah_saudara_kandung').value = 0;
            }
            
            // Show/hide the form section based on total siblings
            if (totalSaudara > 0) {
                siblingFormSection.classList.remove('hidden');
                generateSiblingForms(totalSaudara, jumlahSaudaraKandung);
                
                // Add smooth animation
                siblingFormSection.style.opacity = '0';
                setTimeout(() => {
                    siblingFormSection.style.opacity = '1';
                }, 10);
            } else {
                siblingFormSection.classList.add('hidden');
                siblingFormContainer.innerHTML = '';
            }
        }
        
        // Function to generate sibling form fields
        function generateSiblingForms(totalSaudara, jumlahSaudaraKandung) {
            const container = document.getElementById('sibling-form-container');
            container.innerHTML = '';
            
            for (let i = 0; i < totalSaudara; i++) {
                const isKandung = i < jumlahSaudaraKandung;
                const siblingType = isKandung ? 'Kandung' : 'Tiri';
                const siblingNumber = i + 1;
                
                const siblingForm = document.createElement('div');
                siblingForm.className = 'bg-gray-50 p-4 rounded-lg border border-gray-200';
                siblingForm.innerHTML = `
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-800">Saudara ${siblingNumber} (${siblingType})</h4>
                        <span class="text-xs px-2 py-1 rounded-full ${isKandung ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800'}">
                            ${siblingType}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label for="nama_saudara${i}" class="block text-sm font-medium text-gray-700">Nama Saudara *</label>
                            <input type="text" id="nama_saudara${i}" name="nama_saudara${i}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Masukkan nama saudara" required>
                            <div class="error-message" id="nama_saudara${i}-error"></div>
                        </div>
                        <div class="space-y-2">
                            <label for="hubungan_saudara${i}" class="block text-sm font-medium text-gray-700">Hubungan *</label>
                            <select id="hubungan_saudara${i}" name="hubungan_saudara${i}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Pilih hubungan</option>
                                <option value="Kakak">Kakak</option>
                                <option value="Adik">Adik</option>
                            </select>
                            <div class="error-message" id="hubungan_saudara_${i}-error"></div>
                        </div>
                        <div class="space-y-2">
                            <label for="umur_saudara${i}" class="block text-sm font-medium text-gray-700">Umur *</label>
                            <input type="number" id="umur_saudara${i}" name="umur_saudara${i}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Masukkan umur" min="0" max="100" required>
                            <div class="error-message" id="umur_saudara${i}-error"></div>
                        </div>
                    </div>
                `;
                
                container.appendChild(siblingForm);
            }
        }
        
        // Function to update disease form based on has_penyakit selection
        function updatePenyakitForm() {
            const hasPenyakit = document.getElementById('has_penyakit').value;
            const penyakitFormSection = document.getElementById('penyakit-form-section');
            
            // Show/hide the form section based on has_penyakit value
            if (hasPenyakit === 'ya') {
                penyakitFormSection.classList.remove('hidden');
                
                // Add smooth animation
                penyakitFormSection.style.opacity = '0';
                setTimeout(() => {
                    penyakitFormSection.style.opacity = '1';
                }, 10);
                
                // Make all fields in the disease section required
                const requiredFields = ['penyakit_berapalama', 'penyakit_kapan', 'penyakit_pantangan', 'mempunyai_alergi'];
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = true;
                    }
                });
            } else {
                penyakitFormSection.classList.add('hidden');
                
                // Clear all fields when hiding
                const fieldsToClear = ['penyakit_berapalama', 'penyakit_kapan', 'penyakit_pantangan', 'mempunyai_alergi'];
                fieldsToClear.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.value = '';
                        field.required = false;
                    }
                });
            }
        }
        
        // Function to update wali form based on has_wali selection
        function updateWaliForm() {
            const hasWali = document.getElementById('has_wali').value;
            const waliFormSection = document.getElementById('wali-form-section');
            
            // Show/hide the form section based on has_wali value
            if (hasWali === 'ya') {
                waliFormSection.classList.remove('hidden');
                
                // Add smooth animation
                waliFormSection.style.opacity = '0';
                setTimeout(() => {
                    waliFormSection.style.opacity = '1';
                }, 10);
                
                // Make all required fields in the wali section required
                const requiredFields = ['nama_wali', 'hubungan_wali', 'pendidikan_terakhir_wali', 'pekerjaan_wali', 'no_hp_wali'];
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = true;
                    }
                });
            } else {
                waliFormSection.classList.add('hidden');
                
                // Clear all fields when hiding
                const fieldsToClear = ['nama_wali', 'hubungan_wali', 'pendidikan_terakhir_wali', 'pekerjaan_wali', 'no_hp_wali', 'sosmed_wali'];
                fieldsToClear.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.value = '';
                        field.required = false;
                    }
                });
            }
        }
        
        // Initialize the form when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateSiblingForm();
            updatePenyakitForm();
            updateWaliForm();
            
            // Initialize kode pendaftaran validation
            const kodeInput = document.getElementById('kode_pendaftaran_id');
            if (kodeInput) {
                // Check if there's already a value (e.g., from form validation errors)
                if (kodeInput.value) {
                    checkKodePendaftaran(kodeInput.value);
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const selectHijaiyah = document.getElementById('keterangan_membaca_hijaiyah');
            const bukuHijaiyah = document.getElementById('judulbuku_berlatihmembaca_hijaiyah').closest('.space-y-2');
            const jilidHijaiyah = document.getElementById('jilid_hijaiyah').closest('.space-y-2');

            function toggleHijaiyahFields() {
                if (selectHijaiyah.value === 'Belum bisa') {
                    bukuHijaiyah.style.display = 'none';
                    jilidHijaiyah.style.display = 'none';
                } else {
                    bukuHijaiyah.style.display = '';
                    jilidHijaiyah.style.display = '';
                }
            }

            // Inisialisasi saat load
            toggleHijaiyahFields();
            // Event listener saat berubah
            selectHijaiyah.addEventListener('change', toggleHijaiyahFields);
        });
    </script>
    
</body>
</html>