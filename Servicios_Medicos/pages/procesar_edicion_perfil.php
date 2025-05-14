<?php
session_start();
require_once 'database.php';

// Verificar que el usuario esté logueado y sea un paciente
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pacient') {
    header("Location: index.php");
    exit();
}

// Obtener el user_id del paciente
$user_id = $_SESSION['user_id'];

// Verificar que el formulario haya sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validación simple para asegurarse de que no están vacíos
    if (empty($nombre) || empty($apellido) || empty($correo)) {
        echo "Por favor complete todos los campos obligatorios.";
        exit();
    }

    // Si la contraseña se ha proporcionado, encriptarla
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    }

    // Obtener la conexión a la base de datos
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // Actualizar los datos del paciente en la base de datos
    $sql = "UPDATE user SET name = ?, last_name = ?, username = ?";

    // Si la contraseña ha sido proporcionada, agregarla a la actualización
    if (!empty($password)) {
        $sql .= ", password = ?";
    }

    $sql .= " WHERE user_id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Bind de los parámetros
    if (!empty($password)) {
        $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $password_hashed, $user_id);
    } else {
        $stmt->bind_param("sssi", $nombre, $apellido, $correo, $user_id);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si la actualización fue exitosa
        echo "<script>alert('Perfil actualizado correctamente');</script>";
        echo "<script>window.location.href = 'patient_medico.php';</script>";
    } else {
        // Si ocurrió un error al actualizar
        echo "Error al actualizar el perfil: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
