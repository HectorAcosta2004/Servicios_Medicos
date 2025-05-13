<div class="modal fade" id="modalEditarPS<?= $service_id ?>" tabindex="-1" aria-labelledby="modalEditarPSLabel<?= $service_id ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarPSLabel<?= $service_id ?>">Editar Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="editar_asignacion.php" method="POST">
          <!-- ID oculto del servicio -->
          <input type="hidden" name="service_id" value="<?= $service_id ?>">

          <div class="mb-3">
            <label for="service_id" class="form-label">Servicio</label>
            <select name="service_id" id="service_id" class="form-select" disabled>
              <?php foreach ($services as $service): ?>
                <option value="<?= $service['service_id'] ?>" <?= $service['service_id'] == $service_id ? 'selected' : '' ?>>
                  <?= htmlspecialchars($service['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="user_id" class="form-label">Profesional</label>
            <select name="user_id" id="user_id" class="form-select" required>
              <?php foreach ($doctors as $doctor): ?>
                <option value="<?= $doctor['user_id'] ?>" <?= $doctor['user_id'] == $current_user_id ? 'selected' : '' ?>>
                  <?= htmlspecialchars($doctor['doctor_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
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
