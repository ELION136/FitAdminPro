@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-4">Gestión de Categorías de Servicios</h4>
                <button class="btn btn-success mb-3" id="btnAddCategoria">
                    <i class="ri-add-line align-bottom me-1"></i> Añadir Categoría
                </button>
            </div>

            <!-- Card con la Tabla de Categorías -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Lista de Categorías</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categorias as $categoria)
                                    <tr>
                                        <td>{{ $categoria->nombre }}</td>
                                        <td>{{ $categoria->descripcion ?: 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $categoria->estado ? 'bg-success' : 'bg-danger' }}">
                                                {{ $categoria->estado ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="editCategoria({{ $categoria }})">
                                                    <i class="ri-pencil-line"></i> Editar
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteCategoria({{ $categoria->idCategoria }})">
                                                    <i class="ri-delete-bin-line"></i> Eliminar
                                                </button>
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

    <!-- Modal para Crear/Editar Categoría -->
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formCategoria">
                    @csrf
                    <input type="hidden" id="categoriaId">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="modalCategoriaLabel">Añadir Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required maxlength="50">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" maxlength="255"
                                placeholder="Describe brevemente la categoría (opcional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
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
        $(document).ready(function() {
            // Abrir modal para crear una nueva categoría
            $('#btnAddCategoria').on('click', function() {
                $('#formCategoria')[0].reset();
                $('#categoriaId').val('');
                $('#modalCategoriaLabel').text('Añadir Categoría');
                $('#modalCategoria').modal('show');
            });

            // Enviar el formulario para crear o editar categoría
            $('#formCategoria').on('submit', function(e) {
                e.preventDefault();

                let id = $('#categoriaId').val();
                let url = id ? `{{ url('admin/categorias') }}/${id}` : `{{ url('admin/categorias') }}`;
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalCategoria').modal('hide');

                        // Mostrar alerta de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.success,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Recargar la página para mostrar los cambios
                        });
                    },
                    error: function(xhr) {
                        // Mostrar alerta de error
                        let errorMessage = 'Ha ocurrido un error.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).map(error =>
                                error.join('<br>')).join('<br>');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage, // Usar html para formatear el mensaje de error
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });

        // Función para editar una categoría
        function editCategoria(categoria) {
            $('#formCategoria')[0].reset();
            $('#categoriaId').val(categoria.idCategoria);
            $('#nombre').val(categoria.nombre);
            $('#descripcion').val(categoria.descripcion);
            $('#estado').val(categoria.estado);
            $('#modalCategoriaLabel').text('Editar Categoría');
            $('#modalCategoria').modal('show');
        }

        // Función para eliminar una categoría
        function deleteCategoria(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('admin/categorias') }}/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Mostrar alerta de éxito
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: response.success,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Recargar la página
                            });
                        },
                        error: function(xhr) {
                            // Mostrar alerta de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error al intentar eliminar la categoría.',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
