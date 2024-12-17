<section id="Header" class="flex flex-col gap-[50px] sm:gap-[100px] bg-portto-black relative">
<nav class="container max-w-[1130px] mx-auto flex justify-between items-center pt-[30px] z-10 px-6 sm:px-0">
    @php
        $pendaftaran = App\Models\Pendaftaran::where('status', '1')->first();
    @endphp
    <a href="{{ route('front.index') }}" class="flex shrink-0 h-fit w-fit">
        <img src="{{asset('assets/images/logos/logo-tk.png')}}" alt="logo" class="h-20 w-auto">
    </a>
    <button class="open-mobile-nav w-6 h-6 flex shrink-0 flex sm:hidden">
        <img src="{{asset('assets/images/icons/menu.svg')}}" alt="icon">
    </button>
    <div class="hidden sm:flex gap-[50px] items-center">
        <ul class="flex gap-[50px] items-center text-white">
            <li>
                <a href="{{ route('front.index') }}" 
                   class="font-medium text-lg hover:text-portto-light-gold transition-all duration-300 {{ request()->routeIs('front.index') ? 'text-portto-light-gold' : '' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('front.sejarah') }}"  
                   class="font-medium text-lg hover:text-portto-light-gold transition-all duration-300 {{ request()->routeIs('front.sarana') ? 'text-portto-light-gold' : '' }}">
                    Sejarah
                </a>
            </li>

            <li>
                <a href="{{ route('front.akademik') }}"  
                   class="font-medium text-lg hover:text-portto-light-gold transition-all duration-300 ">
                    Akademik
                </a>
            </li>
        </ul>
        @if($pendaftaran)
        
        <a href="{{ route('front.daftar') }}" class="bg-portto-light-gold font-bold text-lg p-[14px_30px] rounded-full transition-all duration-300 hover:shadow-[0_10px_20px_0_#FFE7C280]">Daftar Peserta Didik Baru</a>
        @endif
    </div>
</nav>
{{-- Mobile Nav --}}
<div class="mobile-nav hidden flex max-w-[330px] w-full bg-white h-screen fixed top-0 right-0 z-50 items-center justify-center">
    <div class="flex flex-col gap-[50px]">
        <ul class="flex flex-col gap-[50px] items-center text-white">
            <li>
                <a href="{{ route('front.index') }}"  
                   class="font-medium text-lg text-[#0B0B1B] text-center hover:text-portto-light-gold transition-all duration-300 {{ request()->routeIs('front.index') ? 'text-portto-light-gold' : '' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('front.sejarah') }}"  
                   class="font-medium text-lg text-[#0B0B1B] text-center hover:text-portto-light-gold transition-all duration-300 ">
                    Sejarah
                </a>
            </li>
            <li>
                <a href="{{ route('front.akademik') }}"  
                   class="font-medium text-lg text-[#0B0B1B] text-center hover:text-portto-light-gold transition-all duration-300 {{ request()->routeIs('front.akademik') ? 'text-portto-light-gold' : '' }}">
                    Akademik
                </a>
            </li>
        </ul>
        @if($pendaftaran)
        
        <a href="{{ route('front.daftar') }}" class="bg-portto-light-gold font-bold text-lg p-[14px_30px] rounded-full transition-all duration-300 hover:shadow-[0_10px_20px_0_#FFE7C280]">Daftar Peserta Didik Baru</a>
        @endif
        {{-- <a href="" class="bg-portto-light-gold font-bold text-lg p-[14px_30px] rounded-full transition-all duration-300 hover:shadow-[0_10px_20px_0_#FFE7C280]">Hire Me</a> --}}
    </div>
    <button class="open-mobile-nav w-9 h-9 flex shrink-0 absolute top-[50px] right-[30px]">
        <img src="{{asset('assets/images/icons/close-circle.svg')}}" alt="icon">
    </button>
</div>