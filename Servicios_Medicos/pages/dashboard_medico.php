<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professional') {
    header("Location: index.php");
    exit();
}

interface Cita {
    public function mostrarCita();
}

class CitaIndividual implements Cita {
    private $hora_inicio;
    private $hora_finalizacion;
    private $paciente;

    public function __construct($hora_inicio, $hora_finalizacion, $paciente) {
        $this->hora_inicio = $hora_inicio;
        $this->hora_finalizacion = $hora_finalizacion;
        $this->paciente = $paciente;
    }

    public function mostrarCita() {
        echo "Hora de Inicio: $this->hora_inicio | Hora de Finalización: $this->hora_finalizacion | Paciente: $this->paciente<br>";
    }
}
require_once 'database.php';

// Obtener la instancia de la base de datos usando el Singleton
$db = Database::getInstance();
$mysqli = $db->getConnection();

$fecha_filtrada = $_GET['fecha'] ?? null;

$sql = "
  SELECT 
    DATE(a.date) AS fecha, 
    a.date AS time_consult_start, 
    a.date AS time_consult_finish, 
    CONCAT(u1.name, ' ', u1.last_name) AS paciente
  FROM agenda a
  JOIN service s ON a.service_id = s.service_id
  JOIN appointments ap ON ap.service_id = s.service_id
  JOIN user u1 ON ap.user_id = u1.user_id AND u1.rol = 'pacient'
  JOIN user u2 ON s.user_id = u2.user_id AND u2.rol = 'professional'
  WHERE u2.user_id = ? 
";

if ($fecha_filtrada) {
    $sql .= " AND DATE(a.date) = ?";  // Filtrar por fecha si está presente
}

$sql .= " ORDER BY a.date";  // Ordenar las citas por fecha

$stmt = $mysqli->prepare($sql);
if ($fecha_filtrada) {
    $stmt->bind_param("is", $_SESSION['user_id'], $fecha_filtrada);
} else {
    $stmt->bind_param("i", $_SESSION['user_id']);
}
$stmt->execute();
$result = $stmt->get_result();

$datosCitas = [];

while ($row = $result->fetch_assoc()) {
    $hora_inicio = !empty($row['time_consult_start']) ? $row['time_consult_start'] : 'No disponible';
    $hora_finalizacion = !empty($row['time_consult_finish']) ? $row['time_consult_finish'] : 'No disponible';
    $paciente = htmlspecialchars($row['paciente']);

    $datosCitas[] = [
        'hora_inicio' => $hora_inicio,
        'hora_finalizacion' => $hora_finalizacion,
        'paciente' => $paciente
    ];
}
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
  <?php $current_page = 'dashboardm'; ?>
  <?php include 'sidenav_medico.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row mb-4">
        <div class="col-md-6">
          <h2 class="font-weight-bolder text-white mb-0">Mis citas</h2>
        </div>
        <div class="col-md-6 text-end">
          <form method="GET" class="d-flex justify-content-end align-items-center">
            <label for="fecha" class="me-2 text-white">Filtrar por fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control w-auto" value="<?= htmlspecialchars($fecha_filtrada) ?>">
            <button type="submit" class="btn btn-primary ms-2">Filtrar</button>
          </form>
        </div>
      </div>

      <div class="card my-4 shadow-sm">
        <div class="card-header pb-0">
          <h6 class="mb-0">Citas Programadas <?= $fecha_filtrada ? 'para el ' . $fecha_filtrada : '' ?></h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-4">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th>Hora de Inicio</th>
                  <th>Hora de Finalización</th>
                  <th>Paciente</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($datosCitas) > 0): ?>
                  <?php foreach ($datosCitas as $cita): ?>
                    <tr>
                      <td><?= $cita['hora_inicio'] ?></td>
                      <td><?= $cita['hora_finalizacion'] ?></td>
                      <td><?= $cita['paciente'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="3">No se encontraron citas.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>

<?php $mysqli->close(); ?>
