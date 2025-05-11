<?php
session_start(); // Iniciar sesión
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Conexión a la base de datos
$host = 'Localhost';
$db = 'Servicios_Medicos';
$db_user = 'root';
$pass = '1234';

$mysqli = new mysqli($host, $db_user, $pass, $db);
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Recibir datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Consultar si el usuario existe
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si el usuario existe, obtener la fila
    $userData = $result->fetch_assoc();

    // Verificar la contraseña
    if ($password === $userData['password']) {
        // Contraseña correcta, iniciar sesión
        $_SESSION['user_id'] = $userData['user_id'];  // Cambiar 'user' a 'user_id'
        $_SESSION['name'] = $userData['name'];
        $_SESSION['role'] = $userData['rol'];

        // Redirigir al dashboard según el rol
        if ($userData['rol'] == 'admin') {
            header("Location: dashboard_admin.php");
        } elseif ($userData['rol'] == 'pacient') {
            header("Location: dashboard_patient.php");
        } elseif ($userData['rol'] == 'professional') {
            header("Location: dashboard_medico.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        // Contraseña incorrecta
        echo "Contraseña incorrecta.";
    }
} else {
    // Usuario no encontrado
    echo "El usuario no existe.";
}

$mysqli->close();
?>
