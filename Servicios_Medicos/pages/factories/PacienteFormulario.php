<?php
require_once 'FormularioPerfil.php';

class PacienteFormulario implements FormularioPerfil {
     private $usuario;

    public function __construct($user_id) {
        // Obtener conexión (usa tu Singleton si aplica)
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Consulta para obtener datos del usuario
        $stmt = $conn->prepare("SELECT name, last_name, username FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($name, $last_name, $email);
        $stmt->fetch();
        $stmt->close();

        // Guardamos en el objeto
        $this->usuario = [
            'nombre' => $name,
            'apellido' => $last_name,
            'correo' => $email
        ];
    }

    public function render(): string {
        $nombre = htmlspecialchars($this->usuario['nombre']);
        $apellido = htmlspecialchars($this->usuario['apellido']);
        $correo = htmlspecialchars($this->usuario['correo']);

        return '
        <form method="POST" action="pages/procesar_edicion_perfil.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="' . $nombre . '" placeholder="Escribe tu nombre">
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="' . $apellido . '" placeholder="Escribe tu apellido">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" value="' . $correo . '" placeholder="ejemplo@correo.com">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••">
            </div>
        </form>';
    }
}
?>