<?php
session_start();

// Verificar si el usuario está logueado y si es un 'professional'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professional') {
  header("Location: index.php");
  exit();
}

// Incluir la clase Database
require_once 'database.php'; // Ajusta la ruta según corresponda
require_once 'includes/FlashMessage.php';

// Obtener la instancia de la base de datos utilizando el Singleton
$db = Database::getInstance();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("No has iniciado sesión.");
}

// Data Access Object (DAO) para los servicios
class ServiceDAO {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getServicesByUser($user_id) {
        // Aseguramos que solo se recuperen los servicios del usuario actual
        $stmt = $this->conn->prepare("SELECT * FROM service WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateServiceTime($service_id, $start, $finish, $user_id) {
        // Solo se actualiza si el servicio pertenece al usuario logueado
        $stmt = $this->conn->prepare("UPDATE service SET time_consult_start=?, time_consult_finish=? WHERE service_id=? AND user_id=?");
        $stmt->bind_param("ssii", $start, $finish, $service_id, $user_id);
        return $stmt->execute();
    }
}

// Crear una instancia del DAO
$serviceDAO = new ServiceDAO($conn);

$flashMessage = FlashMessage::getInstance();

// Editar horario existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $service_id = $_POST['service_id'];
    $start = $_POST['start'];
    $finish = $_POST['finish'];

    if ($serviceDAO->updateServiceTime($service_id, $start, $finish, $user_id)) {
        // Agregar mensaje de éxito
        $flashMessage->addMessage('Horario actualizado correctamente.', 'success');
    } else {
        // Agregar mensaje de error
        $flashMessage->addMessage('No se pudo actualizar el horario. Verifica los datos.', 'danger');
    }
}

// Obtener servicios del médico actual
$result = $serviceDAO->getServicesByUser($user_id);
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <title>Mis Horarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include 'Navbar.php'; ?>
  <?php $current_page = 'agendarm'; ?>
  <?php include 'sidenav_medico.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row mb-4">
        <div class="col-md-6">
          <h2 class="font-weight-bolder text-white mb-0">Mis Horarios</h2>
        </div>
      </div>
      <?php
        // Mostrar mensajes flash si existen
        $flashMessage->displayMessages();
      ?>
      <div class="card my-4 shadow-sm">
        <div class="card-header pb-0">
          <h6 class="mb-0">Crear horarios</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-4">
            <?php if ($result->num_rows > 0): ?>
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th>Servicio</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th></th>
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
