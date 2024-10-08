@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Formulario</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Editar</a></li>
                    <li class="breadcrumb-item active">Cliente</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Datos del Cliente</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.clientes.update', $cliente->idCliente) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row gy-4">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="nombre" class="form-label">Nombre <span style="color:red">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                            @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="primerApellido" class="form-label">Primer Apellido <span style="color:red">*</span></label>
                            <input type="text" class="form-control @error('primerApellido') is-invalid @enderror" id="primerApellido" name="primerApellido" value="{{ old('primerApellido', $cliente->primerApellido) }}" required>
                            @error('primerApellido')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control @error('segundoApellido') is-invalid @enderror" id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido', $cliente->segundoApellido) }}">
                            @error('segundoApellido')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento <span style="color:red">*</span></label>
                            <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento', \Carbon\Carbon::parse($cliente->fechaNacimiento)->format('Y-m-d')) }}" required>
                            @error('fechaNacimiento')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="genero" class="form-label">Género <span style="color:red">*</span></label>
                            <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero" required>
                                <option value="" disabled>Seleccione</option>
                                <option value="Masculino" {{ old('genero', $cliente->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $cliente->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $cliente->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="telefonoEmergencia" class="form-label">Teléfono de Emergencia</label>
                            <input type="text" class="form-control @error('telefonoEmergencia') is-invalid @enderror" id="telefonoEmergencia" name="telefonoEmergencia" value="{{ old('telefonoEmergencia', $cliente->telefonoEmergencia) }}">
                            @error('telefonoEmergencia')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Foto de Perfil</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" id="image" name="image" class="form-control">
                                    @if ($cliente->image)
                                        <small class="text-muted">Imagen actual: {{ $cliente->image }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-end">
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-danger me-2">Volver</a>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
