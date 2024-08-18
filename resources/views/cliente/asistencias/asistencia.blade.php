@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Registro de Asistencia</h1>

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Registrar Asistencia</h5>
            <form action="{{ route('cliente.asistencias.registrar-asistencia') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Registrar Entrada/Salida</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Resumen del Mes</h5>
            <p>Total de asistencias este mes: {{ $asistenciasMes }}</p>
            <p>Tiempo total este mes: {{ $tiempoTotalMes }} horas</p>
            <p class="text-muted">Miembro desde: {{ Auth::user()->fechaCreacion}}</p>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Últimas Asistencias</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasAsistencias as $asistencia)
                        <tr>
                            <td>{{ $asistencia->fecha }}</td>
                            <td>{{ $asistencia->horaEntrada }}</td>
                            <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection