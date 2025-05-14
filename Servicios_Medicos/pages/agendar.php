<?php
session_start();

// Verificar si el usuario está logueado y si es un 'pacient'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pacient') {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Traer servicios disponibles con toda la información necesaria
$sql_services = "
SELECT 
    s.service_id,
    s.name AS service_name,
    s.time_consult_start,
    s.time_consult_finish,
    s.estatos AS service_status,  -- Aquí se usa 'estatos' en lugar de 'status'
    u.name AS doctor_name
FROM service s
JOIN user u ON s.user_id = u.user_id
";

$result_services = $conn->query($sql_services);

if (!$result_services) {
    echo "Error cargando servicios: " . $conn->error;
    exit;
}

// Insertar cita y agenda
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    // Verificar si el servicio está disponible
    $status_check_stmt = $conn->prepare("SELECT estatos FROM service WHERE service_id = ?");
    if ($status_check_stmt) {
        $status_check_stmt->bind_param("i", $service_id);
        $status_check_stmt->execute();
        $status_check_stmt->bind_result($status);
        $status_check_stmt->fetch();
        $status_check_stmt->close();

        // Si el servicio está disponible, agendar la cita
        if ($status === 'disponible') {
            $stmt = $conn->prepare("INSERT INTO appointments (user_id, service_id) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ii", $user_id, $service_id);
                if ($stmt->execute()) {

                    // Obtener hora inicio, fin y doctor del servicio
                    $service_stmt = $conn->prepare("SELECT time_consult_start, time_consult_finish, user_id FROM service WHERE service_id = ?");
                    if ($service_stmt) {
                        $service_stmt->bind_param("i", $service_id);
                        $service_stmt->execute();
                        $service_stmt->bind_result($start_time, $finish_time, $doctor_id);

                        if ($service_stmt->fetch()) {
                            $service_stmt->close();

                            // Insertar en agenda
                            $agenda_stmt = $conn->prepare("INSERT INTO agenda (time_consult_start, time_consult_finish, service_id, user_id) VALUES (?, ?, ?, ?)");
                            if ($agenda_stmt) {
                                $agenda_stmt->bind_param("ssii", $start_time, $finish_time, $service_id, $user_id);
                                $agenda_stmt->execute();
                                $agenda_stmt->close();
                                // Cambiar el estatus del servicio a 'no disponible'
                                $update_status_stmt = $conn->prepare("UPDATE service SET estatos = 'no disponible' WHERE service_id = ?");
                                if ($update_status_stmt) {
                                    $update_status_stmt->bind_param("i", $service_id);
                                    $update_status_stmt->execute();
                                    $update_status_stmt->close();
                                } else {
                                    echo "<script>alert('Error al actualizar el estado del servicio.');</script>";
                                }

                            } else {
                                echo "<script>alert('Error al preparar el registro en la agenda.');</script>";
                            }
                        } else {
                            echo "<script>alert('No se encontró el horario del servicio.');</script>";
                            $service_stmt->close();
                        }
                    }
                } else {
                    echo "<script>alert('Error al agendar la cita.');</script>";
                }
                $stmt->close();
            } else {
                echo "Error preparando consulta: " . $conn->error;
            }
        } else {
            echo "<script>alert('La cita no está disponible, por favor elija otro servicio.');</script>";
        }
    }
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
                                                <th>Estado</th>
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
                                                    <td><?= htmlspecialchars($row['service_status']) ?></td>
                                                    <td>
                                                        <?php if ($row['service_status'] === 'disponible'): ?>
                                                            <button type="submit" name="service_id"
                                                                value="<?= $row['service_id'] ?>"
                                                                class="btn btn-primary btn-sm">
                                                                Agendar
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-danger btn-sm" disabled>
                                                                no disponible
                                                            </button>
                                                        <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>

<?php
$conn->close();
?>