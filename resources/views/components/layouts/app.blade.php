<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'GUBALL เว็บทายผลบอลออนไลน์ แหล่งรวมเหล่าเซียนบอล' }}</title>
        <link rel="icon" type="image/x-icon" href="assets/front/img/favicon.png">

        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">
        <meta name="google-site-verification" content="ASNfa4CysetzOaTrUZqOqBigIcqVwsMd5Plrw0KXgYY" />


        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Libraries Stylesheet -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <link rel="stylesheet" href="{{asset('assets/front/lib/animate/animate.css')}}">
        <link rel="stylesheet" href="{{asset('assets/front/lib/owlcarousel/assets/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/front/lib/lightbox/css/lightbox.min.css')}}">


        <!-- Customized Bootstrap Stylesheet -->
        <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">

        <!-- Template Stylesheet -->
        <link rel="stylesheet" href="{{asset('assets/front/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/front/css/custom.css')}}">
        @livewireStyles
        @stack('styles')
    </head>
    <body class="d-flex flex-column min-vh-100 bg-bb">
        @livewire('layouts.navbar')
        {{ $slot }}
        <main class="flex-fill">
            <!-- Your page content -->
        </main>
        @livewire('layouts.footer')



        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{asset('assets/front/lib/wow/wow.js')}}"></script>
        <script src="{{asset('assets/front/lib/easing/easing.js')}}"></script>
        <script src="{{asset('assets/front/lib/waypoints/waypoints.min.js')}}"></script>
        <script src="{{asset('assets/front/lib/counterup/counterup.min.js')}}"></script>
        <script src="{{asset('assets/front/lib/owlcarousel/owl.carousel.js')}}"></script>
        <script src="{{asset('assets/front/lib/lightbox/js/lightbox.min.js')}}"></script>


        <!-- Template Javascript -->
        <script src="{{asset('assets/front/js/main.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireScripts
        @livewire('wire-elements-modal')
        @stack('scripts')
        <script>
            document.addEventListener('livewire:init', function () {
                // Listen for the sweet-alert event
                Livewire.on('sweet-alert', data => {
                    Swal.fire({
                        icon: data[0].type,
                        title: data[0].title,
                        showConfirmButton: false,
                        timer: 2000
                    });
                });

                // Listen for the redirect-after-delay event
                window.addEventListener('redirect-after-delay', function (event) {
                    setTimeout(function () {
                        window.location.href = '/'+event.detail[0].path;
                    }, 2000); // 2 seconds delay
                });
            });
        </script>
    </body>
</html>
