<?php
session_start();

require_once '../factories/FormularioPerfil.php';
require_once '../factories/PacienteFactory.php';
require_once '../factories/MedicoFactory.php';

$rol = $_SESSION['rol'] ?? 'paciente';

switch ($rol) {
    case 'medico':
        $factory = new MedicoFactory();
        break;
    case 'paciente':
    default:
        $factory = new PacienteFactory();
        break;
}

$formulario = $factory->crearFormulario();
?>

<div class="modal-header">
  <h5 class="modal-title">Editar Perfil</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
  <!-- Formulario para editar perfil -->
  <form id="formEditarPerfil" method="POST" action="procesar_edicion_perfil.php">
    <?= $formulario->render() ?>
  </form>
</div>
<div class="modal-footer">
  <button type="submit" form="formEditarPerfil" class="btn btn-primary">Guardar cambios</button>
</div>
