@extends('layouts.login')
@section('title', 'Iniciar Sesión')
@section('content')
<div class="auth-page-wrapper">
    <div class="auth-one-bg-position">
        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <!-- Tarjeta de Login -->
                        <div class="card mt-4 card-bg-fill">
                            <div class="card-body p-4">
                                <!-- Encabezado -->
                                <div class="text-center mt-2">
                                    <h2 class="fw-bold">
                                        <i class="la la-dumbbell me-2 text-warning"></i>
                                        <span class="text-warning">F</span>it<span class="text-warning">A</span>dminPro
                                    </h2>
                                    <p class="text-muted">Inicia sesión para continuar.</p>
                                </div>

                                <!-- Formulario de Login -->
                                <div class="p-2 mt-4">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <!-- Campo Email -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo Electrónico</label>
                                            <input id="email" type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" value="{{ old('email') }}" 
                                                   required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Campo Contraseña -->
                                        <div class="mb-3">
                                            <div class="float-end">
                                                @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request') }}" class="text-muted">
                                                        ¿Olvidaste tu contraseña?
                                                    </a>
                                                @endif
                                            </div>
                                            
                                            <label class="form-label" for="password-input">Contraseña</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input id="password-input" type="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       name="password" required autocomplete="current-password">
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                        type="button" id="password-addon">
                                                    <i class="ri-eye-fill align-middle"></i> 
                                                </button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Recordar usuario -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">Recordarme</label>
                                        </div>

                                        <!-- Botón de Submit -->
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-success w-100">
                                                Iniciar Sesión
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Enlace a Registro -->
                                <div class="mt-4 text-center">
                                    <p class="mb-0">
                                        ¿No tienes una cuenta? 
                                        <a href="{{ route('register') }}" 
                                           class="fw-semibold text-primary text-decoration-underline">
                                            Regístrate
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <!-- Fin card body -->
                        </div>
                        <!-- Fin card -->
                    </div>
                </div>
                <!-- Fin row -->
            </div>
            <!-- Fin container -->
        </div>
        <!-- Fin auth-page-content -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.querySelector('#password-input');
        const togglePasswordButton = document.querySelector('#password-addon');
        const togglePasswordIcon = togglePasswordButton.querySelector('i');

        // Toggle para mostrar/ocultar contraseña
        togglePasswordButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            togglePasswordIcon.classList.toggle('ri-eye-fill');
            togglePasswordIcon.classList.toggle('ri-eye-off-fill');
        });

        // Mostrar loader al enviar el formulario
        const loginForm = document.querySelector('form');
        loginForm.addEventListener('submit', function(e) {
            Swal.fire({
                title: 'Iniciando sesión...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
</script>
@endpush