@extends('layouts.app')

@section('content')
    <!-- Título de la página y breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Gestión de Servicios</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Servicios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para añadir servicio -->
    <div class="row mb-3">
        <div class="col-sm-auto">
            <div class="d-flex flex-wrap align-items-start gap-2">
                <button class="btn btn-success add-btn" onclick="createServicio()"><i
                        class="ri-add-line align-bottom me-1"></i> Añadir Servicio</button>
            </div>
        </div>
    </div>

    <!-- Tabla de Servicios -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <h5 class="card-title mb-0">Lista de Servicios</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="servicioTable" class="table table-bordered text-nowrap w-100">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Duración</th>
                                    <th>Capacidad</th>
                                    <th>Precio Total (BOB)</th>
                                    <th>Incluye Costo Entrada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>
                                            @php
                                                $horas = floor($servicio->duracion / 60);
                                                $minutos = $servicio->duracion % 60;
                                            @endphp
                                            {{ $horas > 0 ? $horas . ' hora' . ($horas > 1 ? 's' : '') : '' }}
                                            {{ $minutos > 0 ? $minutos . ' minuto' . ($minutos > 1 ? 's' : '') : '' }}
                                        </td>
                                        <td>{{ $servicio->capacidad }}</td>
                                        <td>{{ number_format($servicio->precioTotal, 2, '.', ',') }} BOB</td>
                                        <td>{{ $servicio->incluyeCostoEntrada ? 'Sí' : 'No' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="editServicio({{ $servicio }})"><i
                                                        class="ri-pencil-fill"></i></button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="deleteServicio({{ $servicio->idServicio }})"><i
                                                        class="ri-delete-bin-fill"></i></button>
                                            </div>
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

    <!-- Modal Crear/Editar Servicio -->
    <div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formServicio" method="POST">
                    @csrf
                    <input type="hidden" id="servicioId" name="servicioId">
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="modalServicioLabel">Añadir/Editar Servicio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Campos del formulario con restricciones -->
                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required
                                        maxlength="50">
                                </div>
                            </div>
                            <!-- Capacidad -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacidad" class="form-label">Capacidad</label>
                                    <input type="number" class="form-control" id="capacidad" name="capacidad" required
                                        min="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Duración -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duracion" class="form-label">Duración (minutos)</label>
                                    <input type="number" class="form-control" id="duracion" name="duracion" required
                                        min="60" max="1440">
                                </div>
                            </div>
                            <!-- Precio Total -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precioTotal" class="form-label">Precio Total (BOB)</label>
                                    <input type="number" step="0.01" class="form-control" id="precioTotal"
                                        name="precioTotal" required min="0" max="10000">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Hora Inicio -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="horaInicio" class="form-label">Hora de Inicio</label>
                                    <input type="time" class="form-control" id="horaInicio" name="horaInicio" required
                                        min="06:00" max="22:00">
                                </div>
                            </div>
                            <!-- Fecha Inicio -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio"
                                        required >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Fecha Fin -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fechaFin" class="form-label">Fecha de Fin</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin" required
                                        >
                                </div>
                            </div>
                            <!-- Entrenador -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idEntrenador" class="form-label">Entrenador</label>
                                    <select class="form-control" id="idEntrenador" name="idEntrenador" required>
                                        <option value="">Seleccione un entrenador</option>
                                        @foreach ($entrenadores as $entrenador)
                                            <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Días de la semana -->
                        <div class="mb-3">
                            <label for="idDia" class="form-label">Días de la semana</label>
                            <div class="row">
                                @foreach ($diasSemana as $dia)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="idDia[]"
                                                id="dia_{{ $dia->idDia }}" value="{{ $dia->idDia }}">
                                            <label class="form-check-label" for="dia_{{ $dia->idDia }}">
                                                {{ ucfirst($dia->nombreDia) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Descripción e Incluye Costo Entrada -->
                        <div class="row">
                            <!-- Descripción -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" maxlength="255"></textarea>
                                </div>
                            </div>
                            <!-- Incluye Costo Entrada -->
                            <div class="col-md-4">
                                <div class="mb-3 form-check mt-4">
                                    <input type="checkbox" class="form-check-input" id="incluyeCostoEntrada"
                                        name="incluyeCostoEntrada" value="1">
                                    <label class="form-check-label" for="incluyeCostoEntrada">Incluye Costo
                                        Entrada</label>
                                </div>
                            </div>
                        </div>
                        <!-- Mensajes de error -->
                        <div id="formErrors" class="alert alert-danger d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const $form = $('#formServicio');
            const horaApertura = '06:00';
            const horaCierre = '22:00';

            function createServicio() {
                $form[0].reset();
                $('#servicioId').val('');
                $('#modalServicioLabel').text('Añadir Servicio');
                $('#formErrors').addClass('d-none');
                $('#modalServicio').modal('show');
            }

            function editServicio(servicio) {
                $form[0].reset();
                $('#servicioId').val(servicio.idServicio);
                $('#nombre').val(servicio.nombre);
                $('#descripcion').val(servicio.descripcion);
                $('#duracion').val(servicio.duracion);
                $('#capacidad').val(servicio.capacidad);
                $('#precioTotal').val(servicio.precioTotal);
                $('#horaInicio').val(servicio.horaInicio.slice(0, 5)); // Tomar solo HH:MM
                $('#idEntrenador').val(servicio.idEntrenador);
                $('#incluyeCostoEntrada').prop('checked', servicio.incluyeCostoEntrada ? true : false);
                $('#formErrors').addClass('d-none');

                // Asegúrate de que las fechas están en el formato adecuado
                $('#fechaInicio').val(formatDate(servicio.fechaInicio));
                $('#fechaFin').val(formatDate(servicio.fechaFin));

                // Limpiar los checkboxes de días
                $('input[name="idDia[]"]').prop('checked', false);

                // Marcar los días seleccionados para el servicio
                if (servicio.dias_semana) {
                    servicio.dias_semana.forEach(function(dia) {
                        $('#dia_' + dia.idDia).prop('checked', true);
                    });
                } else if (servicio.diasSemana) {
                    servicio.diasSemana.forEach(function(dia) {
                        $('#dia_' + dia.idDia).prop('checked', true);
                    });
                }

                $('#modalServicioLabel').text('Editar Servicio');
                $('#modalServicio').modal('show');
            }

            // Función para formatear la fecha a 'YYYY-MM-DD'
            function formatDate(dateString) {
                if (!dateString) return '';
                return dateString.split(' ')[0]; // Extraer solo la parte de la fecha si viene con hora
            }


            function deleteServicio(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('admin/servicios') }}/${id}`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Eliminado',
                                        text: 'El servicio ha sido eliminado correctamente',
                                        confirmButtonText: 'Aceptar'
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'No se pudo eliminar el servicio.',
                                        confirmButtonText: 'Aceptar'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Ha ocurrido un error en el servidor',
                                    confirmButtonText: 'Aceptar'
                                });
                                console.error('Error:', error);
                            }
                        });
                    }
                });
            }

            // Validación de hora y duración en el frontend
            function validarHoraFin() {
                const horaInicio = $('#horaInicio').val();
                const duracion = parseInt($('#duracion').val());

                if (horaInicio && duracion) {
                    const [hora, minuto] = horaInicio.split(':').map(Number);
                    const fechaHoraInicio = new Date();
                    fechaHoraInicio.setHours(hora, minuto, 0, 0);

                    const fechaHoraFin = new Date(fechaHoraInicio.getTime() + duracion * 60000);
                    const horaCierre = new Date();
                    horaCierre.setHours(22, 0, 0, 0);

                    if (fechaHoraFin > horaCierre) {
                        return false;
                    }
                }
                return true;
            }

            $form.on('submit', function(e) {
                e.preventDefault();

                // Validación de hora de fin en el frontend
                if (!validarHoraFin()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La hora de fin no puede exceder las 22:00.',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                let servicioId = $('#servicioId').val();
                let url = servicioId ? `{{ url('admin/servicios') }}/${servicioId}` :
                    `{{ url('admin/servicios') }}`;
                let formData = new FormData($form[0]);

                if (servicioId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.errors) {
                            let errors = '';
                            $.each(data.errors, function(key, value) {
                                errors += value.join('<br>') + '<br>';
                            });
                            $('#formErrors').html(errors);
                            $('#formErrors').removeClass('d-none');
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: data.success,
                                confirmButtonText: 'Aceptar'
                            }).then(function() {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error en el servidor',
                            confirmButtonText: 'Aceptar'
                        });
                        console.error('Error:', error);
                    }
                });
            });

            window.createServicio = createServicio;
            window.editServicio = editServicio;
            window.deleteServicio = deleteServicio;

            // Agregar eventos para validar la hora y la duración
            $('#horaInicio').on('change', validarHoraFin);
            $('#duracion').on('change', validarHoraFin);

            $('#servicioTable').DataTable({
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
    </script>
@endpush
