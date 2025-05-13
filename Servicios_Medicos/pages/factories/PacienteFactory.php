<?php
require_once 'FormularioPerfil.php';
require_once 'PacienteFormulario.php';

class PacienteFactory {
    public function crearFormulario(): FormularioPerfil {
        return new PacienteFormulario();
    }
}
?>