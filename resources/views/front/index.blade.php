@extends('layouts.master')

@section('title', 'TKIT Al Qolam - Taman Kanak-Kanak Islam Terpadu')


@section('content')
    <section id="Header" class="flex flex-col gap-[50px] sm:gap-[100px] relative">
        <div class="absolute inset-0 bg-primary-blue opacity-70 z-0"></div>
        <div class="hero container max-w-[1130px] mx-auto flex flex-col sm:flex-row justify-between items-center relative px-6 sm:px-0 z-10">
            <div class="flex flex-col gap-5 sm:gap-[50px] h-fit w-fit text-white z-10">
                <h1 class="font-extrabold text-[42px] sm:text-[80px] leading-[60px] sm:leading-[90px]">Taman Kanak-Kanak Islam Terpadu (TKIT) Al Qolam</h1>
                
                <p class="text-lg sm:text-l px-4 sm:px-8 max-w-[800px]">Merupakan Lembaga Pendidikan yang Unggul dan Berakhlak Mulia anak usia dini yang berada pada jalur formal sebagai lembaga kepedulian dari sekelompok orang terhadap pentingnya nilai pendidikan bagi anak usia 4-6 tahun </p>
                @if($pendaftaran)
                <p class="text-lg sm:text-l px-4 sm:px-8 max-w-[800px]">Telah Dibuka Pendaftaran untuk calon peserta didik Tahun Ajaran : {{$pendaftaran->tahunAjaran->nama}}</p>
                    <a href="{{route('front.daftar')}}" class="font-bold text-lg sm:text-[26px] sm:leading-[39px] rounded-full sm:rounded-[30px] p-[12px_30px] sm:p-[30px_40px] bg-portto-purple w-fit transition-all duration-300 hover:shadow-[0_10px_20px_0_#4920E5]">Daftar Sekarang</a>
                @endif
            </div>
            <div class="flex max-w-[471px] max-h-[567px] z-10 mt-10 sm:mt-0">
                <img src="{{asset('assets/images/tk-image.png')}}" class="w-full h-full object-contain" alt="hero image">
            </div>
            <img src="{{asset('assets/images/Ellipse.svg')}}" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/3 sm:top-1/2 z-0" alt="background icon">
        </div>
       <div></div>
    </section>
