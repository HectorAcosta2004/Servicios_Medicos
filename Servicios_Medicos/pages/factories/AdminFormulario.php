<?php
require_once 'FormularioPerfil.php';

class AdminFormulario implements FormularioPerfil {
    public function render(): string {
        return '

        <form method="POST">
            <input name="nombre" placeholder="Nombre">
            <input name="apellido" placeholder="Apellido">
            <input name="correo" placeholder="Correo">
            <input name="password" placeholder="Contraseña">
            <button type="submit">Guardar</button>
            <button type="submit">Cancelar</button>
        </form>';
    }
}
?>