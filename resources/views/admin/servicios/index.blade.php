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
                                    <th>Capacidad</th>
                                    <th>Precio Total (BOB)</th>
                                    <th>Sesiones</th>
                                    <th>Entrenador</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->nombre }}</td>
                                        <td>{{ $servicio->capacidad }}</td>
                                        <td>{{ number_format($servicio->precioTotal, 2, '.', ',') }} BOB</td>
                                        <td>{{ $servicio->cantidadSesiones ?? 'N/A' }}</td>
                                        <td>{{ $servicio->entrenador->nombre ?? 'Sin entrenador' }}</td>
                                        <td>{{ $servicio->estado ? 'Activo' : 'Inactivo' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('servicio.horarios.edit', $servicio->idServicio) }}" 
                                                    class="btn btn-info btn-sm" 
                                                    title="Editar Horarios">
                                                    <i class="ri-calendar-check-line"></i>
                                                 </a>
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="editServicio({{ $servicio }})"><i
                                                        class="ri-pencil-fill"></i></button>
                                                @if(auth()->user()->rol == 'Administrador')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="deleteServicio({{ $servicio->idServicio }})"><i
                                                        class="ri-delete-bin-fill"></i></button>
                                                @endif
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
                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50">
                                </div>
                            </div>
                            <!-- Capacidad -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacidad" class="form-label">Capacidad</label>
                                    <input type="number" class="form-control" id="capacidad" name="capacidad" required min="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Precio Total -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precioTotal" class="form-label">Precio Total (BOB)</label>
                                    <input type="number" step="0.01" class="form-control" id="precioTotal" name="precioTotal" required min="0" max="10000">
                                </div>
                            </div>
                            <!-- Sesiones -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cantidadSesiones" class="form-label">Cantidad de Sesiones</label>
                                    <input type="number" class="form-control" id="cantidadSesiones" name="cantidadSesiones" min="1">
                                </div>
                            </div>
                        </div>
                        <!-- Entrenador -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="idEntrenador" class="form-label">Entrenador</label>
                                <select class="form-control" id="idEntrenador" name="idEntrenador" required>
                                    <option value="">Seleccione un entrenador</option>
                                    @foreach ($entrenadores as $entrenador)
                                        <option value="{{ $entrenador->idEntrenador }}">{{ $entrenador->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Descripción -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" maxlength="255"></textarea>
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
                $('#capacidad').val(servicio.capacidad);
                $('#precioTotal').val(servicio.precioTotal);
                $('#cantidadSesiones').val(servicio.cantidadSesiones);
                $('#idEntrenador').val(servicio.idEntrenador);
                $('#modalServicioLabel').text('Editar Servicio');
                $('#modalServicio').modal('show');
            }

            function deleteServicio(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede revertir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('admin/servicios') }}/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Eliminado', response.success, 'success').then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            }

            $form.on('submit', function(e) {
                e.preventDefault();
                let servicioId = $('#servicioId').val();
                let url = servicioId ? `{{ url('admin/servicios') }}/${servicioId}` : `{{ url('admin/servicios') }}`;
                let formData = new FormData($form[0]);

                if (servicioId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            });

            window.createServicio = createServicio;
            window.editServicio = editServicio;
            window.deleteServicio = deleteServicio;

            $('#servicioTable').DataTable({
                pageLength: 5,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    search: "Buscar:",
                    zeroRecords: "No se encontraron registros coincidentes",
                    paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
                },
            });
        });
    </script>
@endpush
