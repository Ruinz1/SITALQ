<footer class="bg-portto-black text-white pb-[50px] border-t-[10px] border-portto-purple px-[30px] sm:px-0">
    <div class="container max-w-[1130px] mx-auto flex flex-col sm:flex-row justify-between pt-[50px] sm:pt-[100px] pb-[50px] mb-[50px] relative border-b border-[#585867] gap-[50px] sm:gap-0">
        <img src="{{asset('assets/images/Ellipse.svg')}}" class="absolute h-[300px] top-[70px] right-0 sm:-left-[20px] z-0" alt="icon">
        <div class="flex shrink-0 h-fit z-10">
            <img src="{{asset('assets/images/logos/logo-tk.png')}}" alt="logo" class="h-20 w-auto">
        </div>
        <div class="flex flex-col sm:flex-row gap-[30px] sm:gap-[100px] z-10">
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Services</p>
                <a href="{{route('front.akademik')}}" class="font-medium hover:font-semibold hover:text-portto-light-gold transition-all duration-300">Akademik</a>
                
            </div>
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">About</p>
                <a href="{{route('front.sejarah')}}" class="font-medium hover:font-semibold hover:text-portto-light-gold transition-all duration-300">Sejarah</a>
                
            </div>
            <div class="flex flex-col gap-5">
                <p class="font-bold text-lg">Connect</p>
                <a href="https://wa.me/6282157629776?text=Assalamualaikum%20TK%20Islam%20Terpadu%20AL-QOLAM" target="_blank" class="font-medium hover:font-semibold hover:text-portto-light-gold transition-all duration-300 flex items-center gap-[6px]">
                    <img src="assets/images/icons/call.svg" alt="icon">+62 821-5762-9776
                </a>
                <a href="{{route('front.index')}}" class="font-medium hover:font-semibold hover:text-portto-light-gold transition-all duration-300 flex items-center gap-[6px]"><img src="assets/images/icons/dribbble.svg" alt="icon">TK Islam Terpadu AL-QOLAM</a>
                <a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=TKIslamTerpaduAlQolam@gmail.com" class="font-medium hover:font-semibold hover:text-portto-light-gold transition-all duration-300 flex items-center gap-[6px]"><img src="assets/images/icons/sms.svg" alt="icon">TKIslamTerpaduAlQolam@gmail.com</a>
            </div>
        </div>
    </div>
    <p class="text-sm text-[#A0A0AC] sm:text-center"> All Rights Reserved. ©TKIT AL-QOLAM 2023.</p>
</footer>