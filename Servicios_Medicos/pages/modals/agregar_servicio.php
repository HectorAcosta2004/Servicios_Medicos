<div class="modal fade" id="modalAgregarS" tabindex="-1" aria-labelledby="modalAgregarSLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarSLabel">Agregar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="guardar_servicio.php" method="POST">
          <div class="mb-3">
            <label for="nombre_servicio" class="form-label">Nombre del Servicio</label>
            <input type="text" class="form-control" id="nombre_servicio" name="nombre_servicio" required>
          </div class="text-end">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
      </div>
    </div>
  </div>
</div>
