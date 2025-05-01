<?php
session_start(); // Iniciar sesión

// Conexión a la base de datos
$host = 'localhost';
$db = 'Servicios_Medicos';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);
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
    $user = $result->fetch_assoc();

    // Verificar la contraseña
    if ($password === $user['password']) {
        // Contraseña correcta, iniciar sesión
        $_SESSION['user'] = $user['id']; // Asumiendo que tienes un campo 'id' en la tabla
        $_SESSION['username'] = $user['username'];

        // Redirigir al dashboard
        header("Location: dashboard.php");
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
