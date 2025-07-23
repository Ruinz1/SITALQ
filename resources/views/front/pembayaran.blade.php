@extends('layouts.master')
@section('content')
<section id="Content" class="flex flex-col lg:flex-row min-h-screen">
    <div class="w-full lg:w-1/2 bg-portto-black flex flex-col justify-center items-center p-[30px_40px]">
        <!-- Form Header -->
        <div class="form-header w-full max-w-[550px] mb-8">
           
            <h1 class="text-white text-2xl md:text-3xl font-bold text-center mb-3">Form Pembayaran TKIT AL-QOLAM</h1>
            <p class="text-gray-300 text-center text-base md:text-lg">Silakan lengkapi form pembayaran di bawah ini</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex justify-center w-full max-w-[550px] mb-12">
         
        </div>

        <!-- Form -->
        <div class="w-full max-w-[550px] mx-auto">
            <form id="multiStepForm" action="#" method="POST" class="flex flex-col gap-[50px]">
                @csrf
                
                <!-- Step 1: Data Pembayaran -->
                <div class="step-content" data-step="1">
                    <label class="flex flex-col gap-[10px] font-semibold">
                        <span class="text-white">Kode Transaksi</span>
                        <input type="text" name="kode_transaksi" id="kode_transaksi" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green placeholder:font-normal placeholder:text-base placeholder:text-[#878C9C]" placeholder="Masukkan kode transaksi" required>
                        <p id="kode_error" class="text-red-500 text-sm hidden">Kode transaksi tidak valid</p>
                    </label>

                    <div id="payment_details" class="hidden space-y-4 mt-4">
                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Nama</span>
                            <input type="text" name="nama" id="nama_lengkap" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green" readonly>
                        </label>

                        <label class="flex flex-col gap-[10px] font-semibold">
                            <span class="text-white">Total Pembayaran</span>
                            <input type="text" name="total_bayar" id="total_pembayaran" class="bg-white rounded-full p-[14px_30px] appearance-none outline-none focus:ring-[3px] focus:ring-portto-green" readonly>
                        </label>
                    </div>
                    
                    <div class="flex justify-end mt-12">
                        <button type="button" id="payButton" class="font-bold text-lg text-white bg-portto-purple rounded-[20px] p-5 transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5] hidden">Bayar Sekarang</button>
                    </div>
                </div>



                  
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('multiStepForm');
    const steps = document.querySelectorAll('.step-content');
    const stepIndicators = document.querySelectorAll('.step');
    let currentStep = 1;

    // Fungsi untuk validasi form
    function validateStep(stepNumber) {
        const currentInputs = steps[stepNumber-1].querySelectorAll('input:not([type="hidden"]), select');
        let isValid = true;
        
        currentInputs.forEach(input => {
            if (input.required && !input.value) {
                isValid = false;
                input.reportValidity();
            }
        });
        
        return isValid;
    }

    // Fungsi untuk update tampilan step
    function updateStepStyles(newStep) {
        stepIndicators.forEach((indicator, index) => {
            const stepCircle = indicator.querySelector('.step-circle');
            
            indicator.classList.remove('active', 'completed');
            stepCircle.classList.remove('bg-portto-purple', 'text-white');
            stepCircle.classList.add('bg-white', 'text-portto-purple');
            
            if (index + 1 === newStep) {
                indicator.classList.add('active');
                stepCircle.classList.remove('bg-white', 'text-portto-purple');
                stepCircle.classList.add('bg-portto-purple', 'text-white');
            }
        });
    }

    // Fungsi untuk mengganti step
    function changeStep(newStep) {
        if (!validateStep(currentStep)) {
            return;
        }

        // Update konfirmasi data jika pindah ke step 2
        if (newStep === 2) {
            document.getElementById('konfirmasi_kode').textContent = document.getElementById('kode_pendaftaran').value;
            document.getElementById('konfirmasi_nama').textContent = document.getElementById('nama_lengkap').value;
            document.getElementById('konfirmasi_total').textContent = document.getElementById('total_bayar').value;
            document.getElementById('konfirmasi_metode').textContent = document.getElementById('metode_pembayaran').options[document.getElementById('metode_pembayaran').selectedIndex].text;
        }

        steps[currentStep-1].classList.add('hidden');
        steps[newStep-1].classList.remove('hidden');
        updateStepStyles(newStep);
        currentStep = newStep;
    }

    // Event listener untuk tombol next dan previous
    form.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < steps.length) {
                changeStep(currentStep + 1);
            }
        });
    });

    form.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 1) {
                changeStep(currentStep - 1);
            }
        });
    });

    // Validasi kode pendaftaran
    const kodeTransaksi = document.getElementById('kode_transaksi');
    const namaLengkap = document.getElementById('nama_lengkap');
    const totalPembayaran = document.getElementById('total_pembayaran');
    const payButton = document.getElementById('payButton');
    const paymentDetails = document.getElementById('payment_details');
    let snapToken = null;

    kodeTransaksi.addEventListener('blur', async function() {
        const kode = this.value;
        if (kode) {
            try {
                const response = await fetch(`/api/check-pembayaran/${kode}`);
                const data = await response.json();
                
                if (!data.valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kode Tidak Valid',
                        text: data.message,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4920E5'
                    });
                    namaLengkap.value = '';
                    totalPembayaran.value = '';
                    payButton.classList.add('hidden');
                    paymentDetails.classList.add('hidden');
                } else {
                    namaLengkap.value = data.nama_lengkap;
                    totalPembayaran.value = `Rp ${data.total_pembayaran.toLocaleString('id-ID')}`;
                    snapToken = data.snap_token;
                    payButton.classList.remove('hidden');
                    paymentDetails.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memeriksa kode transaksi',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4920E5'
                });
                paymentDetails.classList.add('hidden');
            }
        } else {
            paymentDetails.classList.add('hidden');
        }
    });

    payButton.addEventListener('click', function() {
        if (snapToken) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.href = '/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status;
                },
                onPending: function(result) {
                    window.location.href = '/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status;
                },
                onError: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan dalam proses pembayaran',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#4920E5'
                    });
                }
            });
        }
    });

    // Inisialisasi tampilan awal
    updateStepStyles(currentStep);
});
</script>
@endpush
@push('scripts')
<script src="{{ asset('main.js') }}"></script>
@endpush

@push('styles')
<style>
/* Form styles */
#multiStepForm {
    width: 100%;
    max-width: 550px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Step indicator styles */
.step {
    flex: 1;
    max-width: 120px;
    position: relative;
    text-align: center;
    margin: 0 auto;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    #multiStepForm {
        padding: 0 15px;
    }
    
    .step {
        max-width: 100px;
    }
}

/* Konfirmasi card styles */
.bg-white.rounded-lg {
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.space-y-4 > div {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.space-y-4 > div:last-child {
    border-bottom: none;
}

/* Form header styles */
.form-header {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-container img {
    transition: transform 0.3s ease;
}

.logo-container img:hover {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .form-header {
        padding: 0 1rem;
    }
    
    .logo-container {
        max-width: 150px;
    }
}
</style>
@endpush
@endsection 