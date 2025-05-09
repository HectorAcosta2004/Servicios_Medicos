<?php
$conn = new mysqli("localhost", "root", "1234", "Servicios_medicos");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellido = $conn->real_escape_string($_POST['apellido']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    $check = $conn->query("SELECT * FROM user WHERE username = '$correo'");
    if ($check->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado'); window.history.back();</script>";
        exit;
    }

    $sql = "INSERT INTO user (name, last_name, username, password) 
            VALUES ('$nombre', '$apellido', '$correo', '$contrasena')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Usuario creado exitosamente'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
