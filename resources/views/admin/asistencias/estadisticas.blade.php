@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Estadísticas de Asistencia</h1>

    <!-- Formulario de Filtro de Fechas -->
    <form method="GET" action="{{ route('admin.asistencias.estadisticas') }}" class="mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Filtrar por Fecha</h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-5">
                        <label for="fecha_inicio">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                    </div>
                    <div class="col-md-5">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Total de Asistencias</h5>
                </div>
                <div class="card-body text-center">
                    <h2>{{ $totalAsistencias }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Asistencias por Día (Últimos 7 días)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Día</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistenciasPorDia as $asistencia)
                                    <tr>
                                        <td>{{ $asistencia->dia }}</td>
                                        <td>{{ $asistencia->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Clientes Más Frecuentes</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($clientesMasFrecuentes as $index => $cliente)
                            @php
                                // Determinar el color de fondo según la posición
                                $bgColor = '';
                                $borderColor = '';
    
                                if ($index === 0) {
                                    $bgColor = 'bg-danger border-danger'; // Primer lugar - Rojo
                                } elseif ($index === 1) {
                                    $bgColor = 'bg-warning border-warning'; // Segundo lugar - Amarillo
                                } elseif ($index === 2) {
                                    $bgColor = 'bg-success border-success'; // Tercer lugar - Verde
                                }
                            @endphp
                            <a href="#" class="list-group-item list-group-item-action {{ $bgColor }}">
                                <strong>#{{ $index + 1 }} {{ $cliente->nombre }}</strong> - {{ $cliente->asistencias_count }} asistencias
                                @if($index === 0)
                                    <span class="badge badge-danger">¡Más frecuente!</span>
                                @elseif($index === 1)
                                    <span class="badge badge-warning">¡Segundo lugar!</span>
                                @elseif($index === 2)
                                    <span class="badge badge-success">¡Tercer lugar!</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Asistencias Filtradas entre {{ $fechaInicio }} y {{ $fechaFin }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Hora de Entrada</th>
                                    <th>Hora de Salida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistenciasFiltradas as $asistencia)
                                    <tr>
                                        <td>{{ $asistencia->cliente->nombre }}</td>
                                        <td>{{ $asistencia->fecha }}</td>
                                        <td>{{ $asistencia->hora_entrada }}</td>
                                        <td>{{ $asistencia->hora_salida }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
