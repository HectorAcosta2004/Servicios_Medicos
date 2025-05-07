<?php
session_start();

$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SIN FILTRAR por usuario
$sql = "SELECT a.cita_id, s.name AS service_name, CONCAT(u.name, ' ', u.last_name) AS doctor_name, 
               s.time_consult_start, s.time_consult_finish
        FROM appointments a
        JOIN service s ON a.service_id = s.service_id
        JOIN user u ON s.user_id = u.user_id";

$result = $conn->query($sql);

if (!$result) {
    echo "Error en la consulta: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SERVICIOS MEDICOS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include 'Navbar.php';?>
  <?php $current_page = 'dashboardp'; ?>
  <?php include 'sidenav_patient.php';?>
  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <nav aria-label="breadcrumb">
            <h2 class="font-weight-bolder text-white mb-0">Todas las citas</h2>
          </nav>
        </div>
      </div>
    </div>

    <hr class="horizontal dark my-1">
    <div class="card-body pt-sm-3 pt-0 overflow-auto">
      <div>
        <h6 class="mb-0">Todas las Citas Programadas</h6>
      </div>
      <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Servicio</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Doctor Asignado</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hora de Inicio</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hora de Finalización</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['service_name']}</td>
                        <td>{$row['doctor_name']}</td>
                        <td>{$row['time_consult_start']}</td>
                        <td>{$row['time_consult_finish']}</td>
                      </tr>";
              }
            } else {
              echo "<tr><td colspan='4'>No se encontraron citas.</td></tr>";
            }

            $conn->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
</body>
</html>
            