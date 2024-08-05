<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Crear Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createUserForm" method="POST" action="{{ route('admin.usuarios.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre de Usuario *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña *</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="primerApellido" class="form-label">Primer Apellido *</label>
                            <input type="text" class="form-control @error('primerApellido') is-invalid @enderror" id="primerApellido" name="primerApellido" value="{{ old('primerApellido') }}" required>
                            @error('primerApellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control @error('segundoApellido') is-invalid @enderror" id="segundoApellido" name="segundoApellido" value="{{ old('segundoApellido') }}">
                            @error('segundoApellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento *</label>
                            <input type="date" class="form-control @error('fechaNacimiento') is-invalid @enderror" id="fechaNacimiento" name="fechaNacimiento" value="{{ old('fechaNacimiento') }}" required>
                            @error('fechaNacimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="genero" class="form-label">Género *</label>
                            <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero" required>
                                <option value="">Seleccionar</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="telefonoEmergencia" class="form-label">Teléfono de Emergencia</label>
                            <input type="text" class="form-control @error('telefonoEmergencia') is-invalid @enderror" id="telefonoEmergencia" name="telefonoEmergencia" value="{{ old('telefonoEmergencia') }}">
                            @error('telefonoEmergencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    
                    <div id="empleadoFields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechaContratacion" class="form-label">Fecha de Contratación</label>
                                <input type="date" class="form-control" id="fechaContratacion" name="fechaContratacion">
                            </div>
                            <div class="col-md-6">
                                <label for="puesto" class="form-label">Puesto</label>
                                <input type="text" class="form-control" id="puesto" name="puesto">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const idRolSelect = document.getElementById('idRol');
        const empleadoFields = document.getElementById('empleadoFields');

        idRolSelect.addEventListener('change', function () {
            if (idRolSelect.value == '2') { // Asumiendo que 2 es el ID del rol de Empleado
                empleadoFields.style.display = 'block';
            } else {
                empleadoFields.style.display = 'none';
            }
        });
    });
</script>