<?php
require_once '../database.php';
require_once 'FormularioPerfil.php';
require_once 'PacienteFormulario.php';

class PacienteFactory {
    public function crearFormulario(): FormularioPerfil {
        $user_id = $_SESSION['user_id'];
        return new PacienteFormulario($user_id);
    }
}

?>