@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-calendar-line me-2"></i> Reporte de Inscripciones por Mes y Año</h1>
    </div>
    <div class="card-body">

        <!-- Cards de estadísticas -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-user-add-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Total de Inscripciones</h6>
                            <h2 class="mb-0">{{ $totalInscripciones }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-success mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-money-dollar-circle-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Total Ganado</h6>
                            <h2 class="mb-0">{{ number_format($totalGanado, 2) }} BOB</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de filtros -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.reportes.inscripciones-mes-anio') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="anio" class="form-label">Año</label>
                            <select name="anio" class="form-control">
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="mes" class="form-label">Mes</label>
                            <select name="mes" class="form-control">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ request('mes') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->locale('es')->isoFormat('MMMM') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.inscripciones-mes-anio') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
            <a href="{{ route('admin.reporte.inscripciones-excel', request()->query()) }}" class="btn btn-success"><i class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
        </div>

        <!-- Tabla de inscripciones por mes y año -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th><i class="ri-calendar-line me-1"></i> Año</th>
                        <th><i class="ri-calendar-event-line me-1"></i> Mes</th>
                        <th><i class="ri-bar-chart-line me-1"></i> Total de Inscripciones</th>
                        <th><i class="ri-money-dollar-circle-line me-1"></i> Total Pagado (BOB)</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($inscripciones as $inscripcion)
                        <tr>
                            <td>{{ $inscripcion->anio }}</td>
                            <td>{{ \Carbon\Carbon::create()->month($inscripcion->mes)->locale('es')->isoFormat('MMMM') }}</td>
                            <td>{{ $inscripcion->totalInscripciones }}</td>
                            <td>{{ number_format($inscripcion->totalGanado, 2) }} BOB</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Nueva tabla para el reporte anual -->
        <h2 class="mt-5"><i class="ri-calendar-check-line me-2"></i>Reporte Total Anual</h2>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th><i class="ri-calendar-line me-1"></i> Año</th>
                        <th><i class="ri-bar-chart-line me-1"></i> Cant. Inscripciones</th>
                        <th><i class="ri-money-dollar-circle-line me-1"></i> Total Ganado (BOB)</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($reporteAnual as $anio)
                        <tr>
                            <td>{{ $anio->anio }}</td>
                            <td>{{ $anio->totalInscripciones }}</td>
                            <td>{{ number_format($anio->totalGanado, 2) }} BOB</td>
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
        const url = new URL('{{ route('admin.reportes.inscripciones-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search); // Obtener los filtros actuales de la URL
        url.search = params; // Añadir los filtros a la URL

        window.open(url, '_blank', 'width=800,height=600');
    }
</script>

@endpush
