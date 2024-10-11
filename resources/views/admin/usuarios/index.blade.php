@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0"> <i class="ri-user-3-line"></i> Usuarios</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Nomina</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">

                    
                    <h5 class="card-title mb-0">Lista de Usuarios</h5><br>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="las la-plus"></i> Añadir Usuario
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle datatable"
                            id="miTabla">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Imagen</th>
                                    <th>Nombre Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($usuario->image)
                                                <img src="{{ asset('storage/' . $usuario->image) }}" alt="Foto de perfil" width="50" height="50">
                                            @else
                                                <img src="{{ asset('images/default-profile.png') }}" alt="Foto de perfil" width="50" height="50">
                                            @endif
                                        </td>                                        
                                        <td>{{ $usuario->nombreUsuario }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->rol }}</td>
                                        <td>
                                            @if ($usuario->eliminado == 1)
                                                <span class="badge bg-success">Habilitado</span>
                                            @else
                                                <span class="badge bg-danger">Inhabilitado</span>
                                            @endif
                                        </td>

                                        <td>
                                            <button class="btn btn-sm btn-info edit-button"
                                                data-usuario="{{ json_encode($usuario) }}">
                                                <i class="ri-sip-fill"></i>
                                            </button>
                                            <!-- Botón de inhabilitar/habilitar -->
                                            <button
                                                class="btn btn-sm {{ $usuario->eliminado == 1 ? 'btn-danger' : 'btn-success' }}"
                                                onclick="toggleUserStatus({{ $usuario->idUsuario }})"
                                                {{ $usuario->idUsuario == auth()->id() ? 'disabled' : '' }}>
                                                @if ($usuario->eliminado == 1)
                                                    <i class="ri-delete-bin-line"></i>
                                                @else
                                                    <i class="ri-check-line"></i> Habilitar
                                                @endif
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

   <!-- Modal para editar usuario (reutilizable) -->
   <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-user-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="idUsuario">
                    <div class="mb-3">
                        <label for="editNombreUsuario" class="form-label">Nombre de Usuario <span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control" id="editNombreUsuario" name="nombreUsuario" required>
                        <div class="invalid-feedback" id="editNombreUsuarioError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Correo Electrónico <span
                                style="color: red">*</span></label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                        <div class="invalid-feedback" id="editEmailError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editRol" class="form-label">Rol <span style="color: red">*</span></label>
                        <select class="form-control" id="editRol" name="rol" required>
                            <option value="" disabled>Seleccione un rol</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                        <div class="invalid-feedback" id="editRolError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editImage" class="form-label">Foto (Opcional)</label>
                        <input type="file" class="form-control" id="editImage" name="image" accept="image/*">
                        <img id="previewEditImage" src="#" alt="Previsualización" style="display: none; width: 100px; height: 100px;">
                        <div class="invalid-feedback" id="editImageError"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear usuario -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Añadir Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="create-user-form" enctype="multipart/form-data">
                    @csrf
                    <!-- Campos del formulario para crear usuario -->
                    <div class="mb-3">
                        <label for="nombreUsuario" class="form-label">Nombre de Usuario <span
                                style="color: red">*</span></label>
                        <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" required>
                        <div class="invalid-feedback" id="nombreUsuarioError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico <span
                                style="color: red">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol <span style="color: red">*</span></label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="" disabled selected>Seleccione un rol</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Vendedor">Vendedor</option>
                        </select>
                        <div class="invalid-feedback" id="rolError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Foto (Opcional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <img id="previewImage" src="#" alt="Previsualización" style="display: none; width: 100px; height: 100px;">
                        <div class="invalid-feedback" id="imageError"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        // Previsualización de la imagen seleccionada
        function previewImage(input, previewElementId) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + previewElementId).attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Mostrar la previsualización en el formulario de creación
        $('#image').on('change', function() {
            previewImage(this, 'previewImage');
        });

        // Mostrar la previsualización en el formulario de edición
        $('#editImage').on('change', function() {
            previewImage(this, 'previewEditImage');
        });

        // Función para abrir el modal de edición y llenar los campos
        function openEditModal(usuario) {
            $('#editUserId').val(usuario.idUsuario);
            $('#editNombreUsuario').val(usuario.nombreUsuario);
            $('#editEmail').val(usuario.email);
            $('#editRol').val(usuario.rol);
            
            // Mostrar la imagen si existe, o ocultar la previsualización
            if (usuario.image) {
                $('#previewEditImage').attr('src', '{{ asset("storage/") }}/' + usuario.image).show();
            } else {
                $('#previewEditImage').hide();
            }

            // Limpiar errores previos
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');

            // Abrir el modal
            $('#editUserModal').modal('show');
        }

        // Evento para abrir el modal de edición
        $(document).on('click', '.edit-button', function() {
            let usuario = $(this).data('usuario');
            openEditModal(usuario);
        });

        // Manejo de errores en formularios
        function handleFormErrors(errors) {
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');

            $.each(errors, function(key, value) {
                $('#' + key).addClass('is-invalid');
                $('#' + key + 'Error').text(value[0]);
            });
        }

        // Función común para enviar solicitudes AJAX
        function sendAjaxRequest(url, method, formData, successMessage) {
            $.ajax({
                url: url,
                method: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire('Éxito!', successMessage, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(response) {
                    let errors = response.responseJSON.errors;
                    if (errors) {
                        handleFormErrors(errors);
                    }
                    Swal.fire('Error!', 'Hubo un problema al procesar la solicitud.', 'error');
                }
            });
        }

        // Enviar formulario de creación
        $('#create-user-form').on('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            sendAjaxRequest("{{ route('admin.usuarios.store') }}", 'POST', formData, 'Usuario creado exitosamente!');
        });

        // Enviar formulario de edición
        $('#edit-user-form').on('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            let userId = $('#editUserId').val();
            sendAjaxRequest("{{ route('admin.usuarios.update', '') }}/" + userId, 'POST', formData, 'Usuario actualizado exitosamente!');
        });

        // Función para cambiar el estado del usuario
        function toggleUserStatus(idUsuario) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El estado del usuario será cambiado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar la alerta de "procesando"
                    Swal.fire({
                        title: 'Procesando...',
                        text: 'Por favor, espera.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('admin.usuarios.toggleStatus', '') }}/" + idUsuario,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT'
                        },
                        success: function(response) {
                            Swal.close();
                            Swal.fire('Éxito!', 'El estado del usuario ha sido cambiado.', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.close();
                            Swal.fire('Error!', 'Hubo un problema al cambiar el estado del usuario.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
