<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image=img-4 data-sidebar-image="none" data-preloader="disable" data-theme="saas"
    data-theme-colors="default" data-bs-theme="ligth">


<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'FitAdminPro')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <link rel="shortcut icon" href="{{ url('dist/assets/images/logo1.png') }}">

    <!-- plugin css -->
    <link href="{{ url('dist/assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Layout config Js -->
    <script src="{{ url('dist/assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ url('dist/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ url('dist/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('dist/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ url('dist/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('package/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('package/dist/sweetalert2.all.min.js') }}"></script>

    <style>
        /* Fondo que ocupa toda la pantalla */
        .auth-one-bg-position {
            background: linear-gradient(to bottom, rgba(33, 32, 32, 0.8), rgba(0, 0, 0, 0.8)), url('{{ url('styles/img/f3.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            /* Ocupar toda la altura de la ventana */
            width: 100%;
            /* Ocupar toda la anchura de la ventana */
            display: flex;
            align-items: center;
            /* Centrar contenido verticalmente */
            justify-content: center;
            /* Centrar contenido horizontalmente */
        }
    </style>


    @stack('head') <!-- Sección para incluir scripts o estilos adicionales -->
</head>

<body>

    <div class="auth-one-bg-position">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ url('dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ url('dist/assets/js/plugins.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/datatables.init.js') }}"></script>
    <!-- apexcharts -->
    <script src="{{ url('dist/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Vector map-->
    <script src="{{ url('dist/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/profile-setting.init.js') }}"></script>
    <!-- calendar min js -->
    <script src="{{ url('dist/assets/libs/fullcalendar/index.global.min.js') }}"></script>
    <!-- Calendar init -->
    <script src="{{ url('dist/assets/js/pages/calendar.init.js') }}"></script>
    <!-- Dashboard init -->
    <script src="{{ url('dist/assets/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/dashboard-projects.init.js') }}"></script>
    <!-- App js -->
    @stack('scripts') <!-- Para incluir scripts adicionales en vistas específicas -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var mybutton = document.getElementById("back-to-top");

            function scrollFunction() {
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
            }

            function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }

            if (mybutton) {
                window.onscroll = function() {
                    scrollFunction();
                };

                mybutton.addEventListener("click", topFunction);
            }
        });
    </script>
    <script src="{{ url('dist/assets/js/app.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/password-addon.init.js') }}"></script>
    <!-- particles js -->
    <script src="{{ url('dist/assets/libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ url('dist/assets/js/pages/particles.app.js') }}"></script>
</body>

</html>
