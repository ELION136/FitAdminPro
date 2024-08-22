@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Gestión de Asistencias</h1>

    <!-- Formulario de filtros -->
    <form action="{{ route('admin.asistencias.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}" placeholder="Filtrar por fecha">
            </div>
            <div class="col-md-4">
                <input type="text" name="cliente" class="form-control" placeholder="Buscar por cliente" value="{{ request('cliente') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Formulario para registro manual -->
    <form action="{{ route('admin.asistencias.registrar-manual') }}" method="POST" class="mb-5">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="idCliente" class="form-control" placeholder="ID del Cliente" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="time" name="horaEntrada" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="time" name="horaSalida" class="form-control" placeholder="Hora de Salida (Opcional)">
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-success">Registrar Asistencia</button>
        </div>
    </form>

    <!-- Tabla de asistencias -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asistencias as $asistencia)
                    <tr>
                        <td>{{ $asistencia->cliente->nombre }}</td>
                        <td>{{ $asistencia->fecha }}</td>
                        <td>{{ $asistencia->horaEntrada }}</td>
                        <td>{{ $asistencia->horaSalida ?? 'No registrada' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $asistencias->links() }}
    </div>
</div>
@endsection
