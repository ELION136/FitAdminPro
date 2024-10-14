@extends('layouts.login')

@section('content')
    <div class="auth-page-wrapper">
        <!-- Fondo de la página de autenticación -->
        <div class="auth-one-bg-position">
            <div class="auth-page-content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card mt-4 card-bg-fill">
                                <div class="card-body p-4">
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">Bienvenido de nuevo!</h5>
                                        <p class="text-muted">Inicia sesión para continuar.</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <!-- Formulario de inicio de sesión -->
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Correo Electrónico</label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">


                                                <div class="float-end">
                                                    @if (Route::has('password.request'))
                                                        <a href="{{ route('password.request') }}"
                                                            class="text-muted">¿Olvidaste tu contraseña?</a>
                                                    @endif
                                                </div>
                                                <label class="form-label" for="password-input">Contraseña</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input id="password-input" type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" required autocomplete="current-password">
                                                    <button
                                                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none"
                                                        type="button" id="password-addon">
                                                        <i class="ri-eye-fill align-middle"></i> <!-- Ícono de "ojito" -->
                                                    </button>
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>


                                            </div>


                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">Recordarme</label>
                                            </div>

                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success w-100">Iniciar Sesión</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">¿No tienes una cuenta? <a href="{{ route('register') }}"
                                                class="fw-semibold text-primary text-decoration-underline"> Regístrate </a>
                                        </p>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->


                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end auth-page-content -->
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.querySelector('#password-input');
        const togglePasswordButton = document.querySelector('#password-addon');
        const togglePasswordIcon = togglePasswordButton.querySelector('i');

        togglePasswordButton.addEventListener('click', function() {
            // Alterna el tipo de input entre 'password' y 'text'
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Alterna el ícono entre 'ri-eye-fill' y 'ri-eye-off-fill'
            togglePasswordIcon.classList.toggle('ri-eye-fill');
            togglePasswordIcon.classList.toggle('ri-eye-off-fill');
        });

        // Añade un event listener para el envío del formulario
        const loginForm = document.querySelector('form');
        loginForm.addEventListener('submit', function(e) {
            // Muestra el SweetAlert de carga
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
