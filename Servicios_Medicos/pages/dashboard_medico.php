<?php

session_start(); // Iniciar sesión
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado y si es un 'professional'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professional') {
    header("Location: index.php");
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '1234', 'Servicios_Medicos');
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Consulta SQL
$sql = "
  SELECT 
    DATE(a.time_consult_start) AS fecha, 
    a.time_consult_start, 
    a.time_consult_finish,  -- Asegúrate de que esta línea esté incluida
    s.name AS servicio,
    CONCAT(u1.name, ' ', u1.last_name) AS paciente, 
    CONCAT(u2.name, ' ', u2.last_name) AS doctor
  FROM agenda a
  JOIN service s ON a.service_id = s.service_id
  JOIN appointments ap ON ap.service_id = s.service_id
  JOIN user u1 ON ap.user_id = u1.user_id AND u1.rol = 'pacient'
  JOIN user u2 ON s.user_id = u2.user_id AND u2.rol = 'professional'
  WHERE u2.user_id = ?  -- Agregar la condición para que solo vea las citas del médico actual
  ORDER BY a.time_consult_start
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);  // Usar el ID del usuario de la sesión
$stmt->execute();
$result = $stmt->get_result();
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
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <h2 class="font-weight-bolder text-white mb-0">Mis citas</h2>
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
                  <th>Hora de Inicio</th>
                  <th>Hora de Finalización</th>
                  <th>Paciente</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    // Verificar si las horas de inicio y finalización están vacías o nulas y asignar un valor predeterminado
                    $time_start = !empty($row['time_consult_start']) ? htmlspecialchars($row['time_consult_start']) : 'No disponible';
                    $time_finish = !empty($row['time_consult_finish']) ? htmlspecialchars($row['time_consult_finish']) : 'No disponible';
                    $paciente = htmlspecialchars($row['paciente']);

                    // Imprimir las filas de la tabla
                    echo "<tr>";
                    echo "<td>" . $time_start . "</td>";
                    echo "<td>" . $time_finish . "</td>";
                    echo "<td>" . $paciente . "</td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='3'>No se encontraron citas.</td></tr>";
                }
                ?>
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
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>
<?php $mysqli->close(); ?>
