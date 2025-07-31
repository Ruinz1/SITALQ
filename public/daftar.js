// Global variables
let currentStep = 1;
const totalSteps = 6;
const formData = {};

// Step configuration
const steps = [
    { id: 1, title: 'Data Peserta', icon: 'fas fa-user' },
    { id: 2, title: 'Data Pendahuluan', icon: 'fas fa-book' },
    { id: 3, title: 'Data Orang Tua/Wali', icon: 'fas fa-users' },
    { id: 4, title: 'Data Informasi Peserta', icon: 'fas fa-info' },
    { id: 5, title: 'Data Keterangan Peserta', icon: 'fas fa-check-square' },
    { id: 6, title: 'Data Pendanaan Peserta', icon: 'fas fa-wallet' }
];

// Validation rules for each step
const validationRules = {
  1: [
    'nama_peserta', 'kode_pendaftaran_id', 'email', 'alamat', 'jenis_kelamin', 'agama',
    'tempat_lahir', 'tanggal_lahir', 'nama_panggilan', 'bahasa_sehari',
    'tinggi_badan', 'berat_badan',
    // 'jumlah_saudara_tiri', // opsional
    'jumlah_saudara_kandung', // wajib
    'anak_ke', 'toilet_traning', 'has_penyakit', 'tanda_tangan'
  ],
  2: [
    'latar_belakang', 'harapan_keislaman', 'harapan_keilmuan', 'harapan_sosial', 'berapa_lama_bersekolah'
  ],
  3: [
    'nama_ayah', 'nama_ibu', 'agama_ayah', 'agama_ibu', 'tempat_lahir_ayah', 'tempat_lahir_ibu',
    'tanggal_lahir_ayah', 'tanggal_lahir_ibu', 'alamat_ayah', 'alamat_ibu',
    'alamat_kantor_ayah', 'alamat_kantor_ibu', 'pendidikan_terakhir_ayah', 'pendidikan_terakhir_ibu',
    'pekerjaan_ayah', 'pekerjaan_ibu', 'no_hp_ayah', 'no_hp_ibu', 'sosmed_ayah', 'sosmed_ibu',
    'has_wali' // field wali divalidasi dinamis
  ],
  4: [
    'tinggal_bersama', 'halaman_bermain', 'jumlah_penghuni_dewasa', 'jumlah_penghuni_anak',
    'kepatuhan_anak', 'pergaulan_dengan_sebaya', 'hubungan_dengan_ayah', 'hubungan_dengan_ibu',
    'kemampuan_buang_air', 'kebiasaan_ngompol', 'selera_makan', 'kebiasaan_tidur_malam',
    'kebiasaan_tidur_siang', 'kebiasaan_bangun_pagi', 'kebiasan_bangun_siang', 'hal_mengenai_tingkah_anak',
    'hal_penting_waktu_tidur', 'mudah_bergaul', 'sifat_baik', 'sifat_buruk', 'pembantu_rumah_tangga'
    // peralatan_elektronik[] (checkbox, validasi jika perlu)
  ],
  5: [
    'keterangan_membaca', 'keterangan_membaca_hijaiyah', 'judulbuku_berlatihmembaca_latin',
    'jilid_hijaiyah', 'keterangan_menulis', 'keterangan_menggambar',
    'keterangan_angka', 'keterangan_menghitung', 'keterangan_berwudhu', 'keterangan_tata_cara_shalat',
    'keterangan_hafalan_doa', 'keterangan_hafalan_juz_ama', 'keterangan_hafalan_murottal', 'hobi',
    'keterangan_kisah_islami', 'keterangan_majalah'
  ],
  6: [
    'pemasukan_perbulan_orang_tua', 'keterangan_kenaikan_pendapatan', 'keterangan_infaq',
    'larangan_menunggu', 'larangan_perhiasan', 'berpakaian_islami', 'menghadiri_pertemuan_wali',
    'kontrol_pengembangan', 'larangan_merokok', 'tidak_bekerjasama', 'penjadwalan'
  ]
};

// Initialize the form when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeForm();
    setupEventListeners();
});

function initializeForm() {
    generateStepIndicator();
    updateStepDisplay();
    updateNavigationButtons();
}

function setupEventListeners() {
    // Add input event listeners to clear errors
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearError(this.name);
        });
    });

    // Add file upload event listeners
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleFileSelect(this, this.name + '-label');
        });
    });
}

