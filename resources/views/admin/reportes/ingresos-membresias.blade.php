@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-money-dollar-box-line me-2"></i> Reporte de Ingresos por Membresías</h1>
    </div>
    <div class="card-body">

        <!-- Cards de estadísticas -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-primary mb-2 shadow-sm" style="font-size: 0.9rem;">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-group-line me-2" style="font-size: 2.5rem;"></i>
                        <div>
                            <h6 class="card-title mb-1">Total de Inscripciones</h6>
                            <h4 class="mb-0">{{ $totalInscripciones }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-success mb-2 shadow-sm" style="font-size: 0.9rem;">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-money-dollar-circle-line me-2" style="font-size: 2.5rem;"></i>
                        <div>
                            <h6 class="card-title mb-1">Total Ganado</h6>
                            <h4 class="mb-0">{{ number_format($totalGanado, 2) }} BOB</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de filtros -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.reportes.ingresos-membresias') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                            <input type="date" name="fechaInicio" class="form-control" value="{{ request('fechaInicio', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaFin" class="form-label">Fecha Fin</label>
                            <input type="date" name="fechaFin" class="form-control" value="{{ request('fechaFin', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.ingresos-membresias') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
            <a href="{{ route('admin.reporte.ingresos-membresias-excel', request()->query()) }}" class="btn btn-success"><i class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
        </div>

        <!-- Tabla de ingresos por membresías -->
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered align-middle">
                <thead class="table text-center">
                    <tr>
                        <th>#</th>
                        <th><i class="ri-user-line me-1"></i> Cliente</th>
                        <th><i class="ri-ticket-line me-1"></i> Membresía</th>
                        <th><i class="ri-user-3-line me-1"></i> Vendedor</th>
                        <th><i class="ri-money-dollar-circle-line me-1"></i> Total Pagado (BOB)</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @forelse ($ingresosPorMembresias as $index => $ingreso)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ingreso->clienteNombre }} {{ $ingreso->clienteApellido }}</td>
                            <td>{{ $ingreso->membresiaNombre }}</td>
                            <td>{{ $ingreso->vendedor }}</td>
                            <td>{{ number_format($ingreso->totalPagado, 2) }} BOB</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Sin elementos</td>
                        </tr>
                    @endforelse
                    <!-- Fila de totales -->
                    @if($ingresosPorMembresias->isNotEmpty())
                        <tr>
                            <td colspan="4" style="text-align: right; font-weight: bold;">Total General:</td>
                            <td style="font-weight: bold;">{{ number_format($totalGanado, 2) }} BOB</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection

@push('scripts')

<script>
    function abrirVentanaPDF() {
        const url = new URL('{{ route('admin.reportes.ingresos-membresias-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search);
        url.search = params;

        window.open(url, '_blank', 'width=800,height=600');
    }
</script>

@endpush
