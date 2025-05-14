<?php
require_once '../database.php';
require_once 'FormularioPerfil.php';
require_once 'MedicoFormulario.php';

class MedicoFactory {
    public function crearFormulario(): FormularioPerfil {
         $user_id = $_SESSION['user_id'];
        return new MedicoFormulario($user_id);
    }
}
?>