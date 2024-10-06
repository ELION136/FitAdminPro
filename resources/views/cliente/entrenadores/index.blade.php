{{-- resources/views/entrenadores/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Catalogos</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Entrenadores</a></li>
                        <li class="breadcrumb-item active">catalogo</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between gy-3">
                        <form method="GET" action="{{ route('cliente.entrenadores.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre"
                                        value="{{ request('nombre') }}">
                                </div>
                                <div class="col-md-4">
                                    <select name="especialidad" class="form-control bg-dark text-white border-secondary">
                                        <option value="">Todas las especialidades</option>
                                        <option value="Entrenamiento Personal"
                                            {{ request('especialidad') == 'Entrenamiento Personal' ? 'selected' : '' }}>Entrenamiento
                                            Personal</option>
                                        <option value="Entrenamiento Cardiovascular"
                                            {{ request('especialidad') == 'Entrenamiento Cardiovascular' ? 'selected' : '' }}>Entrenamiento
                                            Cardiovascular</option>
                                        <option value="Boxeo" {{ request('especialidad') == 'Boxeo' ? 'selected' : '' }}>Boxeo</option>
                                        <option value="Entrenamiento de Resistencia"
                                            {{ request('especialidad') == 'Entrenamiento de Resistencia' ? 'selected' : '' }}>Entrenamiento
                                            de Resistencia</option>
                                        <option value="Nutrición y Bienestar"
                                            {{ request('especialidad') == 'Nutrición y Bienestar' ? 'selected' : '' }}>Nutrición y Bienestar
                                        </option>
                                        <option value="Otro" {{ request('especialidad') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info"><i class="ri-filter-2-line me-1 align-bottom"></i>Filtrar</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>

  
    <div class="row">
        @forelse($entrenadores as $entrenador)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <br>
                    <center>

                        @if ($entrenador->usuario->image)
                            <img src="{{ asset('storage/' . $entrenador->usuario->image) }}"
                                alt="Foto de perfil de {{ $entrenador->nombre }}" width="100" height="100">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}"
                                alt="Foto de perfil de {{ $entrenador->nombre }}" width="100" height="100">
                        @endif
                    </center>

                    <div class="card-body">
                        <h5 class="card-title">{{ $entrenador->nombre }} {{ $entrenador->primerApellido }}
                            {{ $entrenador->segundoApellido }}</h5>
                        <p class="card-text">
                            <strong>Especialidad:</strong> {{ $entrenador->especialidad }}<br>
                            <strong>Género:</strong> {{ $entrenador->genero }}<br>
                            <strong>Descripción:</strong> {{ $entrenador->descripcion }}
                        </p>
                        <p class="card-text">
                            <strong>Fecha de Contratación:</strong>
                            {{ $entrenador->fechaContratacion->format('d/m/Y') }}<br>
                            <strong>Teléfono:</strong> {{ $entrenador->usuario->telefono }}<br>
                            <strong>Dirección:</strong> {{ $entrenador->direccion }}
                        </p>
                        <p><strong>Disponibilidad:</strong> {{ $entrenador->horario }}</p>
                        <div class="d-flex justify-content-between">
                            <a href="tel:{{ $entrenador->usuario->telefono }}" class="btn btn-primary">
                                <i class="fas fa-phone-alt"></i> Llamar
                            </a>
                            <a href="mailto:{{ $entrenador->usuario->email }}" class="btn btn-secondary">
                                <i class="fas fa-envelope"></i> Correo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No se encontraron entrenadores con esta especialidad.</p>
            </div>
        @endforelse
    </div>

    </div>
@endsection
