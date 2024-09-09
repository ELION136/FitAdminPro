@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h5>Resumen de Asistencias</h5>

    <!-- Filtros de Fecha -->
    <form method="GET" action="{{ route('cliente.asistencias.asistencia') }}">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="form-control" value="{{ request('fechaInicio') }}">
            </div>
            <div class="col-md-4">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin" name="fechaFin" class="form-control" value="{{ request('fechaFin') }}">
            </div>
            <div class="col-md-4 mt-4">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de Asistencias -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asistencias as $asistencia)
            <tr>
                <td>{{ $asistencia->fecha }}</td>
                <td>{{ $asistencia->horaEntrada }}</td>
                <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Gráfico de Frecuencia de Asistencias -->
    <h5 class="mt-5">Frecuencia de Asistencias</h5>
    <canvas id="frecuenciaAsistencias"></canvas>

    <!-- Notificación por Ausencia Prolongada -->
    @if ($diasSinAsistir > 7)
    <div class="alert alert-warning mt-4">
        <strong>¡Atención!</strong> No has asistido en los últimos {{ $diasSinAsistir }} días. ¡No te olvides de retomar tus sesiones!
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('frecuenciaAsistencias').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($fechas),
            datasets: [{
                label: 'Asistencias',
                data: @json($frecuenciaAsistencias),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush