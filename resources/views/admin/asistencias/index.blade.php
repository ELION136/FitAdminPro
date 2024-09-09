@extends('layouts.admin')
@section('content')
    <div class="container mt-4">
        <!-- Card Contenedor -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Reportes de Asistencias</h2>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <form method="GET" action="{{ route('admin.asistencias.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select name="cliente_id" class="form-select">
                                <option value="">Todos los clientes</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->idCliente }}" {{ request('cliente_id') == $cliente->idCliente ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                            <input type="date" name="fechaInicio" class="form-control" value="{{ request('fechaInicio', $fechaInicio) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="fechaFin" class="form-label">Fecha Fin</label>
                            <input type="date" name="fechaFin" class="form-control" value="{{ request('fechaFin', $fechaFin) }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                </form>

                <!-- Cards de asistencias -->
                <div class="row">
                    @foreach($asistencias as $asistencia)
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">{{ $asistencia->cliente->nombre }}</h5>
                                    <p class="card-text"><strong>Fecha:</strong> {{ $asistencia->fecha }}</p>
                                    <p class="card-text"><strong>Hora Entrada:</strong> {{ $asistencia->horaEntrada }}</p>
                                    <p class="card-text"><strong>Hora Salida:</strong> {{ $asistencia->horaSalida ?? 'No registrada' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Gráfico de asistencias -->
                <div class="mt-4">
                    <h5 class="mb-3">Frecuencia de Asistencias</h5>
                    <canvas id="asistenciaChart"></canvas>
                </div>

                <!-- Botones de exportación -->
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('admin.asistencias.asistencias-pdf', request()->query()) }}" class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </a>
                    <a href="" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Asegúrate de tener FontAwesome para los iconos en los botones -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('asistenciaChart').getContext('2d');
        var asistenciaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($fechas) !!},
                datasets: [{
                    label: 'Frecuencia de Asistencias',
                    data: {!! json_encode($frecuenciaAsistencias) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endpush
