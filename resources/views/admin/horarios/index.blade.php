@extends('layouts.admin')

@section('content')
    <!-- Page Header -->

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Horarios</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Horarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Errores en el formulario',
                html: `
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
                icon: 'error'
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Éxito',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 2000
            });
        </script>
    @endif



    <!-- Page Header Close -->

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="horariosList">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Horarios</h5>
                    <div class="d-flex gap-1 flex-wrap">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createHorarioModal">
                            <i class="ri-add-line align-bottom me-1"></i> Añadir Nuevo Horario
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="HorariosTable" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Servicio</th>
                                    <th>Entrenador</th>
                                    <th>Día de la Semana</th>
                                    <th>Hora de Inicio</th>
                                    <th>Hora de Fin</th>
                                    <th>Capacidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $numero = 1; @endphp
                                @foreach ($horarios as $horario)
                                    <tr>
                                        <td>{{ $numero++ }}</td>
                                        <td>{{ $horario->servicio->nombre }}</td>
                                        <td>{{ $horario->entrenador->nombre }}</td>
                                        <td>{{ $horario->diaSemana }}</td>
                                        <td>{{ $horario->horaInicio }}</td>
                                        <td>{{ $horario->horaFin }}</td>
                                        <td>{{ $horario->capacidad }}</td>
                                        <td>
                                            @if ($horario->eliminado == 1)
                                                <span class="text-success">Activo</span>
                                            @else
                                                <span class="text-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Botón para abrir el modal de edición -->
                                            <button type="button"
                                                class="btn btn-outline-warning btn-icon waves-effect waves-light material-shadow-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editHorarioModal{{ $horario->idHorario }}" title="Editar">
                                                <i class="las la-pen"></i>
                                            </button>
                                            <!-- Botón para eliminar con confirmación SweetAlert -->
                                            <!-- Botón para eliminar con confirmación SweetAlert2 -->
                                            <form action="{{ route('admin.horarios.destroy', $horario->idHorario) }}"
                                                method="POST" class="delete-form" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" title="Eliminar"
                                                    class="btn btn-outline-danger btn-icon waves-effect waves-light material-shadow-none show_confirm"
                                                    data-id="{{ $horario->idHorario }}">
                                                    <i class="las la-trash-alt"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>


                                    <!-- Modal para Editar Horario -->
                                    <div class="modal fade" id="editHorarioModal{{ $horario->idHorario }}" tabindex="-1"
                                        aria-labelledby="editHorarioModalLabel{{ $horario->idHorario }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg"> <!-- Cambiado a modal-lg para mayor anchura -->
                                            <div class="modal-content">
                                                <form action="{{ route('admin.horarios.update', $horario->idHorario) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editHorarioModalLabel{{ $horario->idHorario }}">Editar
                                                            Horario</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <!-- Distribuir contenido en dos columnas -->
                                                        <div class="row">
                                                            <!-- Servicio -->
                                                            <div class="col-md-6 mb-3">
                                                                <label for="idServicio" class="form-label">Servicio</label>
                                                                <select class="form-control" name="idServicio"
                                                                    id="idServicioEdit{{ $horario->idHorario }}" required>
                                                                    @foreach ($servicios as $servicio)
                                                                        <option value="{{ $servicio->idServicio }}"
                                                                            data-duracion="{{ $servicio->duracion }}"
                                                                            {{ $horario->idServicio == $servicio->idServicio ? 'selected' : '' }}>
                                                                            {{ $servicio->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Entrenador -->
                                                            <div class="col-md-6 mb-3">
                                                                <label for="idEntrenador"
                                                                    class="form-label">Entrenador</label>
                                                                <select class="form-control" name="idEntrenador" required>
                                                                    @foreach ($entrenadores as $entrenador)
                                                                        <option value="{{ $entrenador->idEntrenador }}"
                                                                            {{ $horario->idEntrenador == $entrenador->idEntrenador ? 'selected' : '' }}>
                                                                            {{ $entrenador->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- Días de la Semana -->
                                                            <div class="col-md-6 mb-3">
                                                                <label for="diaSemana" class="form-label">Días de la
                                                                    Semana</label>
                                                                <select class="form-control" name="diaSemana[]"
                                                                    id="diaSemanaEdit{{ $horario->idHorario }}" multiple
                                                                    required>
                                                                    @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                                                        <option value="{{ $dia }}"
                                                                            {{ in_array($dia, explode(',', $horario->diaSemana)) ? 'selected' : '' }}>
                                                                            {{ $dia }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Duración del Servicio -->
                                                            <div class="col-md-6 mb-3">
                                                                <label for="duracion" class="form-label">Duración del
                                                                    Servicio (en minutos)</label>
                                                                <input type="text" class="form-control"
                                                                    id="duracionEdit{{ $horario->idHorario }}" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- Hora de Inicio -->
                                                            <div class="col-md-6 mb-3">
                                                                <label for="horaInicio" class="form-label">Hora de
                                                                    Inicio</label>
                                                                <input type="time" class="form-control" name="horaInicio"
                                                                    id="horaInicioEdit{{ $horario->idHorario }}"
                                                                    value="{{ $horario->horaInicio }}" required>
                                                            </div>

                                                            <!-- Hora de Fin (Calculada automáticamente) -->
                                                            <div class="col-md-6 mb-3">
                                                                <label for="horaFin" class="form-label">Hora de
                                                                    Fin</label>
                                                                <input type="time" class="form-control" name="horaFin"
                                                                    id="horaFinEdit{{ $horario->idHorario }}"
                                                                    value="{{ $horario->horaFin }}" readonly required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <!-- Capacidad -->
                                                            <div class="col-md-12 mb-3">
                                                                <label for="capacidad"
                                                                    class="form-label">Capacidad</label>
                                                                <input type="number" class="form-control"
                                                                    name="capacidad" value="{{ $horario->capacidad }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar
                                                            Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End::row-1 -->

    <!-- Modal para Crear Horario -->
    <div class="modal fade" id="createHorarioModal" tabindex="-1" aria-labelledby="createHorarioModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Cambiar a modal-lg para hacer el modal más ancho -->
            <div class="modal-content">
                <form action="{{ route('admin.horarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Servicio -->
                            <div class="col-md-6 mb-3">
                                <label for="idServicio" class="form-label">Servicio</label>
                                <select class="form-control" name="idServicio" id="idServicio" required>
                                    <option value="" disabled selected>Selecciona un servicio</option>
                                    @foreach ($servicios as $servicio)
                                        <option value="{{ $servicio->idServicio }}"
                                            data-duracion="{{ $servicio->duracion }}">
                                            {{ $servicio->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Entrenador -->
                            <div class="col-md-6 mb-3">
                                <label for="idEntrenador" class="form-label">Entrenador</label>
                                <select class="form-control" name="idEntrenador" required>
                                    @foreach ($entrenadores as $entrenador)
                                        <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Días de la Semana -->
                            <div class="col-md-6 mb-3">
                                <label for="diaSemana" class="form-label">Días de la Semana</label>
                                <div>
                                    @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="diaSemana[]"
                                                value="{{ $dia }}" id="dia_{{ $dia }}"
                                                @if (in_array($dia, old('diaSemana', []))) checked @endif>
                                            <label class="form-check-label" for="dia_{{ $dia }}">
                                                {{ $dia }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Duración del Servicio -->
                            <div class="col-md-6 mb-3">
                                <label for="duracion" class="form-label">Duración del Servicio (en minutos)</label>
                                <input type="text" class="form-control" id="duracion" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Hora de Inicio -->
                            <div class="col-md-6 mb-3">
                                <label for="horaInicio" class="form-label">Hora de Inicio</label>
                                <input type="time" class="form-control @error('horaInicio') is-invalid @enderror"
                                    name="horaInicio" id="horaInicio" value="{{ old('horaInicio') }}" required>
                            </div>

                            <!-- Hora de Fin (Calculada automáticamente) -->
                            <div class="col-md-6 mb-3">
                                <label for="horaFin" class="form-label">Hora de Fin</label>
                                <input type="time" class="form-control" name="horaFin" id="horaFin" readonly
                                    required>
                            </div>
                        </div>

                        <!-- Capacidad -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="capacidad" class="form-label">Capacidad</label>
                                <input type="number" class="form-control" name="capacidad" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear Horario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const servicioSelect = document.querySelector('#idServicio');
            const horaInicioInput = document.querySelector('#horaInicio');
            const horaFinInput = document.querySelector('#horaFin');
            const duracionInput = document.querySelector('#duracion');

            // Cuando se selecciona un servicio, se muestra la duración
            servicioSelect.addEventListener('change', function() {
                const duracion = this.options[this.selectedIndex].getAttribute('data-duracion');
                duracionInput.value = duracion + ' minutos'; // Mostrar la duración en minutos

                // Si ya se seleccionó la hora de inicio, calcular la hora de fin
                calcularHoraFin();
            });

            // Cuando se cambia la hora de inicio, se recalcula la hora de fin
            horaInicioInput.addEventListener('input', function() {
                calcularHoraFin();
            });

            // Función para calcular la hora de fin
            function calcularHoraFin() {
                const duracion = parseInt(servicioSelect.options[servicioSelect.selectedIndex].getAttribute(
                    'data-duracion'));
                const horaInicio = horaInicioInput.value;

                if (duracion && horaInicio) {
                    let [horas, minutos] = horaInicio.split(':').map(Number);
                    minutos += duracion;

                    // Ajustar las horas y minutos si los minutos son mayores de 60
                    while (minutos >= 60) {
                        horas += 1;
                        minutos -= 60;
                    }

                    // Formatear horas y minutos para mostrar
                    const horasFin = horas.toString().padStart(2, '0');
                    const minutosFin = minutos.toString().padStart(2, '0');

                    // Establecer la hora de fin en el input
                    horaFinInput.value = `${horasFin}:${minutosFin}`;
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Iterar sobre todos los horarios que se estén editando
            const horarios = @json($horarios);

            horarios.forEach(horario => {
                const servicioSelect = document.querySelector(`#idServicioEdit${horario.idHorario}`);
                const horaInicioInput = document.querySelector(`#horaInicioEdit${horario.idHorario}`);
                const horaFinInput = document.querySelector(`#horaFinEdit${horario.idHorario}`);
                const duracionInput = document.querySelector(`#duracionEdit${horario.idHorario}`);

                // Mostrar la duración al cargar la página para el horario actual
                const duracionInicial = servicioSelect.options[servicioSelect.selectedIndex].getAttribute(
                    'data-duracion');
                duracionInput.value = duracionInicial + ' minutos';

                // Cuando se selecciona un servicio, se muestra la duración
                servicioSelect.addEventListener('change', function() {
                    const duracion = this.options[this.selectedIndex].getAttribute('data-duracion');
                    duracionInput.value = duracion + ' minutos'; // Mostrar la duración en minutos

                    // Si ya se seleccionó la hora de inicio, calcular la hora de fin
                    calcularHoraFin(horaInicioInput, horaFinInput, servicioSelect);
                });

                // Cuando se cambia la hora de inicio, se recalcula la hora de fin
                horaInicioInput.addEventListener('input', function() {
                    calcularHoraFin(horaInicioInput, horaFinInput, servicioSelect);
                });
            });

            // Función para calcular la hora de fin
            function calcularHoraFin(horaInicioInput, horaFinInput, servicioSelect) {
                const duracion = parseInt(servicioSelect.options[servicioSelect.selectedIndex].getAttribute(
                    'data-duracion'));
                const horaInicio = horaInicioInput.value;

                if (duracion && horaInicio) {
                    let [horas, minutos] = horaInicio.split(':').map(Number);
                    minutos += duracion;

                    // Ajustar las horas y minutos si los minutos son mayores de 60
                    while (minutos >= 60) {
                        horas += 1;
                        minutos -= 60;
                    }

                    // Formatear horas y minutos para mostrar
                    const horasFin = horas.toString().padStart(2, '0');
                    const minutosFin = minutos.toString().padStart(2, '0');

                    // Establecer la hora de fin en el input
                    horaFinInput.value = `${horasFin}:${minutosFin}`;
                }
            }
        });

        $(document).ready(function() {
            $('#HorariosTable').DataTable({
                responsive: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    decimal: "",
                    emptyTable: "No hay datos disponibles en la tabla",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                    infoFiltered: "(filtrado de _MAX_ entradas totales)",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscar:",
                    zeroRecords: "No se encontraron registros coincidentes",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    aria: {
                        sortAscending: ": activar para ordenar la columna de manera ascendente",
                        sortDescending: ": activar para ordenar la columna de manera descendente"
                    }
                },
            });
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            const horaInicio = document.querySelector('#horaInicio').value;
            if (!horaInicio) {
                alert('El campo de Hora de Inicio es requerido.');
                event.preventDefault();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar todos los botones con la clase .show_confirm
            const deleteButtons = document.querySelectorAll('.show_confirm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Alerta de confirmación
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Enviar el formulario si se confirma la acción
                            this.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
