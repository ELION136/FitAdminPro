@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-user-line me-2"></i> Reporte de Clientes</h1>
    </div>
    <div class="card-body">

        <!-- Cards de estadísticas -->
        <div class="row mb-3">
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-primary mb-2 shadow-sm" style="padding: 8px;">
                    <div class="card-body d-flex align-items-center" style="padding: 8px;">
                        <i class="ri-group-line" style="font-size: 1.6rem; margin-right: 8px;"></i>
                        <div>
                            <h6 class="card-title" style="font-size: 0.8rem; margin-bottom: 3px;">Total de Clientes</h6>
                            <h3 class="mb-0" style="font-size: 1.2rem;">{{ $totalClientes }}</h3>
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
                <form action="{{ route('admin.reportes.cliente') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="{{ request('nombre') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="text" name="primerApellido" class="form-control" placeholder="Primer Apellido" value="{{ request('primerApellido') }}">
                        </div>
                        <div class="col-lg-4">
                            <select name="genero" class="form-control">
                                <option value="">seleccione</option>
                                <option value="Masculino" {{ request('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ request('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ request('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-lg-4 col-md-6">
                            <input type="date" name="fechaCreacionInicio" class="form-control" placeholder="Fecha de creación desde" value="{{ request('fechaCreacionInicio', Carbon\Carbon::now()->format('Y-m-d')) }}" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <input type="date" name="fechaCreacionFin" class="form-control" placeholder="Fecha de creación hasta" value="{{ request('fechaCreacionFin', Carbon\Carbon::now()->format('Y-m-d')) }}" max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.cliente') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
            <a href="{{ route('admin.reporte.clientes-excel', request()->query()) }}" class="btn btn-success"><i class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
        </div>

        <!-- Tabla de clientes -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table text-center">
                    <tr>
                        <th>#</th>
                        <th><i class="ri-user-line me-1"></i> Nombre</th>
                        <th><i class="ri-user-line me-1"></i> Primer Apellido</th>
                        <th><i class="ri-user-line me-1"></i> Segundo Apellido</th>
                        <th><i class="ri-genderless-line me-1"></i> Género</th>
                        <th><i class="ri-calendar-line me-1"></i> Fecha de Nacimiento</th>
                        <th><i class="ri-calendar-check-line me-1"></i> Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->primerApellido }}</td>
                            <td>{{ $cliente->segundoApellido }}</td>
                            <td>{{ $cliente->genero }}</td>
                            <td>{{ $cliente->fechaNacimiento->format('d/m/Y') }}</td>
                            <td>{{ $cliente->fechaCreacion->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron resultados.</td>
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
        const url = new URL('{{ route('admin.reportes.clientes-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search);
        url.search = params;

        // Añadir fecha de filtro y género al reporte PDF
        window.open(url, '_blank', 'width=800,height=600');
    }
</script>

@endpush


