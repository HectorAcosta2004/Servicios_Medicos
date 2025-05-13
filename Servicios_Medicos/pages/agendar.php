<?php
session_start();

// Verificar si el usuario está logueado y si es un 'pacient'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pacient') {
  header("Location: index.php");
  exit();
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "No se ha encontrado el ID del usuario en la sesión.";
    exit;
}

require_once 'database.php';

// Obtener la instancia de la conexión utilizando el patrón Singleton
$db = Database::getInstance();
$conn = $db->getConnection();


// ----- CLASE REAL: Servicio que ejecuta la lógica de agendar -----
class AppointmentService {
    public function agendar($conn, $user_id, $service_id) {
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, service_id) VALUES (?, ?)");
        if (!$stmt) return "Error preparando cita.";

        $stmt->bind_param("ii", $user_id, $service_id);
        if (!$stmt->execute()) return "Error al agendar cita.";
        $stmt->close();

        // Obtener hora inicio y fin del servicio
        $stmt = $conn->prepare("SELECT time_consult_start, time_consult_finish FROM service WHERE service_id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $stmt->bind_result($start, $finish);

        if ($stmt->fetch()) {
            $stmt->close();
            $agenda_stmt = $conn->prepare("INSERT INTO agenda (service_id) VALUES (?)");
            $agenda_stmt->bind_param("i", $service_id);
            $agenda_stmt->execute();
            $agenda_stmt->close();
            return "¡Cita y agenda registradas exitosamente!";
        } else {
            return "No se encontró horario del servicio.";
        }
    }
}

// ----- PROXY: controla el acceso a AppointmentService -----
class AppointmentProxy {
    private $realService;

    public function __construct() {
        $this->realService = new AppointmentService();
    }

    public function agendar($conn, $user_id, $service_id) {
        if (!$user_id || !$service_id) {
            return "Datos incompletos para agendar.";
        }

        // Validación opcional: evitar citas duplicadas
        $check = $conn->prepare("SELECT cita_id FROM appointments WHERE user_id = ? AND service_id = ?");
        $check->bind_param("ii", $user_id, $service_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            return "Ya tienes una cita con este servicio.";
        }

        return $this->realService->agendar($conn, $user_id, $service_id);
    }
}

// ----- PROCESO DE AGENDA -----
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $proxy = new AppointmentProxy();
    $mensaje = $proxy->agendar($conn, $user_id, $service_id);
    echo "<script>alert('$mensaje');</script>";
}


// Traer servicios disponibles
$sql_services = "SELECT s.service_id, s.name AS service_name, CONCAT(u.name, ' ', u.last_name) AS doctor_name, s.time_consult_start, s.time_consult_finish
                 FROM service s
                 JOIN user u ON s.user_id = u.user_id";
$result_services = $conn->query($sql_services);

if (!$result_services) {
    echo "Error cargando servicios: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Agendar Cita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>

    <?php include 'Navbar.php'; ?>
    <?php $current_page = 'agendar'; ?>
    <?php include 'sidenav_patient.php'; ?>

    <main class="main-content position-relative border-radius-lg ">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-xl-6 col-sm-6 mb-xl-0 mb-4">
                    <nav aria-label="breadcrumb">
                        <h2 class="font-weight-bolder text-white mb-0">Agendar citas</h2>
                    </nav>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Servicios disponibles</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <form method="POST">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Doctor</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result_services->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                                                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                                                    <td><?= htmlspecialchars($row['time_consult_start']) ?></td>
                                                    <td><?= htmlspecialchars($row['time_consult_finish']) ?></td>
                                                    <td>
                                                        <button type="submit" name="service_id" value="<?= $row['service_id'] ?>" class="btn btn-primary btn-sm">
                                                            Agendar
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts necesarios -->
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/chartjs.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = { damping: '0.5' }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>

<?php $conn->close(); ?>
