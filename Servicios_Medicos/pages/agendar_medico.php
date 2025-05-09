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

// Editar horario existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $service_id = $_POST['service_id'];
    $start = $_POST['start'];
    $finish = $_POST['finish'];

    $stmt = $conn->prepare("UPDATE service SET time_consult_start=?, time_consult_finish=? WHERE service_id=? AND user_id=?");
    $stmt->bind_param("ssii", $start, $finish, $service_id, $user_id);
    $stmt->execute();
}

// Obtener servicios del médico actual
$stmt = $conn->prepare("SELECT * FROM service WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Horarios</title>
  <link rel="stylesheet" href="../assets/css/argon-dashboard.css?v=2.1.0">
</head>
<body>
  <?php include 'Navbar.php'; ?>
  <?php include 'sidenav_medico.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <h2 class="text-white">Mis Horarios</h2>

      <div class="card mt-4">
        <div class="card-header">
          <h6>Modificar horarios asignados</h6>
        </div>
        <div class="card-body">
          <?php if ($result->num_rows > 0): ?>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Servicio</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <form method="POST">
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td>
                    <input type="datetime-local" name="start" value="<?= date('Y-m-d\TH:i', strtotime($row['time_consult_start'])) ?>" class="form-control" required>
                  </td>
                  <td>
                    <input type="datetime-local" name="finish" value="<?= date('Y-m-d\TH:i', strtotime($row['time_consult_finish'])) ?>" class="form-control" required>
                  </td>
                  <td>
                    <input type="hidden" name="service_id" value="<?= $row['service_id'] ?>">
                    <button type="submit" name="editar" class="btn btn-primary btn-sm">Guardar</button>
                  </td>
                </form>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
          <?php else: ?>
            <p>No tienes servicios asignados actualmente.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </main>
</body>
</html>

<?php $conn->close(); ?>
