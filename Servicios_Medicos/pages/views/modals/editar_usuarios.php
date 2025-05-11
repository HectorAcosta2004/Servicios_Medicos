<div class="modal fade" id="modalEditarU<?= $user['user_id'] ?>" tabindex="-1" aria-labelledby="modalEditarULabel<?= $user['user_id'] ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarULabel<?= $user['user_id'] ?>">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="editar_usuario.php?user_id=<?= $user['user_id'] ?>" method="POST">
          <!-- ID oculto del usuario -->
          <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

          <div class="mb-3">
            <label for="name<?= $user['user_id'] ?>" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name<?= $user['user_id'] ?>" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
          </div>

          <div class="mb-3">
            <label for="last_name<?= $user['user_id'] ?>" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="last_name<?= $user['user_id'] ?>" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
          </div>

          <div class="mb-3">
            <label for="rol<?= $user['user_id'] ?>" class="form-label">Rol</label>
            <select class="form-select" id="rol<?= $user['user_id'] ?>" name="rol" required>
              <option value="admin" <?= $user['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
              <option value="professional" <?= $user['rol'] === 'professional' ? 'selected' : '' ?>>Profesional</option>
              <option value="patient" <?= $user['rol'] === 'patient' ? 'selected' : '' ?>>Paciente</option>
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
