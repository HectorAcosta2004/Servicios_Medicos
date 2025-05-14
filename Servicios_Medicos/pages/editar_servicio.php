<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que venga el ID y el nombre del servicio
    if (!isset($_POST['service_id']) || !isset($_POST['nombre_servicio'])) {
        die("Error: Datos incompletos.");
    }

    $service_id = intval($_POST['service_id']);
    $nombre_servicio = trim($_POST['nombre_servicio']);

    // Preparar consulta segura con prepared statements
    $stmt = $conn->prepare("UPDATE service SET name = ? WHERE service_id = ?");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }

    $stmt->bind_param("si", $nombre_servicio, $service_id);

    if ($stmt->execute()) {
        header("Location: Asignacion.php");
        exit;
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
} else {
    echo "Acceso no permitido.";
}
