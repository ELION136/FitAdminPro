@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Gestión de Secciones</h4>
                <button class="btn btn-primary" onclick="createSeccion()">Añadir Sección</button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Capacidad</th>
                                <th>Entrenador</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($secciones as $seccion)
                                <tr>
                                    <td>{{ $seccion->servicio->nombre }}</td>
                                    <td>{{ $seccion->fechaInicio }}</td>
                                    <td>{{ $seccion->fechaFin }}</td>
                                    <td>{{ $seccion->horaInicio }}</td>
                                    <td>{{ $seccion->horaFin }}</td>
                                    <td>{{ $seccion->capacidad }}</td>
                                    <td>{{ $seccion->entrenador->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="editSeccion({{ $seccion }})">Editar</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSeccion({{ $seccion->idSeccion }})">Eliminar</button>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSeccion" method="POST">
                @csrf
                <input type="hidden" id="seccionId" name="seccionId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSeccionLabel">Añadir/Editar Sección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="idServicio">Servicio</label>
                        <select class="form-control" id="idServicio" name="idServicio" required onchange="updateCapacidad()">
                            <option value="" selected disabled>Seleccione un servicio</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->idServicio }}" data-capacidad="{{ $servicio->capacidadMaxima }}">{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio">Fecha Inicio</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaFin">Fecha Fin</label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                    </div>
                    <div class="form-group">
                        <label for="horaInicio">Hora Inicio</label>
                        <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
                    </div>
                    <div class="form-group">
                        <label for="horaFin">Hora Fin</label>
                        <input type="time" class="form-control" id="horaFin" name="horaFin" required>
                    </div>
                    <div class="form-group">
                        <label for="capacidad">Capacidad</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" readonly>
                    </div>
                    <div class="form-group">
                        <label for="idEntrenador">Entrenador</label>
                        <select class="form-control" id="idEntrenador" name="idEntrenador">
                            <option value="" selected>Ninguno</option>
                            @foreach ($entrenadores as $entrenador)
                                <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}</option>
                            @endforeach
                        </select>
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
    let form = document.getElementById('formSeccion');
    const today = new Date().toISOString().split('T')[0];
    const nextYear = new Date();
    nextYear.setFullYear(nextYear.getFullYear() + 1);
    const maxDate = nextYear.toISOString().split('T')[0];

    // Establece los límites para las fechas
    document.getElementById('fechaInicio').setAttribute('min', today);
    document.getElementById('fechaInicio').setAttribute('max', maxDate);
    document.getElementById('fechaFin').setAttribute('min', today);
    document.getElementById('fechaFin').setAttribute('max', maxDate);

    // Función para abrir el modal de creación de una nueva sección
    function createSeccion() {
        form.reset();
        form.seccionId.value = '';
        document.getElementById('idServicio').removeAttribute('disabled');
        document.getElementById('capacidad').removeAttribute('readonly');
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
        document.getElementById('modalSeccionLabel').textContent = 'Editar Sección';

        // Deshabilitar servicio y capacidad en edición
        document.getElementById('idServicio').setAttribute('disabled', true);
        document.getElementById('capacidad').setAttribute('readonly', true);

        new bootstrap.Modal(document.getElementById('modalSeccion')).show();
    }

    // Función para actualizar la capacidad del servicio seleccionado
    function updateCapacidad() {
        const servicioSelect = document.getElementById('idServicio');
        const capacidad = servicioSelect.options[servicioSelect.selectedIndex].getAttribute('data-capacidad');
        document.getElementById('capacidad').value = capacidad;
    }

    // Crear o Editar Sección
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let seccionId = form.seccionId.value;
        let url = seccionId ? `{{ route('admin.secciones.update', '') }}/${seccionId}` : `{{ route('admin.secciones.store') }}`;
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
                let errors = Object.values(data.errors).map(err => err.join('<br>')).join('<br>');
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
                    text: 'Sección guardada correctamente',
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
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
</script>
@endpush
