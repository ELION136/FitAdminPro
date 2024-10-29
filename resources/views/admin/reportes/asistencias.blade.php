@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h1 class="card-title mb-0"><i class="ri-calendar-check-line me-2"></i> Reporte de Asistencias</h1>
    </div>
    <div class="card-body">

        <!-- Cards de estadísticas -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-user-follow-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Total de Asistencias</h6>
                            <h2 class="mb-0">{{ $totalAsistencias }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-success mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-qr-code-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Registro QR</h6>
                            <h2 class="mb-0">{{ $totalQR }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card text-white bg-info mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="ri-hand-coin-line display-4 me-3"></i>
                        <div>
                            <h6 class="card-title">Registro Manual</h6>
                            <h2 class="mb-0">{{ $totalManual }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de filtros -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.reportes.asistencias') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" name="fechaInicio" class="form-control" value="{{ request('fechaInicio') }}">
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="fechaFin" class="form-label">Fecha de Fin</label>
                            <input type="date" name="fechaFin" class="form-control" value="{{ request('fechaFin') }}">
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i> Filtrar</button>
                            <a href="{{ route('admin.reportes.asistencias') }}" class="btn btn-secondary"><i class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botones de exportación -->
        <div class="d-flex justify-content-end mb-3">
            <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
            <a href="{{ route('admin.reportes.asistencias-excel', request()->query()) }}" class="btn btn-success"><i class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
        </div>

        <!-- Tabla de asistencias -->
        <div class="table-responsive">
            @if ($asistencias->isEmpty())
                <div class="alert alert-warning text-center">
                    <i class="ri-alert-line me-2"></i> No se encontraron registros de asistencias para los filtros aplicados.
                </div>
            @else
                <table class="table table-hover table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th><i class="ri-calendar-line me-1"></i> Fecha de Asistencia</th>
                            <th><i class="ri-user-line me-1"></i> Cliente</th>
                            <th><i class="ri-barcode-box-line me-1"></i> Método de Registro</th>
                            <th><i class="ri-checkbox-line me-1"></i> Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($asistencias as $asistencia)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($asistencia->fechaAsistencia)->format('d/m/Y H:i') }}</td>
                                <td>{{ $asistencia->clienteNombre }}</td>
                                <td>{{ ucfirst($asistencia->metodoRegistro) }}</td>
                                <td>{{ ucfirst($asistencia->estado) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function abrirVentanaPDF() {
        const url = new URL('{{ route('admin.reportes.asistencias-pdf') }}', window.location.origin);
        const params = new URLSearchParams(window.location.search);
        url.search = params;

        window.open(url, '_blank', 'width=800,height=600');
    }
</script>
@endpush
