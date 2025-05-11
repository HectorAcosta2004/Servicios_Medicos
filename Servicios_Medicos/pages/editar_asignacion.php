<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar existencia del parámetro
if (!isset($_GET['service_id'])) {
    die("Error: No se proporcionó el ID del servicio.");
}

$service_id = intval($_GET['service_id']);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_user_id = intval($_POST['user_id']);  // Asegurarse de que el ID sea un entero

    // Consulta de actualización
    $update = "UPDATE service 
               SET user_id = $nuevo_user_id 
               WHERE service_id = $service_id";

    if ($conn->query($update)) {
        header("Location: Asignacion.php");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

// Obtener los datos actuales del servicio
$result = $conn->query("SELECT name, user_id, time_consult_start, time_consult_finish 
                        FROM service WHERE service_id = $service_id");
if (!$result || $result->num_rows === 0) {
    die("Servicio no encontrado.");
}

$servicio = $result->fetch_assoc();

// Obtener lista de doctores disponibles
$doctores = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS nombre 
                          FROM user WHERE rol = 'professional'");
?>
