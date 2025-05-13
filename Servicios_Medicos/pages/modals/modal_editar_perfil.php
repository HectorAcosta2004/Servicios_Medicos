<?php
session_start();

require_once '../factories/FormularioPerfil.php';
require_once '../factories/PacienteFactory.php';
require_once '../factories/MedicoFactory.php';

// Detectar rol del usuario (ajusta según cómo guardes esto)
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

<div class="modal fade" id="modalEditarU" tabindex="-1" aria-labelledby="modalEditarULabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarULabel">Editar Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <?= $formulario->render() ?>
      </div>
    </div>
  </div>
</div>
