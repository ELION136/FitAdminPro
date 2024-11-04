@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Formulario</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Añadir</a></li>
                    <li class="breadcrumb-item active">Cliente</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Datos del Cliente</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.clientes.store') }}" method="POST" enctype="multipart/form-data" id="createClient">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="primerApellido" class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" id="primerApellido" name="primerApellido" value="{{ old('primerApellido') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido') }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" max="{{ now()->format('Y-m-d') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="genero" class="form-label">Género</label>
                            <select class="form-control" id="genero" name="genero" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="telefonoEmergencia" class="form-label">Teléfono de Emergencia</label>
                            <input type="text" class="form-control" id="telefonoEmergencia" name="telefonoEmergencia">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Foto de Perfil</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" id="image" name="image" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-end">
                            <a href="{{ route('admin.clientes.index') }}" class="btn btn-danger me-2">Volver</a>
                            <button type="submit" class="btn btn-primary">Crear Nuevo Cliente</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#createClient').on('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, crear cliente!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('admin.clientes.store') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Cliente registrado',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('admin.clientes.index') }}";
                                });
                            }
                        },
                        error: function (xhr) {
                            let errors = xhr.responseJSON.errors;
                            $('.form-control').removeClass('is-invalid'); // Resetear errores anteriores
                            $('.invalid-feedback').text(''); // Limpiar mensajes anteriores
                            $.each(errors, function (field, message) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field).next('.invalid-feedback').text(message[0]);
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection

