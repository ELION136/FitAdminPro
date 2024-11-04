@extends('layouts.app')
@section('content')
    <br>
    <br>
    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-profile.png') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                                alt="Foto de perfil">
                        </div>
                        <h5 class="fs-16 mb-1">{{ $user->nombreUsuario }}</h5>
                        <p class="text-muted mb-0">Administrador</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i> Detalles Personales
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('profile.update') }}" method="POST" id="updateProfileForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="nombreUsuario" class="form-label">Nombre de usuario</label>
                                            <input type="text" class="form-control @error('nombreUsuario') is-invalid @enderror" id="nombreUsuario" name="nombreUsuario"
                                                value="{{ old('nombreUsuario', $user->nombreUsuario) }}" required>
                                            @error('nombreUsuario')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Foto de Perfil</label>
                                            <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                            <img id="preview-image" class="mt-3 rounded-circle avatar-xl img-thumbnail material-shadow d-none" alt="Vista previa de imagen" style="display: none; max-width: 150px;">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="password" class="form-label">Contraseña (dejar en blanco si no se quiere cambiar)</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="8 - 15 Caracteres">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="8 - 15 Caracteres">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-3 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if (session('success'))
    <script>
        $(document).ready(function() {
            Swal.fire({
                title: 'Éxito',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Previsualización de imagen
        $('#image').on('change', function(e) {
            let reader = new FileReader();
            reader.onload = (event) => {
                $('#preview-image').attr('src', event.target.result).removeClass('d-none').show();
            };
            reader.readAsDataURL(e.target.files[0]);
        });

        // Manejar el envío del formulario con AJAX
        $('#updateProfileForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, actualizar perfil!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('profile.update') }}",
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Éxito',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                            // Actualiza la imagen de perfil en la interfaz
                            if (formData.get('image')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    $('.user-profile-image').attr('src', e.target.result);
                                };
                                reader.readAsDataURL(formData.get('image'));
                            }

                            // Limpiar campos de imagen, contraseña y confirmación de contraseña
                            $('#image').val('');
                            $('#preview-image').hide();
                            $('#password').val('');
                            $('#password_confirmation').val('');
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: xhr.responseJSON.error,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush



    
