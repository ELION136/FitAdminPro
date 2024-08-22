@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Estadísticas de Asistencia</h1>

    <div class="mb-4">
        <h2>Total de Asistencias</h2>
        <div class="alert alert-info">
            <strong>{{ $totalAsistencias }}</strong>
        </div>
    </div>

    <div class="mb-4">
        <h2>Asistencias por Día (Últimos 7 días)</h2>
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

    <div>
        <h2>Clientes Más Frecuentes</h2>
        <div class="list-group">
            @foreach($clientesMasFrecuentes as $cliente)
                <a href="#" class="list-group-item list-group-item-action">
                    {{ $cliente->nombre }} - <strong>{{ $cliente->asistencias_count }}</strong> asistencias
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
