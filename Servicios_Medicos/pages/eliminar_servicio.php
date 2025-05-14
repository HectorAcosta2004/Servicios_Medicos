<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$service_id = $_GET['service_id'] ?? null;

if ($service_id) {
    // Prepara la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("DELETE FROM service WHERE service_id = ?");
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        header("Location: Asignacio.php"); // redirige a donde quieras
        exit;
    } else {
        echo "Error al eliminar el servicio: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID de servicio no proporcionado.";
}

$conn->close();
?>
