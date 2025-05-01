<?php
$conn = new mysqli("localhost", "root", "", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$service_id = $_GET['service_id'];

$delete = "UPDATE service SET user_id = NULL WHERE service_id = $service_id";
if ($conn->query($delete)) {
    header("Location: asignacion.php");
    exit;
} else {
    echo "Error al eliminar la asignación: " . $conn->error;
}
$conn->close();
