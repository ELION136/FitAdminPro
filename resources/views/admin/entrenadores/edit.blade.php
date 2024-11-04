@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Editar</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Formulario</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Formulario de edición</h4>
            </div>
            <div class="card-body">
                <form id="editTrainerForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row gy-4">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="nombre" class="form-label">Nombre <span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $entrenador->nombre) }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="primerApellido" class="form-label">Primer Apellido <span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="primerApellido" name="primerApellido" value="{{ old('primerApellido', $entrenador->primerApellido) }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido', $entrenador->segundoApellido) }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento <span style="color:red">*</span></label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento', \Carbon\Carbon::parse($entrenador->fechaNacimiento)->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="genero" class="form-label">Género <span style="color:red">*</span></label>
                            <select class="form-control" id="genero" name="genero" required>
                                <option value="" disabled>Seleccione</option>
                                <option value="Masculino" {{ old('genero', $entrenador->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $entrenador->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $entrenador->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="fechaContratacion" class="form-label">Fecha de Contratación <span style="color:red">*</span></label>
                            <input type="date" class="form-control" id="fechaContratacion" name="fechaContratacion" value="{{ old('fechaContratacion', \Carbon\Carbon::parse($entrenador->fechaContratacion)->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $entrenador->telefono) }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $entrenador->direccion) }}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="especialidad" class="form-label">Especialidad <span style="color:red">*</span></label>
                            <select class="form-control" id="especialidad" name="especialidad" required>
                                <option value="" disabled>Seleccione una especialidad</option>
                                <option value="Entrenamiento Personal" {{ old('especialidad', $entrenador->especialidad) == 'Entrenamiento Personal' ? 'selected' : '' }}>Entrenamiento Personal</option>
                                <option value="Entrenamiento Cardiovascular" {{ old('especialidad', $entrenador->especialidad) == 'Entrenamiento Cardiovascular' ? 'selected' : '' }}>Entrenamiento Cardiovascular</option>
                                <option value="Boxeo" {{ old('especialidad', $entrenador->especialidad) == 'Boxeo' ? 'selected' : '' }}>Boxeo</option>
                                <option value="Entrenamiento de Resistencia" {{ old('especialidad', $entrenador->especialidad) == 'Entrenamiento de Resistencia' ? 'selected' : '' }}>Entrenamiento de Resistencia</option>
                                <option value="Nutrición y Bienestar" {{ old('especialidad', $entrenador->especialidad) == 'Nutrición y Bienestar' ? 'selected' : '' }}>Nutrición y Bienestar</option>
                                <option value="Otro" {{ old('especialidad', $entrenador->especialidad) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2">{{ old('descripcion', $entrenador->descripcion) }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="image" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 text-end">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.entrenadores.index') }}" class="btn btn-danger me-2">Volver</a>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#editTrainerForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.entrenadores.update', $entrenador->idEntrenador) }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Entrenador actualizado',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('admin.entrenadores.index') }}";
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                        $.each(errors, function(field, messages) {
                            let input = $(`#${field}`);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error inesperado.',
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
