@extends('layouts.app')

@section('content')
    <!-- Título de página y botón -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Lista de Membresías</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Paginas</a></li>
                        <li class="breadcrumb-item active">Membresías</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de membresías -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Listado de Membresías</h5>
                    <button class="btn btn-success" onclick="createMembresia()">
                        <i class="ri-add-line align-bottom me-1"></i> Añadir Membresía
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reservaTable" class="table table-bordered text-nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Duración (días)</th>
                                    <th>Precio (BOB)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $contador = 1; @endphp <!-- Inicializamos el contador -->
                                @foreach ($membresias as $membresia)
                                    <tr>
                                        <td>{{ $contador++ }}</td>
                                        <td>{{ $membresia->nombre }}</td>
                                        <td>{{ strlen($membresia->descripcion) > 50 ? substr($membresia->descripcion, 0, 50) . '...' : $membresia->descripcion }}
                                        </td>
                                        <td>{{ $membresia->duracionDias }}</td>
                                        <td>{{ number_format($membresia->precio, 2, '.', ',') }} BOB</td>
                                        <td>
                                            <button class="btn btn-info btn-sm"
                                                onclick="editMembresia({{ $membresia }})">
                                                <i class="ri-pencil-fill align-bottom"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="deleteMembresia({{ $membresia->idMembresia }})">
                                                <i class="ri-delete-bin-fill align-bottom"></i>
                                            </button>
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

    <!-- Modal Crear/Editar Membresía -->
    <div class="modal fade" id="modalMembresia" tabindex="-1" aria-labelledby="modalMembresiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formMembresia" method="POST">
                    @csrf
                    <input type="hidden" id="membresiaId" name="membresiaId">
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="modalMembresiaLabel">Añadir/Editar Membresía</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duracionDias" class="form-label">Duración (días)</label>
                                    <input type="number" class="form-control" id="duracionDias" name="duracionDias"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio (BOB)</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio"
                                required>
                        </div>

                        <!-- Fecha de Inicio -->
                        <div class="mb-3">
                            <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
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
            // Inicializar DataTable
            $('#reservaTable').DataTable({
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5,
                language: {
                    lengthMenu: "Mostrar _MENU_ registros por página",
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
                    }
                },
            });

            let $form = $('#formMembresia');

            // Función para abrir el modal de creación de una nueva membresía
            window.createMembresia = function() {
                $form[0].reset();
                $('#membresiaId').val('');
                $('#modalMembresiaLabel').text('Añadir Membresía');
                $('#modalMembresia').modal('show');
            };

            // Función para abrir el modal de edición de una membresía existente
            window.editMembresia = function(membresia) {
                $form[0].reset();
                $('#membresiaId').val(membresia.idMembresia);
                $('#nombre').val(membresia.nombre);
                $('#descripcion').val(membresia.descripcion);
                $('#duracionDias').val(membresia.duracionDias);
                $('#precio').val(membresia.precio);
                $('#fechaInicio').val(membresia.fechaInicio);
                $('#modalMembresiaLabel').text('Editar Membresía');
                $('#modalMembresia').modal('show');
            };

            // Crear o Editar Membresía
            $form.on('submit', function(e) {
                e.preventDefault();
                let membresiaId = $('#membresiaId').val();
                let url = membresiaId ? `{{ route('admin.membresias.update', '') }}/${membresiaId}` :
                    `{{ route('admin.membresias.store') }}`;
                let formData = $form.serialize(); // Serializar el formulario

                if (membresiaId) {
                    formData += '&_method=PUT';
                }

                $.post(url, formData)
                    .done(function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Membresía guardada correctamente',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .fail(function(xhr) {
                        let errorMessage = 'Ha ocurrido un error en el servidor';
                        let errors = xhr.responseJSON?.errors;

                        if (errors) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                            confirmButtonText: 'Aceptar'
                        });
                    });
            });

            // Función para eliminar una membresía
            window.deleteMembresia = function(id) {
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
                        $.ajax({
                            url: `{{ route('admin.membresias.destroy', '') }}/${id}`,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        .done(function(data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'La membresía ha sido eliminada correctamente',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ha ocurrido un error en el servidor',
                                confirmButtonText: 'Aceptar'
                            });
                        });
                    }
                });
            };
        });
    </script>
@endpush
