<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['nombre_servicio'])) {
        die("Error: El nombre del servicio es obligatorio.");
    }

    $nombre_servicio = trim($_POST['nombre_servicio']);

    $stmt = $conn->prepare("INSERT INTO service (name) VALUES (?)");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }

    $stmt->bind_param("s", $nombre_servicio);

    if ($stmt->execute()) {
        // Redirige o muestra mensaje de éxito
        header("Location: Asignacion.php"); // O donde tengas la lista de servicios
        exit;
    } else {
        echo "Error al agregar servicio: " . $stmt->error;
    }
} else {
    echo "Acceso no permitido.";
}
?>
