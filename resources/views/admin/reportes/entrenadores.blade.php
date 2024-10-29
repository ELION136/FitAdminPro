@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-user-line me-2"></i> Reporte de Entrenadores</h1>
    </div>
    <div class="card-body">

        <!-- Cards de estadísticas -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-group-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Total de Entrenadores</h6>
                            <h2 class="mb-0">{{ $totalEntrenadores }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-success mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-men-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Total de Hombres</h6>
                            <h2 class="mb-0">{{ $totalHombres }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-info mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-women-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Total de Mujeres</h6>
                            <h2 class="mb-0">{{ $totalMujeres }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de filtros -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.reportes.entrenadores') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="{{ request('nombre') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <select name="especialidad" class="form-control">
                                <option value="">Todas las especialidades</option>
                                <option value="Entrenamiento Personal" {{ request('especialidad') == 'Entrenamiento Personal' ? 'selected' : '' }}>Entrenamiento Personal</option>
                                <option value="Entrenamiento Cardiovascular" {{ request('especialidad') == 'Entrenamiento Cardiovascular' ? 'selected' : '' }}>Entrenamiento Cardiovascular</option>
                                <option value="Boxeo" {{ request('especialidad') == 'Boxeo' ? 'selected' : '' }}>Boxeo</option>
                                <option value="Entrenamiento de Resistencia" {{ request('especialidad') == 'Entrenamiento de Resistencia' ? 'selected' : '' }}>Entrenamiento de Resistencia</option>
                                <option value="Nutrición y Bienestar" {{ request('especialidad') == 'Nutrición y Bienestar' ? 'selected' : '' }}>Nutrición y Bienestar</option>
                                <option value="Otro" {{ request('especialidad') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <select name="genero" class="form-control">
                                <option value="">Todos los géneros</option>
                                <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-lg-4 col-md-6">
                            <input type="date" name="fechaCreacionInicio" class="form-control" placeholder="Fecha de creación desde" value="{{ request('fechaCreacionInicio') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="date" name="fechaCreacionFin" class="form-control" placeholder="Fecha de creación hasta" value="{{ request('fechaCreacionFin') }}">
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.entrenadores') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
            <a href="{{ route('admin.reporte.entrenadores-excel', request()->query()) }}" class="btn btn-success"><i class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
        </div>

        <!-- Tabla de entrenadores -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th><i class="ri-user-line me-1"></i> Nombre</th>
                        <th><i class="ri-user-line me-1"></i> Primer Apellido</th>
                        <th><i class="ri-user-line me-1"></i> Segundo Apellido</th>
                        <th><i class="ri-award-line me-1"></i> Especialidad</th>
                        <th><i class="ri-genderless-line me-1"></i> Género</th>
                        <th><i class="ri-phone-line me-1"></i> Teléfono</th>
                        <th><i class="ri-calendar-line me-1"></i> Fecha de Nacimiento</th>
                        <th><i class="ri-calendar-check-line me-1"></i> Fecha de Contratación</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($entrenadores as $entrenador)
                        <tr>
                            <td>{{ $entrenador->nombre }}</td>
                            <td>{{ $entrenador->primerApellido }}</td>
                            <td>{{ $entrenador->segundoApellido }}</td>
                            <td>{{ $entrenador->especialidad }}</td>
                            <td>{{ $entrenador->genero }}</td>
                            <td>{{ $entrenador->telefono }}</td>
                            <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($entrenador->fechaContratacion)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function abrirVentanaPDF() {
        const url = new URL('{{ route('admin.reportes.entrenadores-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search); // Obtener los filtros actuales de la URL
        url.search = params;

        window.open(url, '_blank', 'width=800,height=600');
    }
</script>
@endpush
