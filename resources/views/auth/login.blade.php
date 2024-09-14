<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>FitAdminPro</title>
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




    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">



    
    <style>
        
        
        .custom-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            background: linear-gradient(rgba(26, 29, 32, 0.75), rgba(1, 1, 1, 0.413)),
                url('{{ url('styles/img/f3.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
            
            
        }

        .card-sigin {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            margin: 20px;
            background-color: rgba(255, 255, 255, 0.05);
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
            border-color: #e2b44a;
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
            color: #edf0f3;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 12px;
            color: #e2914a;
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
            border-color: #f0950c;
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
            color: #e6813e !important;
        }

        .form-check-label, .text-muted {
            color: #e3e8ee !important;
        }

        a {
            color: #e2a04a;
        }

        a:hover {
            color: #d5873a;
        }

        .logo-luminoso {
        max-height: 40px; /* Ajusta la altura del logo */
        vertical-align: middle;
        filter: drop-shadow(0 0 10px rgba(226, 155, 74, 0.7)); /* Sombra luminosa alrededor del logo */
        transition: all 0.3s ease; /* Transición suave para los efectos */
        }

        .logo-luminoso:hover {
        filter: drop-shadow(0 0 20px rgb(226, 137, 74)) brightness(1.2); /* Aumenta el brillo al pasar el cursor */
        transform: scale(1.05); /* Pequeño efecto de zoom */
        }
    </style>
</head>

<body>
    
    
    <div class="container-fluid custom-page">
        <div class="card-sigin">
            <div class="main-signup-header">
                <h2 class="text-primary mb-2">Bienvenido! a <br>
                    <img src="{{ url('dist/assets/images/logo3.png') }}" alt="FitAdminPro Logo" class="logo-luminoso">
                </h2>
                
                
                <h5 class="fw-normal text-muted mb-4">Por favor inicie sesión para continuar</h5>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus placeholder=" ">
                        <label for="email" class="form-label"><i class="bi bi-envelope-at"></i>  </i>Correo Electrónico</label>
                        @error('email')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                            required autocomplete="current-password" placeholder=" ">
                        <label for="password" class="form-label"><i class="bi bi-unlock"></i>  </i>Contraseña</label>
                        @error('password')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>

                    <div class="row align-items-center mb-4">
                        <div class="col-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recuérdame</label>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            @if (Route::has('password.request'))
                            <a class="text-muted" href="{{ route('password.request') }}">
                                ¿Olvidó su contraseña?
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">Iniciar sesión</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-primary">Regístrate</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('dist/assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ url('dist/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ url('dist/assets/js/plugins.js') }}"></script>
    
    
</body>
</html>