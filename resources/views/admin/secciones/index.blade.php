@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Gestión de Secciones</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Secciones</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <div class="row">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">Lista de Secciones</h5>
                        </div>
                        <div class="col-sm-auto">
                            <button class="btn btn-success" onclick="createSeccion()">
                                <i class="ri-add-line align-bottom me-1"></i> Añadir Sección
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservaTable" class="table table-bordered text-nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Servicio</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>

                                    <th>Capacidad</th>
                                    <th>Entrenador</th>
                                    <th>Días</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $contador = 1; @endphp <!-- Inicializamos el contador -->
                                @foreach ($secciones as $seccion)
                                    <tr>
                                        <td>{{ $contador++ }}</td>
                                        <td>{{ $seccion->servicio->nombre }}</td>
                                        <td>{{ $seccion->fechaInicio }}</td>
                                        <td>{{ $seccion->fechaFin }}</td>

                                        <td>{{ $seccion->capacidad }}</td>
                                        <td>{{ $seccion->entrenador->nombre ?? 'N/A' }}</td>
                                        <td>
                                            @foreach ($seccion->dias as $dia)
                                                {{ ucfirst($dia->nombreDia) }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item">
                                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        onclick="editSeccion({{ $seccion }})">
                                                        <i class="ri-pencil-fill fs-16"></i>
                                                    </button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="deleteSeccion({{ $seccion->idSeccion }})">
                                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Crear/Editar Sección -->
    <div class="modal fade" id="modalSeccion" tabindex="-1" aria-labelledby="modalSeccionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formSeccion" method="POST">
                    @csrf
                    <input type="hidden" id="seccionId" name="seccionId">
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="modalSeccionLabel">Añadir/Editar Sección</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idServicio" class="form-label">Servicio</label>
                                    <select class="form-select" id="idServicio" name="idServicio" required>
                                        <option value="" selected disabled>Seleccione un servicio</option>
                                        @foreach ($servicios as $servicio)
                                            <option value="{{ $servicio->idServicio }}"
                                                data-capacidad="{{ $servicio->capacidadMaxima }}"
                                                data-duracion="{{ $servicio->duracion }}">
                                                {{ $servicio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fechaInicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                                </div>
                                <div class="mb-3">
                                    <label for="horaInicio" class="form-label">Hora Inicio</label>
                                    <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fechaFin" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin" readonly
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="horaFin" class="form-label">Hora Fin</label>
                                    <input type="time" class="form-control" id="horaFin" name="horaFin" readonly
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="capacidad" class="form-label">Capacidad</label>
                                    <input type="number" class="form-control" id="capacidad" name="capacidad" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="idEntrenador" class="form-label">Entrenador</label>
                                    <select class="form-select" id="idEntrenador" name="idEntrenador">
                                        <option value="" selected>Ninguno</option>
                                        @foreach ($entrenadores as $entrenador)
                                            <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="dias" class="form-label">Días de la semana</label>
                            <div id="dias" class="d-flex flex-wrap gap-3">
                                @foreach ($dias as $dia)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]"
                                            value="{{ $dia->idDia }}" id="dia-{{ $dia->idDia }}">
                                        <label class="form-check-label"
                                            for="dia-{{ $dia->idDia }}">{{ ucfirst($dia->nombreDia) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#reservaTable').DataTable({
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formSeccion');
            const capacidadInput = document.getElementById('capacidad');
            const servicioSelect = document.getElementById('idServicio');
            const fechaInicioInput = document.getElementById('fechaInicio');
            const fechaFinInput = document.getElementById('fechaFin');
            const horaInicioInput = document.getElementById('horaInicio');
            const horaFinInput = document.getElementById('horaFin');

            // Configurar la capacidad máxima y calcular la hora fin cuando se selecciona un servicio
            servicioSelect.addEventListener('change', function() {
                let capacidadMaxima = this.options[this.selectedIndex].getAttribute('data-capacidad');
                let duracion = parseInt(this.options[this.selectedIndex].getAttribute('data-duracion'));

                capacidadInput.value = capacidadMaxima;
                capacidadInput.setAttribute('readonly', 'true');

                // Remover cualquier evento previo para evitar duplicaciones
                horaInicioInput.removeEventListener('change', calcularHoraFinEvent);

                // Registrar el nuevo evento
                horaInicioInput.addEventListener('change', calcularHoraFinEvent);

                // Definir la función del evento para calcular la hora de fin
                function calcularHoraFinEvent() {
                    calcularHoraFin(duracion);
                }
            });

            // Validar la fecha de inicio para que no sea menor a la fecha actual
            fechaInicioInput.addEventListener('change', function() {
                let fechaActual = new Date();
                let fechaMinimaFin = new Date(this.value);
                fechaMinimaFin.setDate(fechaMinimaFin.getDate() + 7);

                // Establecer restricciones para la fecha de fin
                fechaFinInput.removeAttribute('readonly');
                fechaFinInput.setAttribute('min', fechaMinimaFin.toISOString().split('T')[0]);
                fechaFinInput.value = fechaMinimaFin.toISOString().split('T')[0];
            });

            function calcularHoraFin(duracion) {
                if (duracion && horaInicioInput.value) {
                    let horaInicio = new Date(`1970-01-01T${horaInicioInput.value}`);
                    let horaFin = new Date(horaInicio.getTime() + duracion * 60 *
                        1000); // Convertir minutos a milisegundos

                    // Establecer la hora de cierre a las 22:00 en el mismo día
                    let horaCierre = new Date(horaInicio);
                    horaCierre.setHours(22, 0, 0, 0);

                    // Si la hora de fin excede las 22:00, ajustarla a las 22:00
                    if (horaFin > horaCierre) {
                        horaFin = horaCierre;
                    }

                    // Obtener horas y minutos en hora local y formatearlos
                    let horas = horaFin.getHours().toString().padStart(2, '0');
                    let minutos = horaFin.getMinutes().toString().padStart(2, '0');
                    horaFinInput.value = `${horas}:${minutos}`;
                    horaFinInput.setAttribute('readonly', 'true');
                }
            }

            // Función para abrir el modal de creación de una nueva sección
            function createSeccion() {
                form.reset();
                form.seccionId.value = '';
                capacidadInput.removeAttribute('readonly');
                servicioSelect.removeAttribute('disabled');
                horaFinInput.setAttribute('readonly', 'true');
                fechaFinInput.setAttribute('readonly', 'true');
                document.getElementById('modalSeccionLabel').textContent = 'Añadir Sección';
                new bootstrap.Modal(document.getElementById('modalSeccion')).show();
            }

            // Función para abrir el modal de edición de una sección existente
            function editSeccion(seccion) {
                form.reset();
                form.seccionId.value = seccion.idSeccion;
                form.idServicio.value = seccion.idServicio;
                form.fechaInicio.value = seccion.fechaInicio;
                form.fechaFin.value = seccion.fechaFin;
                form.horaInicio.value = seccion.horaInicio;
                form.horaFin.value = seccion.horaFin;
                form.capacidad.value = seccion.capacidad;
                form.idEntrenador.value = seccion.idEntrenador || '';
                servicioSelect.setAttribute('disabled', 'true');
                capacidadInput.setAttribute('readonly', 'true');
                horaFinInput.setAttribute('readonly', 'true');
                fechaFinInput.removeAttribute('readonly');

                // Calcular la hora de fin automáticamente al editar
                let duracion = parseInt(servicioSelect.options[servicioSelect.selectedIndex].getAttribute(
                    'data-duracion'));
                calcularHoraFin(duracion);

                // Establecer restricciones para la fecha de fin
                let fechaMinimaFin = new Date(fechaInicioInput.value);
                fechaMinimaFin.setDate(fechaMinimaFin.getDate() + 7);
                fechaFinInput.setAttribute('min', fechaMinimaFin.toISOString().split('T')[0]);

                seccion.dias.forEach(dia => {
                    document.getElementById('dia-' + dia.idDia).checked = true;
                });
                document.getElementById('modalSeccionLabel').textContent = 'Editar Sección';
                new bootstrap.Modal(document.getElementById('modalSeccion')).show();
            }

            // Función para eliminar una sección
            function deleteSeccion(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ route('admin.secciones.destroy', '') }}/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    _method: 'DELETE'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Eliminado',
                                        text: 'La sección ha sido eliminada correctamente',
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'No se pudo eliminar la sección.',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ha ocurrido un error en el servidor',
                                    confirmButtonText: 'Aceptar'
                                });
                                console.error('Error:', error);
                            });
                    }
                });
            }

            // Manejar la respuesta del servidor para guardar la sección
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const diasSeleccionados = document.querySelectorAll('input[name="dias[]"]:checked');
                if (diasSeleccionados.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar al menos un día de la semana.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                let seccionId = form.seccionId.value;
                let url = seccionId ? `{{ route('admin.secciones.update', '') }}/${seccionId}` :
                    `{{ route('admin.secciones.store') }}`;
                let formData = new FormData(form);

                if (seccionId) {
                    formData.append('_method', 'PUT');
                }

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            let errors = Object.values(data.errors).map(err => err.join('<br>')).join(
                                '<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: errors,
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: data.success,
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error en el servidor',
                            confirmButtonText: 'Aceptar'
                        });
                        console.error('Error:', error);
                    });
            });

            window.createSeccion = createSeccion;
            window.editSeccion = editSeccion;
            window.deleteSeccion = deleteSeccion;
        });
    </script>
@endpush
