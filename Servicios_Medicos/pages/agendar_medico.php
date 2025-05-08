<?php
session_start();
$conn = new mysqli('localhost', 'root', '1234', 'Servicios_Medicos');

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("No has iniciado sesión.");
}

// Crear nuevo horario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $name = $_POST['name'];
    $start = $_POST['start'];
    $finish = $_POST['finish'];

    $stmt = $conn->prepare("INSERT INTO service (name, time_consult_start, time_consult_finish, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $start, $finish, $user_id);
    $stmt->execute();
}

// Editar horario existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $service_id = $_POST['service_id'];
    $name = $_POST['name'];
    $start = $_POST['start'];
    $finish = $_POST['finish'];

    $stmt = $conn->prepare("UPDATE service SET name=?, time_consult_start=?, time_consult_finish=? WHERE service_id=? AND user_id=?");
    $stmt->bind_param("sssii", $name, $start, $finish, $service_id, $user_id);
    $stmt->execute();
}

// Obtener servicios del médico actual
$query = "SELECT * FROM service WHERE user_id = $user_id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gestionar Horarios</title>
  <link rel="stylesheet" href="../assets/css/argon-dashboard.css?v=2.1.0">
</head>
<body>
  <?php include 'Navbar.php'; ?>
  <?php include 'sidenav_medico.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <h2 class="text-white">Mis horarios</h2>

      <div class="card mt-4">
        <div class="card-header">
          <h6>Crear nuevo horario</h6>
        </div>
        <div class="card-body">
          <form method="POST">
            <input type="text" name="name" class="form-control mb-2" placeholder="Nombre del servicio" required>
            <input type="datetime-local" name="start" class="form-control mb-2" required>
            <input type="datetime-local" name="finish" class="form-control mb-2" required>
            <button class="btn btn-success" type="submit" name="crear">Crear</button>
          </form>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-header">
          <h6>Horarios existentes</h6>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>Servicio</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Editar</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <form method="POST">
                  <td>
                    <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control">
                  </td>
                  <td>
                    <input type="datetime-local" name="start" value="<?= date('Y-m-d\TH:i', strtotime($row['time_consult_start'])) ?>" class="form-control">
                  </td>
                  <td>
                    <input type="datetime-local" name="finish" value="<?= date('Y-m-d\TH:i', strtotime($row['time_consult_finish'])) ?>" class="form-control">
                  </td>
                  <td>
                    <input type="hidden" name="service_id" value="<?= $row['service_id'] ?>">
                    <button class="btn btn-primary btn-sm" type="submit" name="editar">Guardar</button>
                  </td>
                </form>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

<?php $conn->close(); ?>
