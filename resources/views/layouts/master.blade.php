<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/logos/logo-tk-circle.png') }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logo-tk-circle.png') }}">
  <link href="{{asset('output.css')}}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    [x-cloak] { display: none !important; }
    </style>
  <title>@yield('title', 'TKIT Al Qolam - Taman Kanak-Kanak Islam Terpadu')</title>
</head>

<body class="text-portto-black font-poppins">
  
  @unless(request()->routeIs('front.daftar'))
    <x-navbar />
  @endunless
  @yield('content')
    
    @include('layouts.footer')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('sweetalert'))
    <script>
        Swal.fire({
            icon: '{{ session("sweetalert.icon") }}',
            title: '{{ session("sweetalert.title") }}',
            text: '{{ session("sweetalert.text") }}',
            confirmButtonColor: '#4920E5'
        });
    </script>
    @endif

    @stack('scripts')
    @stack('styles')

    <!-- Banner persetujuan cookies dengan desain yang lebih baik -->
    <div id="cookieConsent" style="display: none;" class="fixed bottom-0 left-0 right-0 bg-gray-900/95 backdrop-blur-sm text-white py-4 px-6 shadow-lg z-50">
        <div class="container mx-auto max-w-screen-xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <p class="text-sm">Kami menggunakan cookies untuk meningkatkan pengalaman Anda.</p>
                <button id="acceptCookies" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Terima</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consentBanner = document.getElementById('cookieConsent');
            const acceptButton = document.getElementById('acceptCookies');

            if (!localStorage.getItem('cookiesAccepted')) {
                consentBanner.style.display = 'block';
            }

            acceptButton.addEventListener('click', function() {
                localStorage.setItem('cookiesAccepted', 'true');
                consentBanner.style.display = 'none';
            });
        });
    </script>
</body>

</html>