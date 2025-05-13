<?php
require_once 'FormularioPerfil.php';
require_once 'AdminFormulario.php';

class AdminFactory {
    public function crearFormulario(): FormularioPerfil {
        return new AdminFormulario();
    }
}
?>