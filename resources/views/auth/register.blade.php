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
                                        <h5 class="text-primary">Registrar</h5>
                                        <p class="text-muted">Crea una nueva cuenta para continuar.</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <!-- Formulario de registro -->
                                        <form method="POST" action="{{ route('register') }}">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="nombreUsuario" class="form-label">Nombre</label>
                                                <input id="nombreUsuario" type="text"
                                                    class="form-control @error('nombreUsuario') is-invalid @enderror" name="nombreUsuario"
                                                    value="{{ old('nombreUsuario') }}" required autocomplete="nombreUsuario" autofocus>

                                                @error('nombreUsuario')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Correo Electrónico</label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Contraseña</label>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="new-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="password-confirm" class="form-label">Confirmar
                                                    Contraseña</label>
                                                <input id="password-confirm" type="password" class="form-control"
                                                    name="password_confirmation" required autocomplete="new-password">
                                            </div>

                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success w-100">Registrar</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="mt-4 text-center">
                                        <p class="mb-0">¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                                                class="fw-semibold text-primary text-decoration-underline">Inicia Sesión</a>
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
