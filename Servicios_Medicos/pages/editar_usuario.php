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
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 400px;
    }
    h2 {
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }
    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
      color: #555;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }
    button, .cancel-btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
    button {
      background-color: #007bff;
      color: white;
    }
    .cancel-btn {
      background-color: #dc3545;
      color: white;
      text-decoration: none;
      margin-left: 10px;
    }
    button:hover {
      background-color: #0056b3;
    }
    .cancel-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Editar Usuario</h2>
    <form method="POST">
      <label for="name">Nombre:</label>
      <input type="text" name="name" value="<?= htmlspecialchars($usuario['name']) ?>" required>

      <label for="last_name">Apellido:</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($usuario['last_name']) ?>" required>

      <label for="rol">Rol:</label>
      <select name="rol" required>
        <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        <option value="professional" <?= $usuario['rol'] === 'professional' ? 'selected' : '' ?>>Profesional</option>
        <option value="patient" <?= $usuario['rol'] === 'patient' ? 'selected' : '' ?>>Paciente</option>
      </select>

      <button type="submit">Guardar Cambios</button>
      <a href="administracion_usuarios.php" class="cancel-btn">Cancelar</a>
    </form>
  </div>
</body>
</html>
