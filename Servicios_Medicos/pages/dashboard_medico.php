<?php
session_start();

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '1234', 'Servicios_Medicos');
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL
$query = "
SELECT
    app.cita_id, 
    s.name AS service_name, 
    a.date AS appointment_date, 
    app.service_id,
    u.name AS user_name,
    u.last_name AS user_last_name,
    s.time_consult_start,
    s.time_consult_finish
FROM appointments app
JOIN service s ON app.service_id = s.service_id
JOIN agenda a ON app.service_id = a.service_id
JOIN user u ON app.user_id = u.user_id
WHERE u.rol = 'pacient'
ORDER BY a.date
";

$result = $conn->query($query);
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
                    echo "<tr>";
                    echo "<td>" . $row['time_consult_start'] . "</td>";
                    echo "<td>" . $row['time_consult_finish'] . "</td>";
                    echo "<td>" . $row['user_name'] . " " . $row['user_last_name'] . "</td>";
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
<?php $conn->close(); ?>
