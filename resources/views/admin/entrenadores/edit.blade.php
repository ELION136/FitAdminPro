@extends('layouts.admin')

@section('content')
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title fs-21 mb-1">Editar Entrenador</h5>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.entrenadores.index') }}">Entrenador</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Datos del Entrenador
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.entrenadores.update', $entrenador->idEntrenador) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre', $entrenador->nombre) }}" required>
                                @error('nombre')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="primerApellido" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control @error('primerApellido') is-invalid @enderror"
                                    id="primerApellido" name="primerApellido"
                                    value="{{ old('primerApellido', $entrenador->primerApellido) }}" required>
                                @error('primerApellido')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control @error('segundoApellido') is-invalid @enderror"
                                    id="segundoApellido" name="segundoApellido"
                                    value="{{ old('segundoApellido', $entrenador->segundoApellido) }}">
                                @error('segundoApellido')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror"
                                    id="fechaNacimiento" name="fechaNacimiento"
                                    value="{{ old('fechaNacimiento', \Carbon\Carbon::parse($entrenador->fechaNacimiento)->format('Y-m-d')) }}"
                                    required>
                                @error('fechaNacimiento')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-control @error('genero') is-invalid @enderror" id="genero"
                                    name="genero" required>
                                    <option value="" disabled>Seleccione</option>
                                    <option value="Masculino"
                                        {{ old('genero', $entrenador->genero) == 'Masculino' ? 'selected' : '' }}>Masculino
                                    </option>
                                    <option value="Femenino"
                                        {{ old('genero', $entrenador->genero) == 'Femenino' ? 'selected' : '' }}>Femenino
                                    </option>
                                </select>
                                @error('genero')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fechaContratacion" class="form-label">Fecha de Contratación</label>
                                <input type="date" class="form-control @error('fechaContratacion') is-invalid @enderror"
                                    id="fechaContratacion" name="fechaContratacion"
                                    value="{{ old('fechaContratacion', \Carbon\Carbon::parse($entrenador->fechaContratacion)->format('Y-m-d')) }}"
                                    required>
                                @error('fechaContratacion')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                                @error('email')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control @error('nombreUsuario') is-invalid @enderror"
                                    id="nombreUsuario" name="nombreUsuario"
                                    value="{{ old('nombreUsuario', $usuario->nombreUsuario) }}" required>
                                @error('nombreUsuario')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                                @error('telefono')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="image" class="form-label">Imagen</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="direccion" class="form-label">direccion</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" value="{{ old('direccion', $usuario->direccion) }}">
                                @error('direccion')
                                    <small style="color:red">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-end">
                                <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.entrenadores.index') }}" class="btn btn-danger me-2">Volver</a>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
                <div class="card-footer d-none border-top-0"></div>
            </div>
        </div>
    </div>
@endsection
