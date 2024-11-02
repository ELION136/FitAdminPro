@extends('layouts.app')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h1 class="card-title mb-0"><i class="ri-calendar-check-line me-2"></i> Reporte de Asistencias</h1>
        </div>
        <div class="card-body">

            <!-- Cards de estadísticas ajustados para ser más pequeños -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-primary mb-2 shadow-sm">
                        <div class="card-body d-flex align-items-center p-2">
                            <i class="ri-user-follow-line" style="font-size: 1.2rem; margin-right: 6px;"></i>
                            <div>
                                <h6 class="card-title mb-0" style="font-size: 0.75rem;">Total de Asistencias</h6>
                                <h5 class="mb-0" style="font-size: 1rem;">{{ $totalAsistencias }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-success mb-2 shadow-sm">
                        <div class="card-body d-flex align-items-center p-2">
                            <i class="ri-qr-code-line" style="font-size: 1.2rem; margin-right: 6px;"></i>
                            <div>
                                <h6 class="card-title mb-0" style="font-size: 0.75rem;">Registro QR</h6>
                                <h5 class="mb-0" style="font-size: 1rem;">{{ $totalQR }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-info mb-2 shadow-sm">
                        <div class="card-body d-flex align-items-center p-2">
                            <i class="ri-hand-coin-line" style="font-size: 1.2rem; margin-right: 6px;"></i>
                            <div>
                                <h6 class="card-title mb-0" style="font-size: 0.75rem;">Registro Manual</h6>
                                <h5 class="mb-0" style="font-size: 1rem;">{{ $totalManual }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card text-white bg-warning mb-2 shadow-sm">
                        <div class="card-body d-flex align-items-center p-2">
                            <i class="ri-calendar-check-line" style="font-size: 1.2rem; margin-right: 6px;"></i>
                            <div>
                                <h6 class="card-title mb-0" style="font-size: 0.75rem;">Asistencias de Hoy</h6>
                                <h5 class="mb-0" style="font-size: 1rem;">{{ $asistenciasHoy }}</h5>
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
                                <input type="date" name="fechaInicio" class="form-control" value="{{ $fechaInicio }}"
                                    max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label for="fechaFin" class="form-label">Fecha de Fin</label>
                                <input type="date" name="fechaFin" class="form-control" value="{{ $fechaFin }}"
                                    max="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-lg-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2"><i class="ri-filter-3-line me-1"></i>
                                    Filtrar</button>
                                <a href="{{ route('admin.reportes.asistencias') }}" class="btn btn-secondary"><i
                                        class="ri-refresh-line me-1"></i> Limpiar Filtros</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Botones de exportación -->
            <div class="d-flex justify-content-end mb-3">
                <a href="javascript:void(0);" onclick="abrirVentanaPDF()" class="btn btn-danger me-2"><i
                        class="ri-file-pdf-line me-1"></i> Exportar a PDF</a>
                <a href="{{ route('admin.reportes.asistencias-excel', request()->query()) }}" class="btn btn-success"><i
                        class="ri-file-excel-line me-1"></i> Exportar a Excel</a>
            </div>

            <!-- Tabla de asistencias -->
            <div class="table-responsive">
                @if ($asistencias->isEmpty())
                    <div class="alert alert-warning text-center">
                        <i class="ri-alert-line me-2"></i> No se encontraron registros de asistencias para los filtros
                        aplicados.
                    </div>
                @else
                    <table class="table table-hover table-striped table-bordered align-middle">
                        <thead class="table text-center">
                            <tr>
                                <th>#</th>
                                <th><i class="ri-calendar-line me-1"></i> Fecha de Asistencia</th>
                                <th><i class="ri-user-line me-1"></i> Cliente</th>
                                <th><i class="ri-barcode-box-line me-1"></i> Método de Registro</th>
                                <th><i class="ri-checkbox-line me-1"></i> Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($asistencias as $asistencia)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
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
