<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="dark"
    data-header-styles="dark" data-menu-styles="dark" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FitAdminPro</title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- Favicon -->
    <link rel="icon" href="{{ url('assets/images/brand-logos/toggle-logo2.png') }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ url('assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ url('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ url('assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">



    
    <style>
        
        
        .custom-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            background: linear-gradient(rgba(12, 54, 94, 0.5), rgba(9, 50, 73, 0.5)),
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
            border-radius: 8px;
            padding: 1.25rem;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .form-control:focus {
            border-color: #4a90e2;
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
            color: #a0aec0;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -0.5rem;
            left: 0.75rem;
            font-size: 12px;
            color: #4a90e2;
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
            background-color: #4a90e2;
            border-color: #4a90e2;
        }

        .btn-primary:hover {
            background-color: #3a7bd5;
            border-color: #3a7bd5;
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

        .form-check-label, .text-muted {
            color: #d9e7f8 !important;
        }

        a {
            color: #4a90e2;
        }

        a:hover {
            color: #3a7bd5;
        }

        .logo-luminoso {
        max-height: 40px; /* Ajusta la altura del logo */
        vertical-align: middle;
        filter: drop-shadow(0 0 10px rgba(74, 144, 226, 0.7)); /* Sombra luminosa alrededor del logo */
        transition: all 0.3s ease; /* Transición suave para los efectos */
        }

        .logo-luminoso:hover {
        filter: drop-shadow(0 0 20px rgba(74, 144, 226, 1)) brightness(1.2); /* Aumenta el brillo al pasar el cursor */
        transform: scale(1.05); /* Pequeño efecto de zoom */
        }
    </style>
</head>

<body>
    
    
    <div class="container-fluid custom-page">
        <div class="card-sigin">
            <div class="main-signup-header">
                <h2 class="text-primary mb-2">Bienvenido! a <br>
                    <img src="{{ url('assets/images/brand-logos/2desktop-logo-white.png') }}" alt="FitAdminPro Logo" class="logo-luminoso">
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
    <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    
</body>
</html>