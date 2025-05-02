<div class="modal fade" id="modalEditarCita" tabindex="-1" aria-labelledby="modalEditarCitaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" id="editarCitaForm">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarCitaLabel">Editar Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="id_agenda" name="id_agenda">
          <div class="mb-3">
            <label for="fecha" class="form-label">Fecha y Hora</label>
            <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
          </div>
          <div class="mb-3">
            <label for="doctor_id" class="form-label">Doctor</label>
            <select class="form-control" id="doctor_id" name="doctor_id" required>
              <!-- Listado de doctores -->
            </select>
          </div>
          <div class="mb-3">
            <label for="servicio_id" class="form-label">Servicio</label>
            <select class="form-control" id="servicio_id" name="servicio_id" required>
              <!-- Listado de servicios -->
            </select>
          </div>
          <div class="mb-3">
            <label for="paciente_id" class="form-label">Paciente</label>
            <select class="form-control" id="paciente_id" name="paciente_id" required>
              <!-- Listado de pacientes -->
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
