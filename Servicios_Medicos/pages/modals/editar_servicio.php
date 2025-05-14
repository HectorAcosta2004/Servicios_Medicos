<div class="modal fade" id="modalEditarS<?= $service['service_id'] ?>" tabindex="-1" aria-labelledby="modalEditarSLabel<?= $service['service_id'] ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarSLabel<?= $service['service_id'] ?>">Editar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="editar_servicio.php" method="POST">
          <!-- ID oculto del servicio -->
          <input type="hidden" name="service_id" value="<?= $service['service_id'] ?>">

          <div class="mb-3">
            <label for="nombre_servicio<?= $service['service_id'] ?>" class="form-label">Nombre del Servicio</label>
            <input type="text" class="form-control" id="nombre_servicio<?= $service['service_id'] ?>" name="nombre_servicio" value="<?= htmlspecialchars($service['name']) ?>" required>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
