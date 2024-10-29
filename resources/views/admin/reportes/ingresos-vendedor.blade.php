@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-file-list-line me-2"></i> Reporte de Ingresos por Vendedor</h1>
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
                <form action="{{ route('admin.reportes.ingresos-vendedor') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                            <input type="date" name="fechaInicio" class="form-control" value="{{ request('fechaInicio') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaFin" class="form-label">Fecha Fin</label>
                            <input type="date" name="fechaFin" class="form-control" value="{{ request('fechaFin') }}">
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.ingresos-vendedor') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
        </div>

        <!-- Tabla de ingresos por vendedor -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead >
                    <tr>
                        <th><i class="ri-user-line me-1"></i> Vendedor</th>
                        <th><i class="ri-bar-chart-line me-1"></i> Total de Inscripciones</th>
                        <th><i class="ri-money-dollar-circle-line me-1"></i> Total Ganado (BOB)</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($ingresosPorVendedor as $ingreso)
                        <tr>
                            <td>{{ $ingreso->vendedor }}</td>
                            <td>{{ $ingreso->totalInscripciones }}</td>
                            <td>{{ number_format($ingreso->totalGanado, 2) }} BOB</td>
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
        const url = new URL('{{ route('admin.reportes.ingresos-vendedor-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search);
        url.search = params;
        window.open(url, '_blank', 'width=800,height=600');
    }
</script>

@endpush
