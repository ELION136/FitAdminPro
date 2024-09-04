<!-- Modal para Crear Servicio -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Crear Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createServicioForm">
                    @csrf <!-- Token CSRF -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="duracion" class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" id="duracion" name="duracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipoServicio" class="form-label">Tipo de Servicio</label>
                        <select class="form-select" id="tipoServicio" name="tipoServicio" required>
                            <option value="Individual">Individual</option>
                            <option value="Grupal">Grupal</option>
                            <option value="Online">Online</option>
                            <option value="Presencial">Presencial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="Entrenamiento">Entrenamiento</option>
                            <option value="Nutrición">Nutrición</option>
                            <option value="Rehabilitación">Rehabilitación</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
