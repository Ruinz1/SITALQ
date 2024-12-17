@extends('layouts.master')
@section('title', 'Sejarah - TKIT Al Qolam')
@section('content')
<section id="Header" class="flex flex-col gap-[100px] bg-portto-black relative max-h-[665px] mb-[493px]">
       
    <div class="hero container max-w-[1130px] mx-auto flex flex-col justify-center items-center relative">
        <h1 class="font-extrabold text-[30px] sm:text-[50px] leading-[40px] sm:leading-[70px] text-white text-center z-10 px-4 sm:px-0">
            TK Islam Terpadu Al Qolam
        </h1>
        <div class="flex shrink-0 w-full h-[400px] sm:h-[800px] rounded-[25px] sm:rounded-[50px] overflow-hidden bg-white mt-[35px] sm:mt-[70px] z-10 mx-4 sm:mx-0">
            <img src="{{asset('assets/images/thumbnails/tujuan-1.png')}}" class="w-full h-full object-cover" alt="thumbnail">
        </div>
        <img src="{{asset('assets/images/Ellipse.svg')}}" class="absolute transform -translate-x-1/2 -translate-y-1/2 left-1/2 top-[135px] w-[35%]" alt="background icon">
    </div>
</section>
</section>
<section id="Details" class="container max-w-[1130px] mx-auto pt-[50px] px-6 sm:px-8 md:px-12 lg:px-0">
    <div class="flex flex-col sm:flex-row gap-[30px] sm:gap-[50px] justify-between">
        <div class="flex flex-col gap-5 max-w-full sm:max-w-[650px]">
            <h2 class="font-extrabold text-xl sm:text-2xl">Sejarah Singkat</h2>
            <div class="description flex flex-col gap-4 font-medium text-base sm:text-lg leading-[30px] sm:leading-[38px]">
                <p class="text-justify ">Taman Kanak-Kanak Islam Terpadu (TKIT) Al Qolam adalah Lembaga 
                    Pendidikan anak usia dini yang berada pada jalur formal sebagai lembaga kepedulian dari sekelompok orang terhadap 
                    pentingnya nilai pendidikan bagi anak usia 4-6 tahun,maka muncullah gagasan atau ide untuk membuat sebuah wadah /lembaga untuk 
                    dijadikan sebagai tempat belajar, maka muncullah gagasan untuk mendidrikan sekolah.</p>
                
                <p class="text-justify ">Dari beberapa hal yang melatarbelakangi permasalahan di atas,maka muncullah gagasan untuk mendirikan lembaga pendidikan terpadu yang 
                    dimaksudkan mampu memadukan kurikulum Diniyah(Agama) dengan kurikulum umum yang diharapkan ilmu yang akan diterima oleh murid 
                    dapat dijadikan sebagai bekal pembelajaran ketika memasuki jenjang Pendidikan Dasar.</p>
                
                <p class="text-justify ">Alhamdulillah atas karunia dari Allah Swt, dan berkat bantuan dari dermawan dan donatur, Yayasan Khairu Ummah akhirnya dapat membebaskan sebidang tanah yang berada 
                    di kompleks Perumnas Tinggede dan berkat kerja keras dan gotong royong dari semua elemen yang ada di Yayasan Khairu Ummah maka terbangunlah 1 bangunan sekolah yang 
                    terbuat dari kayu yang sangat sederhana.</p>
            </div>
            <div class="flex flex-col gap-5 mt-10">
                <h2 class="font-extrabold text-xl sm:text-2xl">Visi dan Misi</h2>
                <div class="description flex flex-col gap-4 font-medium text-base sm:text-lg leading-[30px] sm:leading-[38px]">
                    <p class="text-justify">Visi kami adalah Mencetak generasi tangguh Muslim agar menjadi manusia yang beraqidah lurus,berakhlaq karimah,cerdas,aktif dan kreatif,kuat ilmu dan amal</p>
                    
                    <p class="text-justify">Misi kami meliputi: 
                        <ul class="list-disc pl-5">
                            <li>Mendidik anak-anak dengan nilai-nilai Al Qur’an dan sunnah sedini mungkin guna membentu pribadi sholeh-sholehah </li>
                            <li>Menyediakan lingkungan belajar yang aman dan mendukung.</li>
                            <li>Membentuk anak didik untuk berorientasi pada hidup yang benar, jelas dan berperilaku baik.</li>
                            <li>Mengembangkan potensi setiap anak secara optimal.</li>
                            <li>Mengajarkan nilai-nilai moral dan etika yang kuat.</li>
                            <li>Mendorong kreativitas dan inovasi dalam pembelajaran.</li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
        {{-- <div class="flex flex-col gap-5">
            <h2 class="font-extrabold text-2xl">Software Used</h2>
            <div class="software-container flex flex-col shrink-0 gap-5 w-[325px]">
                <div class="card-software w-full flex items-center bg-[#F4F5F8] rounded-2xl p-5 gap-4 transition-all duration-300 hover:ring-2 hover:ring-portto-purple">
                    <div class="w-[70px] h-[70px] bg-white rounded-full flex shrink-0 items-center justify-center">
                        <img src="assets/images/logos/react.svg" alt="tool">
                    </div>
                    <div class="flex flex-col gap-[2px]">
                        <p class="tool-title font-bold text-xl leading-[30px]">React JS</p>
                        <p class="text-lg text-[#878C9C]">Web Framework</p>
                    </div>
                </div>
                <div class="card-software w-full flex items-center bg-[#F4F5F8] rounded-2xl p-5 gap-4 transition-all duration-300 hover:ring-2 hover:ring-portto-purple">
                    <div class="w-[70px] h-[70px] bg-white rounded-full flex shrink-0 items-center justify-center">
                        <img src="assets/images/logos/blender.svg" alt="tool">
                    </div>
                    <div class="flex flex-col gap-[2px]">
                        <p class="tool-title font-bold text-xl leading-[30px]">Blender 3D</p>
                        <p class="text-lg text-[#878C9C]">Product Modeling</p>
                    </div>
                </div>
                <div class="card-software w-full flex items-center bg-[#F4F5F8] rounded-2xl p-5 gap-4 transition-all duration-300 hover:ring-2 hover:ring-portto-purple">
                    <div class="w-[70px] h-[70px] bg-white rounded-full flex shrink-0 items-center justify-center">
                        <img src="assets/images/logos/figma.svg" alt="tool">
                    </div>
                    <div class="flex flex-col gap-[2px]">
                        <p class="tool-title font-bold text-xl leading-[30px]">Figma</p>
                        <p class="text-lg text-[#878C9C]">UI/UX Design</p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</section>
@endsection
 @push('scripts')
    <script src="{{ asset('main.js') }}"></script>
    @endpush