</section>

    <section id="Lokasi" class="container max-w-[1130px] mx-auto pt-[485px] sm:pt-[190px] pb-[50px] sm:pb-[100px] px-6 sm:px-0">
        <div class="flex flex-col gap-[20px] sm:gap-[20px]">
            <div class="flex justify-between items-center">
                <h2 class="font-extrabold text-[32px] sm:text-[50px] leading-[48px] sm:leading-[70px] w-[240px] sm:w-auto">Lokasi & Tentang Kami</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-[30px]">
                <div class="p-[30px] sm:p-[50px] pb-0 sm:pb-0 rounded-[30px] flex flex-col gap-[30px] bg-[#F4F5F8]">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center justify-center shrink-0 w-20 h-20 rounded-full bg-portto-red">
                            <img src="{{asset('assets/images/icons/location_icon.png')}}" class="w-10 h-10 object-contain" alt="icon">
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.2910070792086!2d119.86082106481221!3d-0.9315371969807607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d8bf21696660917%3A0x6b10be03f1987d41!2sTKIT%20AL%20QOLAM%20TINGGEDE!5e0!3m2!1sid!2sid!4v1690803167615!5m2!1sid!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="p-[30px] sm:p-[50px] rounded-[30px] flex flex-col gap-[30px] bg-[#F4F5F8]">
                    <div class="flex flex-col gap-5">
                        <div class="flex items-center justify-center shrink-0 w-20 h-20 rounded-full bg-portto-red">
                            <img src="{{asset('assets/images/icons/about_icon.png')}}" class="w-10 h-10 object-contain" alt="icon">
                        </div>
                        <p class="font-extrabold text-[26px] sm:text-[32px] leading-[39px] sm:leading-[48px]">Tentang Kami</p>
                        <p class="text-lg leading-[34px]">TKIT Al Qolam telah memiliki Surat Izin Operasional pada bulan Mei 2011 dari Dinas Pendidikan Pemuda dan Olahraga Kabupaten Sigi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="Sarana" class=" w-full flex flex-col py-[50px] sm:py-[100px] bg-[url('assets/images/background/background1.png')] bg-cover bg-center bg-no-repeat px-6 sm:px-0">
        <div class="flex flex-col gap-[10px] mb-[30px] sm:mb-[50px]">
            <h2 class="font-extrabold text-[40px] sm:text-[50px] leading-[70px] text-center text-white">Sarana & Prasarana</h2>
            <p class="text-lg text-center text-white">TK Islam Terpadu Al Qolam</p>
        </div>
        <div class="projects w-full hidden sm:flex flex-col mb-[30px] overflow-hidden">    
            <div class="group/slider slider flex flex-nowrap w-max items-center">
                <div class="project-container animate-[slide_50s_linear_infinite] group-hover/slider:pause-animate flex gap-[30px] pl-[30px] items-center flex-nowrap">
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/ruangkelas.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Ruang Kelas</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/halaman.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Halaman Bermain</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/rak.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Rak Siswa</p>
                            </div>
                            <a href="#" class="z-10 font-bold text-lg text-center w-fit h-fit bg-portto-light-gold rounded-full p-[14px_30px] transition-all duration-300 hover:shadow-[0_10px_20px_0_#FFE7C280]">View Details</a>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/aula.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Aula</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/kantor.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Kantor</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                </div>
                <div class="project-container animate-[slide_50s_linear_infinite] group-hover/slider:pause-animate flex gap-[30px] pl-[30px] items-center flex-nowrap">
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/ruangkelas.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Ruang Kelas</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/halaman.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Halaman Bermain</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/rak.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Rak Siswa</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/aula.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Aula</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                    <div class="group w-[650px] h-[450px] flex shrink-0 rounded-[30px] border border-white p-5 bg-[#FFFFFF33] backdrop-blur relative">
                        <div class="w-[608px] h-[408px] rounded-[30px] overflow-hidden absolute">
                            <img src="{{asset('assets/images/sarana/kantor.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col w-full items-center justify-center gap-[50px] bg-portto-black rounded-[30px] relative opacity-0 hover:opacity-100 transition-all duration-300">
                            <div class="text-center z-10">
                                <p class="font-extrabold text-[32px] leading-[48px] mb-[10px] text-white">Kantor</p>
                            </div>
                            <img src="assets/images/Ellipse.svg" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2 w-1/2" alt="background icon">
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="project-container-mobile flex sm:hidden flex-col gap-[30px]">
            <div class="group w-full aspect-[4/3] flex shrink-0 rounded-[30px] border border-white p-3 px-[11px] bg-[#FFFFFF33] backdrop-blur relative">
                    <img src="{{asset('assets/images/sarana/ruangkelas.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                </a>
            </div>
            <div class="group w-full aspect-[4/3] flex shrink-0 rounded-[30px] border border-white p-3 px-[11px] bg-[#FFFFFF33] backdrop-blur relative">
                    <img src="{{asset('assets/images/sarana/halaman.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                </a>
            </div>
            <div class="group w-full aspect-[4/3] flex shrink-0 rounded-[30px] border border-white p-3 px-[11px] bg-[#FFFFFF33] backdrop-blur relative">
                    <img src="{{asset('assets/images/sarana/rak.png')}}" class="w-full h-full object-cover" alt="thumbnail">
                </a>
            </div>
        </div>
    </section>

    <section id="FAQ" class="container max-w-[1130px] mx-auto px-6 sm:px-0">
        <div class="flex flex-col sm:flex-row gap-[50px] sm:gap-[70px] items-center pt-[50px] sm:pt-[100px] pb-[100px] sm:pb-[150px]">
            <div class="flex flex-col gap-[30px]">
                <div class="w-20 h-20 flex shrink-0 rounded-full bg-portto-purple items-center justify-center">
                    <img src="{{asset('assets/images/icons/messages.svg')}}" alt="icon">
                </div>
                <div class="flex flex-col gap-[10px]">
                    <h2 class="font-extrabold text-[32px] sm:text-[50px] leading-[48px] sm:leading-[70px]">Pertanyaan Umum</h2>
                    <p class="text-lg text-[#878C9C]">Jika ada pertanyaan lebih lanjut, silakan hubungi kami.</p>
                </div>
                <a href="#" class="bg-portto-black font-bold text-lg text-white rounded-full p-[14px_30px] w-fit transition-all duration-300 hover:bg-white hover:text-portto-black hover:ring hover:ring-portto-black">Hubungi Kami</a>
            </div>
            <div class="flex flex-col gap-[30px] sm:w-[603px] shrink-0">
                <div class="flex flex-col p-5 rounded-2xl bg-[#F4F5F8] w-full">
                    <button class="accordion-button flex justify-between gap-1 items-center" data-accordion="accordion-faq-1">
                        <span class="font-bold text-2xl text-left">Apa saja syarat pendaftaran siswa baru?</span>
                        <div class="arrow w-9 h-9 flex shrink-0">
                            <img src="assets/images/icons/arrow-circle-down.svg" class="transition-transform duration-300" alt="icon">
                        </div>
                    </button>
                    <div id="accordion-faq-1" class="accordion-content max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-[20px] leading-[36px] pt-5">Syarat pendaftaran meliputi: usia 4-6 tahun, mengambil nomor pendaftaran, dan mengisi formulir pendaftaran yang disediakan.</p>
                    </div>
                </div>
                <div class="flex flex-col p-5 rounded-2xl bg-[#F4F5F8] w-full">
                    <button class="accordion-button flex justify-between gap-1 items-center" data-accordion="accordion-faq-2">
                        <span class="font-bold text-2xl text-left">Berapa biaya pendidikan per bulan?</span>
                        <div class="arrow w-9 h-9 flex shrink-0">
                            <img src="{{asset('assets/images/icons/arrow-circle-down.svg')}}" class="transition-all duration-300" alt="icon">
                        </div>
                    </button>
                    <div id="accordion-faq-2" class="accordion-content hide">
                        <p class="text-[20px] leading-[36px] pt-5">Biaya pendidikan disesuaikan dengan program yang dipilih. Silakan menghubungi bagian administrasi untuk informasi lebih detail.</p>
                    </div>
                </div>
                <div class="flex flex-col p-5 rounded-2xl bg-[#F4F5F8] w-full">
                    <button class="accordion-button flex justify-between gap-1 items-center" data-accordion="accordion-faq-3">
                        <span class="font-bold text-2xl text-left">Apa saja kegiatan pembelajaran yang diterapkan?</span>
                        <div class="arrow w-9 h-9 flex shrink-0">
                            <img src="{{asset('assets/images/icons/arrow-circle-down.svg')}}" class="transition-all duration-300" alt="icon">
                        </div>
                    </button>
                    <div id="accordion-faq-3" class="accordion-content hide">
                        <p class="text-[20px] leading-[36px] pt-5">Kami menerapkan pembelajaran terpadu yang mengintegrasikan nilai-nilai Islam dengan kurikulum nasional, termasuk mengaji, praktik ibadah, dan pengembangan karakter.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tambahkan script di bagian bawah -->
    @push('scripts')
    <script src="{{ asset('main.js') }}"></script>
    @endpush
@endsection



