<?php
session_start();
require_once '../command/command.php';  // Ajusta la ruta si es necesario
require_once '../database.php';        // Ajusta la ruta si es necesario

// Verifica que haya un parámetro GET válido
if (isset($_GET['cita_id'])) {
    $cita_id = intval($_GET['cita_id']); // Usamos GET, no POST
    $database = Database::getInstance();
    $conn = $database->getConnection();

    $citaManager = new CitaManager($conn);
    $comando = new CancelarCitaCommand($citaManager, $cita_id);

    if ($comando->ejecutar()) {
        $_SESSION['flash_success'] = "Cita cancelada con éxito.";
    } else {
        $_SESSION['flash_error'] = "Error al cancelar la cita.";
    }

    $conn->close();
    header("Location: ../dashboard_patient.php");
    exit();
} else {
    $_SESSION['flash_error'] = "ID de cita no proporcionado.";
    header("Location: pages/dashboard_patient.php");
    exit();
}
?>
