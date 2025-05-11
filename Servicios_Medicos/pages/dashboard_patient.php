<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pacient') {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  echo "No se ha encontrado el ID del usuario en la sesión.";
  exit();
}

$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// --- PATRÓN STRATEGY ---

interface CitaEstrategia {
  public function obtenerCitas($conn, $user_id);
}

class CitasPorDefecto implements CitaEstrategia {
  public function obtenerCitas($conn, $user_id) {
    $sql = "SELECT s.name AS service_name, 
                   CONCAT(u.name, ' ', u.last_name) AS doctor_name,
                   s.time_consult_start, s.time_consult_finish
            FROM appointments a
            JOIN service s ON a.service_id = s.service_id
            JOIN user u ON s.user_id = u.user_id
            WHERE a.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
  }
}

class CitasPorDoctor implements CitaEstrategia {
  public function obtenerCitas($conn, $user_id) {
    $sql = "SELECT s.name AS service_name, 
                   CONCAT(u.name, ' ', u.last_name) AS doctor_name,
                   s.time_consult_start, s.time_consult_finish
            FROM appointments a
            JOIN service s ON a.service_id = s.service_id
            JOIN user u ON s.user_id = u.user_id
            WHERE a.user_id = ?
            ORDER BY doctor_name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
  }
}

class CitasPorFecha implements CitaEstrategia {
  public function obtenerCitas($conn, $user_id) {
    $sql = "SELECT s.name AS service_name, 
                   CONCAT(u.name, ' ', u.last_name) AS doctor_name,
                   s.time_consult_start, s.time_consult_finish
            FROM appointments a
            JOIN service s ON a.service_id = s.service_id
            JOIN user u ON s.user_id = u.user_id
            WHERE a.user_id = ?
            ORDER BY s.time_consult_start";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
  }
}

// CONTEXTO
class ContextoCita {
  private $estrategia;

  public function __construct(CitaEstrategia $estrategia) {
    $this->estrategia = $estrategia;
  }

  public function setEstrategia(CitaEstrategia $estrategia) {
    $this->estrategia = $estrategia;
  }

  public function obtenerCitas($conn, $user_id) {
    return $this->estrategia->obtenerCitas($conn, $user_id);
  }
}

// Obtener filtro
$filtro = $_GET['filtro'] ?? 'defecto';

switch ($filtro) {
  case 'doctor':
    $estrategia = new CitasPorDoctor();
    break;
  case 'fecha':
    $estrategia = new CitasPorFecha();
    break;
  default:
    $estrategia = new CitasPorDefecto();
    break;
}

$contexto = new ContextoCita($estrategia);
$result = $contexto->obtenerCitas($conn, $user_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>Mis Citas</title>
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
  <?php $current_page = 'dashboardp'; ?>
  <?php include 'sidenav_patient.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-6">
          <h2 class="font-weight-bolder text-white mb-0">Mis citas</h2>
        </div>
        <div class="col-md-6 text-end">
          <form method="GET" class="d-flex justify-content-end align-items-center">
            <label for="filtro" class="text-white me-2">Ordenar por:</label>
            <select name="filtro" id="filtro" class="form-control w-auto">
              <option value="defecto" <?= $filtro === 'defecto' ? 'selected' : '' ?>>Por defecto</option>
              <option value="doctor" <?= $filtro === 'doctor' ? 'selected' : '' ?>>Doctor</option>
              <option value="fecha" <?= $filtro === 'fecha' ? 'selected' : '' ?>>Fecha</option>
            </select>
            <button type="submit" class="btn btn-primary ms-2">Aplicar</button>
          </form>
        </div>
      </div>

      <div class="card my-4 shadow-sm">
        <div class="card-header pb-0">
          <h6 class="mb-0">Citas Programadas</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-4">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th>Servicio</th>
                  <th>Doctor Asignado</th>
                  <th>Hora Inicio</th>
                  <th>Hora Fin</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['service_name']) . "</td>
                            <td>" . htmlspecialchars($row['doctor_name']) . "</td>
                            <td>" . htmlspecialchars($row['time_consult_start']) . "</td>
                            <td>" . htmlspecialchars($row['time_consult_finish']) . "</td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='4'>No se encontraron citas.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
<?php $conn->close(); ?>
