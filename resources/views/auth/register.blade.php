@extends('layouts.login')

@section('content')
  
        <div class="card-sigin">
            <div class="main-signup-header">
                <h2 class="text-primary mb-4">Bienvenido a <img src="{{ url('assets/images/brand-logos/2desktop-logo-white.png') }}" alt="FitAdminPro Logo"
                    class="logo-luminoso mb-4"></h2>
                
               
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row">
                        <!-- Nombre de Usuario -->
                        <div class="col-md-6 form-group">
                            <input id="nombreUsuario" type="text"
                                class="form-control @error('nombreUsuario') is-invalid @enderror" name="nombreUsuario"
                                value="{{ old('nombreUsuario') }}" required autofocus placeholder=" ">
                            <label for="nombreUsuario" class="form-label">{{ __('Nombre de Usuario') }}</label>
                            @error('nombreUsuario')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="form-group col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required placeholder=" ">
                            <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Contraseña -->
                        <div class="col-md-6 form-group">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                placeholder=" ">
                            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="col-md-6 form-group">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                required placeholder=" ">
                            <label for="password-confirm" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 form-group">
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror"
                                name="nombre" value="{{ old('nombre') }}" required placeholder=" ">
                            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Primer Apellido -->
                        <div class="col-md-6 form-group">
                            <input id="primerApellido" type="text"
                                class="form-control @error('primerApellido') is-invalid @enderror" name="primerApellido"
                                value="{{ old('primerApellido') }}" required placeholder=" ">
                            <label for="primerApellido" class="form-label">{{ __('Primer Apellido') }}</label>
                            @error('primerApellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Segundo Apellido -->
                        <div class="col-md-6 form-group">
                            <input id="segundoApellido" type="text"
                                class="form-control @error('segundoApellido') is-invalid @enderror" name="segundoApellido"
                                value="{{ old('segundoApellido') }}" placeholder=" ">
                            <label for="segundoApellido" class="form-label">{{ __('Segundo Apellido') }}</label>
                            @error('segundoApellido')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="col-md-6 form-group">
                            <input id="fechaNacimiento" type="date"
                                class="form-control @error('fechaNacimiento') is-invalid @enderror" name="fechaNacimiento"
                                value="{{ old('fechaNacimiento') }}" required placeholder=" ">
                            <label for="fechaNacimiento" class="form-label">{{ __('Fecha de Nacimiento') }}</label>
                            @error('fechaNacimiento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Género -->
                        <div class="col-md-6 form-group">
                            <label for="genero" class="form-label"></label>
                            <select id="genero" class="form-select @error('genero') is-invalid @enderror" name="genero"
                                required>
                                <option value="" disabled selected>{{ __('Seleccione su género') }}</option>
                                <option value="Masculino">{{ __('Masculino') }}</option>
                                <option value="Femenino">{{ __('Femenino') }}</option>
                                <option value="Otro">{{ __('Otro') }}</option>
                            </select>
                            @error('genero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6 form-group">
                            <input id="telefono" type="text"
                                class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                value="{{ old('telefono') }}" placeholder=" ">
                            <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
                            @error('telefono')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Botón de Registro -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('Registrar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
   
@endsection
