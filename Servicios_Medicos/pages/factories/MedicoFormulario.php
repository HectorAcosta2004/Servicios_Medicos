<?php
require_once 'FormularioPerfil.php';

class MedicoFormulario implements FormularioPerfil {
    public function render(): string {
        return '
        <form method="POST">
            <h5>Formulario Médico</h5>
            <input name="nombre" placeholder="Nombre"><br>
            <input name="apellido" placeholder="Apellido"><br>
            <input name="especialidad" placeholder="Especialidad"><br>
            <button type="submit">Guardar</button>
        </form>';
    }
}
?>