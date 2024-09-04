<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServicioForm">
                <div class="modal-body">
                    <input type="hidden" id="editServicioId">
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editDescripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editDuracion" class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" id="editDuracion" name="duracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTipoServicio" class="form-label">Tipo de Servicio</label>
                        <select class="form-select" id="editTipoServicio" name="tipoServicio" required>
                            <option value="Individual">Individual</option>
                            <option value="Grupal">Grupal</option>
                            <option value="Online">Online</option>
                            <option value="Presencial">Presencial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoria" class="form-label">Categoría</label>
                        <select class="form-select" id="editCategoria" name="categoria" required>
                            <option value="Entrenamiento">Entrenamiento</option>
                            <option value="Nutrición">Nutrición</option>
                            <option value="Rehabilitación">Rehabilitación</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
