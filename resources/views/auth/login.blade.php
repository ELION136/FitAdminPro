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
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #0a192f; /* Dark blue night color */
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: #0a192f; /* Dark blue night color */
            z-index: -1;
        }
        
        .custom-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            z-index: 1;
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
            color: #4a90e2 !important;
        }

        .form-check-label, .text-muted {
            color: #a0aec0 !important;
        }

        a {
            color: #4a90e2;
        }

        a:hover {
            color: #3a7bd5;
        }
    </style>
</head>

<body>
    <div id="particles-js"></div>
    
    <div class="container-fluid custom-page">
        <div class="card-sigin">
            <div class="main-signup-header">
                <h2 class="text-primary mb-2">Bienvenido! a FitAdminPro</h2>
                
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
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS("particles-js", {
                particles: {
                    number: { value: 80, density: { enable: true, value_area: 800 } },
                    color: { value: "#4a90e2" },
                    shape: { type: "circle", stroke: { width: 0, color: "#000000" }, polygon: { nb_sides: 5 } },
                    opacity: { value: 0.5, random: false, anim: { enable: false, speed: 1, opacity_min: 0.1, sync: false } },
                    size: { value: 3, random: true, anim: { enable: false, speed: 40, size_min: 0.1, sync: false } },
                    line_linked: { enable: true, distance: 150, color: "#4a90e2", opacity: 0.4, width: 1 },
                    move: { enable: true, speed: 6, direction: "none", random: false, straight: false, out_mode: "out", bounce: false, attract: { enable: false, rotateX: 600, rotateY: 1200 } }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "repulse" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    },
                    modes: {
                        grab: { distance: 400, line_linked: { opacity: 1 } },
                        bubble: { distance: 400, size: 40, duration: 2, opacity: 8, speed: 3 },
                        repulse: { distance: 200, duration: 0.4 },
                        push: { particles_nb: 4 },
                        remove: { particles_nb: 2 }
                    }
                },
                retina_detect: true
            });
        });
    </script>
</body>
</html>