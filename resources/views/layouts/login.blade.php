<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="saas" data-theme-colors="default" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'FitAdminPro')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Premium Multipurpose Admin & Dashboard Template">
    <meta name="author" content="Themesbrand">

    <link rel="shortcut icon" href="{{ url('dist/assets/images/logo11.png') }}">

    <link href="{{ url('dist/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet">
    <link href="{{ url('dist/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('dist/assets/css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ url('dist/assets/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ url('dist/assets/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('package/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <style>
        .auth-one-bg-position {
            background: linear-gradient(to bottom, rgba(33, 32, 32, 0.8), rgba(0, 0, 0, 0.8)), url('{{ url('styles/img/f3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('head')
</head>

<body>
    @if (($message = Session::get('mensaje')) && ($icono = Session::get('icono')))
        <script>
            Swal.fire({
                icon: "{{ $icono }}",
                title: "{{ $message }}",
                showConfirmButton: false,
                timer: 4500
            });
        </script>
    @endif

    <div class="auth-one-bg-position">
        @yield('content')
    </div>

    <script src="{{ url('dist/assets/js/layout.js') }}"></script>
    <script src="{{ url('dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('package/dist/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ url('dist/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <script src="{{ url('dist/assets/js/app.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/password-addon.init.js') }}"></script>
    <script src="{{ url('dist/assets/libs/particles.js/particles.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/particles.app.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mybutton = document.getElementById("back-to-top");
            
            if (mybutton) {
                window.onscroll = function() {
                    mybutton.style.display = (document.body.scrollTop > 100 || 
                                           document.documentElement.scrollTop > 100) ? "block" : "none";
                };

                mybutton.addEventListener("click", function() {
                    window.scrollTo({top: 0, behavior: 'smooth'});
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>