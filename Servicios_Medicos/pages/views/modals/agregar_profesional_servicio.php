<!-- views/modals/agregar_profesional_servicio.php -->
<div class="modal fade" id="modalAgregarPS" tabindex="-1" aria-labelledby="modalAgregarPSLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarPSLabel">Asignar Profesional al Servicio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="asignacion.php" method="POST">
          <div class="mb-3">
            <label for="service_id" class="form-label">Servicio</label>
            <select name="service_id" id="service_id" class="form-select">
              <?php foreach ($services as $service): ?>
                <option value="<?= $service['service_id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="user_id" class="form-label">Profesional</label>
            <select name="user_id" id="user_id" class="form-select">
              <?php foreach ($doctors as $doctor): ?>
                <option value="<?= $doctor['user_id'] ?>"><?= htmlspecialchars($doctor['doctor_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
      </div>
    </div>
  </div>
</div>
