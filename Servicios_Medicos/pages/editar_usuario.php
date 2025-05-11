<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar existencia del parámetro
if (!isset($_GET['user_id'])) {
    die("Error: No se proporcionó el ID del usuario.");
}

$user_id = intval($_GET['user_id']);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_name = $conn->real_escape_string($_POST['name']);
    $nuevo_last_name = $conn->real_escape_string($_POST['last_name']);
    $nuevo_rol = $conn->real_escape_string($_POST['rol']);

    $update = "UPDATE user 
               SET name = '$nuevo_name', 
                   last_name = '$nuevo_last_name', 
                   rol = '$nuevo_rol' 
               WHERE user_id = $user_id";

    if ($conn->query($update)) {
        header("Location: administracion_usuarios.php");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

// Obtener los datos actuales del usuario
$result = $conn->query("SELECT name, last_name, rol FROM user WHERE user_id = $user_id");
if (!$result || $result->num_rows === 0) {
    die("Usuario no encontrado.");
}

$usuario = $result->fetch_assoc();
?>