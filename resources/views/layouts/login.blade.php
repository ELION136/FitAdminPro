<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="sass" data-theme-colors="default" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>FitAdminPro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />


    <link rel="shortcut icon" href="{{ url('dist/assets/images/favicon.ico') }}">

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
        .custom-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            background: linear-gradient(rgba(2, 39, 75, 0.75), rgba(48, 63, 78, 0.413)),
                url('{{ url('styles/img/f3.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;


        }

        .card-sigin {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            max-width: 800px;
            width: 100%;
            margin: 20px;
            background-color: rgba(22, 41, 75, 0.076);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 6px;
            padding: 1rem;
            font-size: 12px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .form-control:focus {
            border-color: #e2a34a;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.25);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .form-label {
            position: absolute;
            top: 50%;
            left: 1.25rem;
            transform: translateY(-50%);
            background-color: transparent;
            padding: 0 0.25rem;
            transition: all 0.2s;
            color: #f4f5f7;
        }

        .form-control:focus+.form-label,
        .form-control:not(:placeholder-shown)+.form-label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 12px;
            color: #e2964a;
        }

        .is-invalid {
            border-color: #e53e3e;
        }

        .invalid-feedback {
            display: block;
            color: #e53e3e;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .btn-primary {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            background-color: #ec7d0d;
            border-color: #0c0b0b;
        }

        .btn-primary:hover {
            background-color: #f34814;
            border-color: #f22e0c;
        }

        .main-signup-header {
            text-align: center;
        }

        .main-signup-header h2 {
            font-weight: bold;
            color: #ffffff;
        }

        .main-signup-header h5 {
            color: #a0aec0;
        }

        .text-primary {
            color: #83b4eb !important;
        }

        .form-check-label,
        .text-muted {
            color: #d9e7f8 !important;
        }

        a {
            color: #e29e4a;
        }

        a:hover {
            color: #d57d3a;
        }

        .logo-luminoso {
            max-height: 40px;
            /* Ajusta la altura del logo */
            vertical-align: middle;
            filter: drop-shadow(0 0 10px rgba(226, 158, 74, 0.7));
            /* Sombra luminosa alrededor del logo */
            transition: all 0.3s ease;
            /* Transición suave para los efectos */
        }

        .logo-luminoso:hover {
            filter: drop-shadow(0 0 20px rgb(226, 158, 74)) brightness(1.2);
            /* Aumenta el brillo al pasar el cursor */
            transform: scale(1.05);
            /* Pequeño efecto de zoom */
        }
    </style>
</head>

<body>


    <div class="container-fluid custom-page">



        @yield('content')

        <!-- Scripts -->


    </div>

    <script src="{{ url('dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ url('dist/assets/js/plugins.js') }}"></script>


</body>

</html>
