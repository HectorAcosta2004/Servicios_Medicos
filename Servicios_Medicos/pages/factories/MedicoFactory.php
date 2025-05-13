<?php
require_once 'FormularioPerfil.php';
require_once 'MedicoFormulario.php';

class MedicoFactory {
    public function crearFormulario(): FormularioPerfil {
        return new MedicoFormulario();
    }
}
?>