function generateStepIndicator() {
    const stepIndicator = document.getElementById('stepIndicator');
    stepIndicator.innerHTML = '';

    steps.forEach((step, index) => {
        const stepElement = document.createElement('div');
        stepElement.className = 'flex items-center step-item';
        
        stepElement.innerHTML = `
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300 step-circle" 
                     id="step-${step.id}">
                    <i class="${step.icon} text-sm"></i>
                </div>
                <span class="text-xs mt-2 text-center max-w-16 leading-tight step-title" id="step-title-${step.id}">
                    ${step.title}
                </span>
            </div>
        `;

        stepIndicator.appendChild(stepElement);

        // Add progress line between steps (except for the last step)
        if (index < steps.length - 1) {
            const progressLine = document.createElement('div');
            progressLine.className = 'hidden md:block w-16 h-0.5 mx-4 progress-line';
            progressLine.id = `progress-${step.id}`;
            stepIndicator.appendChild(progressLine);
        }
    });

    updateStepIndicator();
}

function updateStepIndicator() {
    steps.forEach(step => {
        const stepCircle = document.getElementById(`step-${step.id}`);
        const stepTitle = document.getElementById(`step-title-${step.id}`);
        const progressLine = document.getElementById(`progress-${step.id}`);

        if (step.id < currentStep) {
            // Completed step
            stepCircle.className = 'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300 step-completed';
            stepCircle.innerHTML = '<i class="fas fa-check text-sm"></i>';
            stepTitle.className = 'text-xs mt-2 text-center max-w-16 leading-tight text-gray-600';
            if (progressLine) {
                progressLine.classList.add('completed');
            }
        } else if (step.id === currentStep) {
            // Active step
            stepCircle.className = 'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300 step-active';
            stepCircle.innerHTML = `<i class="${step.icon} text-sm"></i>`;
            stepTitle.className = 'text-xs mt-2 text-center max-w-16 leading-tight text-gray-800 font-medium';
            if (progressLine) {
                progressLine.classList.remove('completed');
            }
        } else {
            // Inactive step
            stepCircle.className = 'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-300 step-inactive';
            stepCircle.innerHTML = `<i class="${step.icon} text-sm"></i>`;
            stepTitle.className = 'text-xs mt-2 text-center max-w-16 leading-tight text-gray-500';
            if (progressLine) {
                progressLine.classList.remove('completed');
            }
        }
    });
}

function updateStepDisplay() {
    // Hide all steps
    const allSteps = document.querySelectorAll('.form-step');
    allSteps.forEach(step => {
        step.classList.remove('active');
    });

    // Show current step
    const currentStepElement = document.querySelector(`[data-step="${currentStep}"]`);
    if (currentStepElement) {
        currentStepElement.classList.add('active');
    }

    // Update step title and description
    const stepTitle = document.getElementById('stepTitle');
    const stepDescription = document.getElementById('stepDescription');
    
    if (stepTitle && stepDescription) {
        stepTitle.textContent = steps[currentStep - 1].title;
        stepDescription.textContent = `Langkah ${currentStep} dari ${totalSteps}`;
    }

    updateStepIndicator();
    updateNavigationButtons();

    // If we're on step 6, populate confirmation data
    if (currentStep === 6) {
        populateConfirmationData();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Previous button
    if (prevBtn) {
        prevBtn.disabled = currentStep === 1;
        if (currentStep === 1) {
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Next/Submit buttons
    if (currentStep === totalSteps) {
        if (nextBtn) nextBtn.classList.add('hidden');
        if (submitBtn) submitBtn.classList.remove('hidden');
    } else {
        if (nextBtn) nextBtn.classList.remove('hidden');
        if (submitBtn) submitBtn.classList.add('hidden');
    }
}

function changeStep(direction) {
    if (direction > 0) {
        // Moving forward - validate current step
        if (!validateCurrentStep()) {
            return;
        }
        saveCurrentStepData();
    }

    const newStep = currentStep + direction;
    
    if (newStep >= 1 && newStep <= totalSteps) {
        currentStep = newStep;
        updateStepDisplay();
    }
}

function validateCurrentStep() {
  const fieldsToValidate = validationRules[currentStep] || [];
  let isValid = true;

  // Clear all previous errors for this step
  fieldsToValidate.forEach(fieldName => clearError(fieldName));

  fieldsToValidate.forEach(fieldName => {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    if (!field) return;
    let value = field.type === 'file'
      ? (field.files && field.files.length > 0 ? field.files[0].name : '')
      : field.value.trim();
    if (!value) {
      showError(fieldName, getFieldLabel(fieldName) + ' harus diisi');
      isValid = false;
    }
    
    // Special validation for kode_pendaftaran_id
    if (fieldName === 'kode_pendaftaran_id' && value) {
      // Check if the kode has been validated as valid
      const errorElement = document.getElementById(`${fieldName}-error`);
      if (errorElement && errorElement.textContent && !errorElement.textContent.includes('dapat digunakan')) {
        showError(fieldName, 'Kode pendaftaran tidak valid atau sudah digunakan');
        isValid = false;
      }
    }
  });

  // Validasi field dinamis: Penyakit
  if (currentStep === 1) {
    const hasPenyakit = document.getElementById('has_penyakit').value;
    if (hasPenyakit === 'ya') {
      ['penyakit_berapalama', 'penyakit_kapan', 'penyakit_pantangan', 'mempunyai_alergi'].forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
          showError(fieldName, getFieldLabel(fieldName) + ' harus diisi');
          isValid = false;
        }
      });
    }
  }

  // Validasi field dinamis: Wali
  if (currentStep === 3) {
    const hasWali = document.getElementById('has_wali').value;
    if (hasWali === 'ya') {
      [
        'nama_wali', 'agama_wali', 'tempat_lahir_wali', 'tanggal_lahir_wali',
        'alamat_wali', 'alamat_kantor_wali', 'hubungan_wali', 'pendidikan_terakhir_wali',
        'pekerjaan_wali', 'no_hp_wali'
      ].forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
          showError(fieldName, getFieldLabel(fieldName) + ' harus diisi');
          isValid = false;
        }
      });
    }
  }

  // Tidak perlu validasi judulbuku_berlatihmembaca_hijaiyah (opsional)

  return isValid;
}

