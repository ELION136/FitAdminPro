@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Editar Asistencia</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detalles de la Asistencia</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.asistencias.update', $asistencia->idAsistencia) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha', $asistencia->fecha) }}" required>
                    @error('fecha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hora_entrada">Hora de Entrada</label>
                    <input type="time" name="hora_entrada" id="hora_entrada" class="form-control @error('hora_entrada') is-invalid @enderror" value="{{ old('hora_entrada', $asistencia->hora_entrada) }}" required>
                    @error('hora_entrada')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hora_salida">Hora de Salida</label>
                    <input type="time" name="hora_salida" id="hora_salida" class="form-control @error('hora_salida') is-invalid @enderror" value="{{ old('hora_salida', $asistencia->hora_salida) }}">
                    @error('hora_salida')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Agregar más campos según sea necesario -->

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.asistencias.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Asistencia</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
