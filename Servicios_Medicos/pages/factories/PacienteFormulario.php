<?php
require_once 'FormularioPerfil.php';

class PacienteFormulario implements FormularioPerfil {
    public function render(): string {
        return '
        <form method="POST">
            <h5>Formulario Paciente</h5>
            <input name="nombre" placeholder="Nombre"><br>
            <input name="apellido" placeholder="Apellido"><br>
            <input name="correo" placeholder="Correo"><br>
            <button type="submit">Guardar</button>
        </form>';
    }
}
?>