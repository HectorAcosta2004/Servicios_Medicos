<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT s.service_id, s.name AS service_name, u.user_id, CONCAT(u.name, ' ', u.last_name) AS doctor_name, s.time_consult_start, s.time_consult_finish
        FROM service s 
        JOIN user u ON s.user_id = u.user_id 
        WHERE u.rol = 'professional'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Asignación de profesionales</title>
  <!-- Estilos y fuentes -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include 'Navbar.php';?>
  <?php $current_page = 'asignacion'; ?>
  <?php include 'sidenav_admin.php'; ?>
  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
        <nav aria-label="breadcrumb">
          <h2 class="font-weight-bolder text-white mb-0">Personal</h2>
        </nav>
          <div class="card mb-4">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>ASIGNACIÓN</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
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
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>
                            <td>{$row['service_name']}</td>
                            <td>{$row['doctor_name']}</td>
                            <td>{$row['time_consult_start']}</td>
                            <td>{$row['time_consult_finish']}</td>
                            <td class='text-center'>
                              <a href='editar_asignacion.php?service_id={$row['service_id']}' class='text-secondary font-weight-bold text-xs'>Editar</a>
                              |
                              <a href='eliminar_asignacion.php?service_id={$row['service_id']}' class='text-danger font-weight-bold text-xs' onclick=\"return confirm('¿Estás seguro de eliminar esta asignación?')\">Eliminar</a>
                            </td>
                          </tr>";
                    }

                    $conn->close();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer pt-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                ©
                <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
</body>

</html>
