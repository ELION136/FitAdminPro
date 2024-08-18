@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Reporte de Asistencia</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('cliente.asistencias.reporte-asistencia') }}" method="GET" class="form-inline mb-4">
                <div class="form-group mr-2">
                    <label for="mes" class="mr-2">Mes:</label>
                    <select name="mes" id="mes" class="form-control">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('mes', date('n')) == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="anio" class="mr-2">Año:</label>
                    <select name="anio" id="anio" class="form-control">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ request('anio', date('Y')) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Duración</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $asistencia)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $asistencia->horaEntrada ? \Carbon\Carbon::parse($asistencia->horaEntrada)->format('H:i') : '-' }}</td>
                            <td>{{ $asistencia->horaSalida ? \Carbon\Carbon::parse($asistencia->horaSalida)->format('H:i') : 'En curso' }}</td>
                            <td>
                                @if($asistencia->horaEntrada && $asistencia->horaSalida)
                                    {{ \Carbon\Carbon::parse($asistencia->horaEntrada)->diffInHours(\Carbon\Carbon::parse($asistencia->horaSalida)) }} horas
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $asistencias->links() }}
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h3 class="card-title">Resumen del Mes</h3>
            <p>Total de asistencias: {{ $totalAsistencias }}</p>
            <p>Tiempo total: {{ number_format($tiempoTotal, 2) }} horas</p>
            <p>Promedio diario: {{ number_format($promedioDiario, 2) }} horas</p>
        </div>
    </div>
</div>
@endsection
