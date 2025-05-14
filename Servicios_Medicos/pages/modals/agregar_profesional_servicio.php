<div class="modal fade" id="modalAgregarPS" tabindex="-1" aria-labelledby="modalAgregarPSLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="asignacion.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAgregarPSLabel">Asignar Profesional al Servicio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <!-- Selección de Profesional -->
          <div class="mb-3">
            <label for="user_id" class="form-label">Profesional</label>
            <select name="user_id" class="form-select" required>
              <?php foreach ($doctors as $doctor): ?>
                <option value="<?= $doctor['user_id'] ?>">
                  <?= htmlspecialchars($doctor['doctor_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Selección de Servicio -->
          <div class="mb-3">
            <label for="service_id" class="form-label">Servicio</label>
            <select name="service_id" class="form-select" required>
              <?php foreach ($services as $service): ?>
                <option value="<?= $service['service_id'] ?>">
                  <?= htmlspecialchars($service['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
