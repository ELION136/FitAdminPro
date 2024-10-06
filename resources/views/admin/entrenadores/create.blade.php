@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Formulario</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Añadir</a></li>
                        <li class="breadcrumb-item active">Entrenador</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Formulario</h4>

                </div><!-- end card header -->
                <div class="card-body">
                    <form action="{{ route('admin.entrenadores.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="nombre" class="form-label">Nombre<span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre') }}" required>

                                @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="primerApellido" class="form-label">Primer Apellido<span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control @error('primerApellido') is-invalid @enderror"
                                    id="primerApellido" name="primerApellido" value="{{ old('primerApellido') }}" required>
                                <small class="error-message"></small>
                                @error('primerApellido')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control @error('segundoApellido') is-invalid @enderror"
                                    id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido') }}">
                                @error('segundoApellido')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento<span
                                        style="color: red">*</span></label>
                                <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror"
                                    id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}"
                                    max="{{ date('Y-m-d') }}" required>
                                @error('fechaNacimiento')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="genero" class="form-label">Género<span style="color: red">*</span></label>
                                <select class="form-control @error('genero') is-invalid @enderror" id="genero"
                                    name="genero" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">otro</option>
                                </select>
                                @error('genero')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="fechaContratacion" class="form-label">Fecha de Contratación<span
                                        style="color: red">*</span></label>
                                <input type="date" class="form-control @error('fechaContratacion') is-invalid @enderror"
                                    id="fechaContratacion" name="fechaContratacion" value="{{ old('fechaContratacion') }}"
                                    max="{{ date('Y-m-d') }}" required>
                                @error('fechaContratacion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="email" class="form-label">Email<span style="color: red">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="nombreUsuario" class="form-label">Nombre de Usuario<span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control @error('nombreUsuario') is-invalid @enderror"
                                    id="nombreUsuario" name="nombreUsuario" value="{{ old('nombreUsuario') }}" required>
                                @error('nombreUsuario')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono') }}">
                                @error('telefono')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="direccion" class="form-label">direccion</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" value="{{ old('direccion') }}">
                                @error('profesion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="especialidad" class="form-label">Especialidad <span
                                        style="color: red">*</span></label>
                                <select class="form-control @error('especialidad') is-invalid @enderror"
                                    id="especialidad" name="especialidad">
                                    <option value="" disabled selected>Seleccione una especialidad</option>
                                    <option value="Entrenamiento Personal"
                                        {{ old('especialidad') == 'Entrenamiento Personal' ? 'selected' : '' }}>
                                        Entrenamiento Personal</option>
                                    <option value="Entrenamiento Cardiovascular"
                                        {{ old('especialidad') == 'Entrenamiento Cardiovascular' ? 'selected' : '' }}>
                                        Entrenamiento Cardiovascular</option>
                                    <option value="Boxeo" {{ old('especialidad') == 'Boxeo' ? 'selected' : '' }}>Boxeo
                                    </option>
                                    <option value="Entrenamiento de Resistencia"
                                        {{ old('especialidad') == 'Entrenamiento de Resistencia' ? 'selected' : '' }}>
                                        Entrenamiento de Resistencia</option>
                                    <option value="Nutrición y Bienestar"
                                        {{ old('especialidad') == 'Nutrición y Bienestar' ? 'selected' : '' }}>Nutrición y
                                        Bienestar</option>
                                    <option value="otro" {{ old('especialidad') == 'otro' ? 'selected' : '' }}>otro
                                    </option>

                                </select>
                                @error('especialidad')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="descripcion" class="form-label">Descripcion</label>
                                <textarea class="form-control" name="descripcion" rows="2"></textarea>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.entrenadores.index') }}"
                                        class="btn btn-danger me-2">Volver</a>
                                    <button type="submit" class="btn btn-primary">Crear un nuevo registro</button>
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
