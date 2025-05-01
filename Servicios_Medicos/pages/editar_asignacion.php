<?php
$conn = new mysqli("localhost", "root", "", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$service_id = $_GET['service_id'];

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_user_id = $_POST['user_id'];
    $update = "UPDATE service SET user_id = $nuevo_user_id WHERE service_id = $service_id";
    if ($conn->query($update)) {
        header("Location: Asignacion.php");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

// Obtener datos del servicio actual
$servicio = $conn->query("SELECT name, user_id FROM service WHERE service_id = $service_id")->fetch_assoc();

// Obtener lista de doctores
$doctores = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS nombre FROM user WHERE rol = 'professional'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Asignación</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .container {
      background-color: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      width: 400px;
      text-align: center;
    }

    h2 {
      color: #333;
      margin-bottom: 25px;
    }

    label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-weight: bold;
      color: #555;
    }

    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 20px;
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
      background-color: #28a745;
      color: white;
      margin-right: 10px;
    }

    .cancel-btn {
      background-color: #dc3545;
      color: white;
      text-decoration: none;
      display: inline-block;
    }

    button:hover {
      background-color: #218838;
    }

    .cancel-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Editar Asignación para: <?= htmlspecialchars($servicio['name']) ?></h2>

    <form method="POST">
      <label for="user_id">Seleccionar Doctor:</label>
      <select name="user_id" required>
        <?php while ($doc = $doctores->fetch_assoc()): ?>
          <option value="<?= $doc['user_id'] ?>" <?= $servicio['user_id'] == $doc['user_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($doc['nombre']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <button type="submit">Guardar Cambios</button>
      <a href="Asignacion.php" class="cancel-btn">Cancelar</a>
    </form>
  </div>

</body>
</html>

