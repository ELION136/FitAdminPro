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
                <div class="card text-white bg-primary mb-2 shadow-sm" style="padding: 8px;">
                    <div class="card-body d-flex align-items-center" style="padding: 8px;">
                        <i class="ri-group-line" style="font-size: 1.6rem; margin-right: 8px;"></i>
                        <div>
                            <h6 class="card-title" style="font-size: 0.8rem; margin-bottom: 3px;">Total de Entrenadores</h6>
                            <h3 class="mb-0" style="font-size: 1.2rem;">{{ $totalEntrenadores }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-success mb-2 shadow-sm" style="padding: 8px;">
                    <div class="card-body d-flex align-items-center" style="padding: 8px;">
                        <i class="ri-men-line" style="font-size: 1.6rem; margin-right: 8px;"></i>
                        <div>
                            <h6 class="card-title" style="font-size: 0.8rem; margin-bottom: 3px;">Total de Hombres</h6>
                            <h3 class="mb-0" style="font-size: 1.2rem;">{{ $totalHombres }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-info mb-2 shadow-sm" style="padding: 8px;">
                    <div class="card-body d-flex align-items-center" style="padding: 8px;">
                        <i class="ri-women-line" style="font-size: 1.6rem; margin-right: 8px;"></i>
                        <div>
                            <h6 class="card-title" style="font-size: 0.8rem; margin-bottom: 3px;">Total de Mujeres</h6>
                            <h3 class="mb-0" style="font-size: 1.2rem;">{{ $totalMujeres }}</h3>
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
                                <option value="">Seleccione una especialidad</option>
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
                                <option value="">Selecciones</option>
                                <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-lg-4 col-md-6">
                            <input type="date" name="fechaCreacionInicio" class="form-control" value="{{ request('fechaCreacionInicio', \Carbon\Carbon::now()->format('Y-m-d')) }}" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="date" name="fechaCreacionFin" class="form-control" value="{{ request('fechaCreacionFin', \Carbon\Carbon::now()->format('Y-m-d')) }}" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
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
                <thead class="table text-center">
                    <tr>
                        <th>#</th>
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
                    @forelse ($entrenadores as $entrenador)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $entrenador->nombre }}</td>
                            <td>{{ $entrenador->primerApellido }}</td>
                            <td>{{ $entrenador->segundoApellido }}</td>
                            <td>{{ $entrenador->especialidad }}</td>
                            <td>{{ $entrenador->genero }}</td>
                            <td>{{ $entrenador->telefono }}</td>
                            <td>{{ \Carbon\Carbon::parse($entrenador->fechaNacimiento)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($entrenador->fechaContratacion)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron entrenadores.</td>
                        </tr>
                    @endforelse
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
        const params = new URLSearchParams(window.location.search); 
        url.search = params;
        window.open(url, '_blank', 'width=800,height=600');
    }
</script>
@endpush
