@extends('layouts.admin')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Crear Empleado</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.empleados.index') }}">Empleados</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Datos del empleado
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.empleados.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="primerApellido" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control @error('primerApellido') is-invalid @enderror" id="primerApellido" name="primerApellido" value="{{ old('primerApellido') }}" required>
                                @error('primerApellido')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control @error('segundoApellido') is-invalid @enderror" id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido') }}">
                                @error('segundoApellido')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" required>
                                @error('fechaNacimiento')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                                @error('genero')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fechaContratacion" class="form-label">Fecha de Contratación</label>
                                <input type="date" class="form-control @error('fechaContratacion') is-invalid @enderror" id="fechaContratacion" name="fechaContratacion" value="{{ old('fechaContratacion') }}" required>
                                @error('fechaContratacion')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control @error('nombreUsuario') is-invalid @enderror" id="nombreUsuario" name="nombreUsuario" value="{{ old('nombreUsuario') }}" required>
                                @error('nombreUsuario')
                                <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                                @error('telefono')
                                <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="image" class="form-label">Imagen</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                @error('image')
                                <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">Crear</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer d-none border-top-0"></div>
            </div>
        </div>
    </div>
@endsection