function saveCurrentStepData() {
    const fieldsToSave = validationRules[currentStep];
    
    fieldsToSave.forEach(fieldName => {
        const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
        
        if (!field) return;

        if (field.type === 'file') {
            formData[fieldName] = field.files && field.files.length > 0 ? field.files[0] : null;
        } else {
            formData[fieldName] = field.value.trim();
        }
    });
}

function showError(fieldName, message) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    const errorElement = document.getElementById(`${fieldName}-error`);
    
    if (field) {
        field.classList.add('input-error');
    }
    
    if (errorElement) {
        errorElement.textContent = message;
    }
}

function clearError(fieldName) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    const errorElement = document.getElementById(`${fieldName}-error`);
    
    if (field) {
        field.classList.remove('input-error');
        // Don't remove green border for valid kode
        if (fieldName !== 'kode_pendaftaran_id') {
            field.classList.remove('border-green-500');
        }
    }
    
    if (errorElement) {
        errorElement.textContent = '';
    }
}

function getFieldLabel(fieldName) {
    const labelMap = {
        'nama_peserta': 'Nama lengkap',
        'kode_pendaftaran_id': 'Kode Pendaftaran',
       
    };
    
    return labelMap[fieldName] || fieldName;
}

function handleFileSelect(input, labelId) {
    const label = document.getElementById(labelId);
    const errorElement = document.getElementById(`${input.name}-error`);
    
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (file.size > maxSize) {
            showError(input.name, 'Ukuran file maksimal 2MB');
            input.value = '';
            return;
        }
        
        if (label) {
            label.textContent = file.name;
            label.classList.add('text-green-600', 'font-medium');
        }
        
        clearError(input.name);
    } else {
        if (label) {
            label.textContent = `Pilih file untuk ${getFieldLabel(input.name).toLowerCase()}`;
            label.classList.remove('text-green-600', 'font-medium');
        }
    }
}

function populateConfirmationData() {
    // Save current step data before showing confirmation
    for (let step = 1; step <= 5; step++) {
        const fieldsToSave = validationRules[step];
        fieldsToSave.forEach(fieldName => {
            const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
            
            if (!field) return;

            if (field.type === 'file') {
                formData[fieldName] = field.files && field.files.length > 0 ? field.files[0] : null;
            } else {
                formData[fieldName] = field.value.trim();
            }
        });
    }

    // Populate text fields
    const textFields = [
        'nama_peserta', 'kode_pendaftaran_id', 'jenis_kelamin', 'alamat', 
    ];

    textFields.forEach(fieldName => {
        const confirmElement = document.getElementById(`confirm-${fieldName}`);
        if (confirmElement) {
            confirmElement.textContent = formData[fieldName] || '-';
        }
    });

    // Special handling for tempet, tanggal lahir
    const confirmTempetTanggalLahir = document.getElementById('confirm-tempat-tanggal-lahir');
    if (confirmTempetTanggalLahir) {
        const tempat = formData['tempat_lahir'] || '';
        const tanggal = formData['tanggal_lahir'] || '';
        confirmTempetTanggalLahir.textContent = `${tempat}, ${tanggal}`;
    }

    // Update file upload status
    const fileFields = ['tanda_tangan'];
    fileFields.forEach(fieldName => {
        const confirmElement = document.getElementById(`confirm-${fieldName}`);
        const icon = confirmElement ? confirmElement.querySelector('i') : null;
        
        if (confirmElement && icon) {
            if (formData[fieldName]) {
                icon.className = 'fas fa-check-circle text-green-500';
                confirmElement.classList.remove('text-gray-400');
                confirmElement.classList.add('text-green-600');
            } else {
                icon.className = 'fas fa-times-circle text-red-500';
                confirmElement.classList.remove('text-green-600');
                confirmElement.classList.add('text-red-400');
            }
        }
    });
}

