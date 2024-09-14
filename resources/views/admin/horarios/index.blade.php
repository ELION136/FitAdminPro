@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Gestión de Horarios</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Botón para abrir modal de crear horario -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearHorarioModal">
            Crear Nuevo Horario
        </button>

        <!-- Tabla de horarios -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Entrenador</th>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($horarios as $horario)
                    <tr>
                        <td>{{ $horario->entrenador->nombre }}</td>
                        <td>{{ $horario->dia }}</td>
                        <td>{{ $horario->horaInicio }}</td>
                        <td>{{ $horario->horaFin }}</td>
                        <td>{{ $horario->capacidad }}</td>
                        <td>
                            <!-- Botón para abrir modal de editar horario -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editarHorarioModal{{ $horario->idHorario }}">
                                Editar
                            </button>

                            <!-- Formulario para eliminar horario -->
                            <form action="{{ route('admin.horarios.destroy', $horario->idHorario) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal para editar horario -->
                    <div class="modal fade" id="editarHorarioModal{{ $horario->idHorario }}" tabindex="-1"
                        aria-labelledby="editarHorarioLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Horario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.horarios.update', $horario->idHorario) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Campos del formulario -->
                                        <div class="form-group mb-3">
                                            <label for="idEntrenador">Entrenador</label>
                                            <select name="idEntrenador" class="form-control">
                                                @foreach ($entrenadores as $entrenador)
                                                    <option value="{{ $entrenador->idEntrenador }}"
                                                        {{ $entrenador->idEntrenador == $horario->idEntrenador ? 'selected' : '' }}>
                                                        {{ $entrenador->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="dia">Día</label>
                                            <select name="dia" class="form-control">
                                                <option value="Lunes" {{ $horario->dia == 'Lunes' ? 'selected' : '' }}>
                                                    Lunes</option>
                                                <option value="Martes" {{ $horario->dia == 'Martes' ? 'selected' : '' }}>
                                                    Martes</option>
                                                <option value="Miércoles"
                                                    {{ $horario->dia == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                                <option value="Jueves" {{ $horario->dia == 'Jueves' ? 'selected' : '' }}>
                                                    Jueves</option>
                                                <option value="Viernes" {{ $horario->dia == 'Viernes' ? 'selected' : '' }}>
                                                    Viernes</option>
                                                <option value="Sábado" {{ $horario->dia == 'Sábado' ? 'selected' : '' }}>
                                                    Sábado</option>
                                                <option value="Domingo" {{ $horario->dia == 'Domingo' ? 'selected' : '' }}>
                                                    Domingo</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="horaInicio">Hora Inicio</label>
                                            <input type="time" class="form-control" name="horaInicio"
                                                value="{{ $horario->horaInicio }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="horaFin">Hora Fin</label>
                                            <input type="time" class="form-control" name="horaFin"
                                                value="{{ $horario->horaFin }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="capacidad">Capacidad</label>
                                            <input type="number" class="form-control" name="capacidad"
                                                value="{{ $horario->capacidad }}">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para crear nuevo horario -->
    <div class="modal fade" id="crearHorarioModal" tabindex="-1" aria-labelledby="crearHorarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.horarios.store') }}" method="POST">
                        @csrf
                        <!-- Campos del formulario -->
                        <div class="form-group mb-3">
                            <label for="idEntrenador">Entrenador</label>
                            <select name="idEntrenador" class="form-control">
                                @foreach ($entrenadores as $entrenador)
                                    <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="dia">Día</label>
                            <select name="dia" class="form-control">
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="horaInicio">Hora Inicio</label>
                            <input type="time" class="form-control" name="horaInicio">
                        </div>

                        <div class="form-group mb-3">
                            <label for="horaFin">Hora Fin</label>
                            <input type="time" class="form-control" name="horaFin">
                        </div>

                        <div class="form-group mb-3">
                            <label for="capacidad">Capacidad</label>
                            <input type="number" class="form-control" name="capacidad">
                        </div>

                        <button type="submit" class="btn btn-primary">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
@endsection
