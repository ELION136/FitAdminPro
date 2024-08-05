
<!-- resources/views/admin/usuarios/partials/edit_modal.blade.php -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editNombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="editNombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPrimerApellido" class="form-label">Primer Apellido</label>
                                <input type="text" class="form-control" id="editPrimerApellido" name="primerApellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="editSegundoApellido" class="form-label">Segundo Apellido</label>
                                <input type="text" class="form-control" id="editSegundoApellido" name="segundoApellido">
                            </div>
                            <div class="mb-3">
                                <label for="editFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="editFechaNacimiento" name="fechaNacimiento" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDireccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="editDireccion" name="direccion">
                            </div>
                            <div class="mb-3">
                                <label for="editTelefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="editTelefono" name="telefono">
                            </div>
                            <div class="mb-3">
                                <label for="editTelefonoEmergencia" class="form-label">Teléfono de Emergencia</label>
                                <input type="text" class="form-control" id="editTelefonoEmergencia" name="telefonoEmergencia">
                            </div>
                            <div class="mb-3">
                                <label for="editGenero" class="form-label">Género</label>
                                <select class="form-control" id="editGenero" name="genero" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