function submitForm() {
    // Validate step 6 before submitting
    if (!validateCurrentStep()) {
        return;
    }

    // Additional validation for kode_pendaftaran_id
    const kodeField = document.getElementById('kode_pendaftaran_id');
    if (kodeField && kodeField.value.trim()) {
        const errorElement = document.getElementById('kode_pendaftaran_id-error');
        if (errorElement && errorElement.textContent && !errorElement.textContent.includes('dapat digunakan')) {
            showError('kode_pendaftaran_id', 'Kode pendaftaran tidak valid atau sudah digunakan');
            return;
        }
    }
    
    // Validate required file upload
    const tandaTanganField = document.getElementById('tanda_tangan');
    if (!tandaTanganField || !tandaTanganField.files || tandaTanganField.files.length === 0) {
        showError('tanda_tangan', 'Tanda tangan orang tua/wali wajib diupload');
        return;
    }

    // Save final step data
    saveCurrentStepData();
    
    // Collect all data from all steps to ensure nothing is missed
    for (let step = 1; step <= totalSteps; step++) {
        const fieldsToSave = validationRules[step];
        fieldsToSave.forEach(fieldName => {
            const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
            
            if (!field) return;

            if (field.type === 'file') {
                formData[fieldName] = field.files && field.files.length > 0 ? field.files[0] : null;
            } else {
                formData[fieldName] = field.value.trim();
            }
        });
    }

    // Get the form element
    const form = document.getElementById('registrationForm');
    if (!form) {
        console.error('Form not found');
        return;
    }

    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonText = submitButton ? submitButton.innerHTML : 'Submit';
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim Data...';
    }

    // Create FormData object from the form
    const formDataObj = new FormData(form);

    // Add any additional data from the formData object if needed
    Object.keys(formData).forEach(key => {
        if (!formDataObj.has(key)) {
            formDataObj.append(key, formData[key]);
        }
    });
    
    // Debug: Log the data being sent
    console.log('Form data being sent:', Object.fromEntries(formDataObj));

    // Submit the form data
    fetch(form.action, {
        method: 'POST',
        body: formDataObj,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Reset button state
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }

        if (data.status === 'success') {
            // Show success SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: data.message || 'Data pendaftaran Anda telah berhasil disimpan Silahkan Cek Email Anda.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10B981',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to index page after successful submission
                    window.location.href = '/';
                }
            });
        } else {
            // Show error SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan Data!',
                text: data.message || 'Terjadi kesalahan saat menyimpan data pendaftaran.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#EF4444',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        
        // Reset button state
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }

        // Show error SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#EF4444',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    });
}

function showSuccessToast() {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 5000);
    }
}

function resetForm() {
    // Reset form data
    Object.keys(formData).forEach(key => {
        delete formData[key];
    });

    // Reset form fields
    const form = document.getElementById('registrationForm');
    if (form) {
        form.reset();
    }

    // Reset file upload labels
    const fileLabels = [
        'tanda_tangan-label',
    ];
    
    fileLabels.forEach(labelId => {
        const label = document.getElementById(labelId);
        if (label) {
            const fieldName = labelId.replace('-label', '');
            label.textContent = `Pilih file untuk ${getFieldLabel(fieldName).toLowerCase()}`;
            label.classList.remove('text-green-600', 'font-medium');
        }
    });

    // Clear all errors
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(element => {
        element.textContent = '';
    });

    const inputFields = document.querySelectorAll('.input-error');
    inputFields.forEach(field => {
        field.classList.remove('input-error');
    });

    // Reset to first step
    currentStep = 1;
    updateStepDisplay();
}

// Drag and drop functionality for file uploads
document.addEventListener('DOMContentLoaded', function() {
    const fileUploadAreas = document.querySelectorAll('.file-upload-area');
    
    fileUploadAreas.forEach(area => {
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = this.parentElement.querySelector('input[type="file"]');
                if (input) {
                    // Create a new FileList and assign it to input
                    input.files = files;
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            }
        });
    });
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.ctrlKey) {
        // Ctrl + Enter to go next
        changeStep(1);
    } else if (e.key === 'Backspace' && e.ctrlKey) {
        // Ctrl + Backspace to go back
        changeStep(-1);
    }
});