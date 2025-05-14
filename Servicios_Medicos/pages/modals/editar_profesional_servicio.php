<?php
$modal_id = 'modalEditarPS' . $service['service_id'];
?>

<div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="<?= $modal_id ?>Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="asignacion.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="<?= $modal_id ?>Label">Editar Profesional</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Servicio (readonly o oculto si no se va a cambiar) -->
          <div class="mb-3">
            <label for="service_id_<?= $modal_id ?>" class="form-label">Servicio</label>
            <select name="service_id" id="service_id_<?= $modal_id ?>" class="form-select" required>
              <?php foreach ($services as $srv): ?>
                <option value="<?= $srv['service_id'] ?>" <?= $srv['service_id'] == $service['service_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($srv['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Profesional -->
          <div class="mb-3">
            <label for="user_id_<?= $modal_id ?>" class="form-label">Profesional</label>
            <select name="user_id" id="user_id_<?= $modal_id ?>" class="form-select" required>
              <?php foreach ($doctors as $doctor): ?>
                <option value="<?= $doctor['user_id'] ?>" <?= $doctor['user_id'] == $service['user_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($doctor['doctor_name']) ?>
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