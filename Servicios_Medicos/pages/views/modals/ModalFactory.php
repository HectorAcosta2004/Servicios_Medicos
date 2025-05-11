<?php
// views/modals/ModalFactory.php
class ModalFactory {
    // Método estático para renderizar los modales
    public static function render($modalName, $data = []) {
        $path = __DIR__ . "/$modalName.php"; // Ruta del modal

        if (file_exists($path)) {
            // Extraer datos para que se puedan usar como variables dentro del modal
            extract($data);
            include $path; // Incluir el archivo del modal
        } else {
            echo "<!-- Modal '$modalName' no encontrado -->";
        }
    }
}
