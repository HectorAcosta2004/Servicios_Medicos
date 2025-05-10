<?php
session_start();

// Verificar si el usuario está autenticado y tiene rol 'admin'
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirigir si no tiene permiso
    exit();
}
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Actualizar cita
  if (isset($_POST['id_agenda'], $_POST['fecha'], $_POST['doctor_id'], $_POST['servicio_id'], $_POST['paciente_id'])) {
    $id_agenda = $_POST['id_agenda'];
    $fecha = $_POST['fecha'];
    $doctor_id = $_POST['doctor_id'];
    $servicio_id = $_POST['servicio_id'];
    $paciente_id = $_POST['paciente_id'];

    // Actualizar agenda con fecha y servicio (el doctor ya viene en el servicio)
    $conn->query("UPDATE agenda SET date = '$fecha', service_id = '$servicio_id' WHERE id_agenda = $id_agenda");

    // Actualizar la cita (appointments) con el nuevo paciente y doctor
    $conn->query("UPDATE appointments SET user_id = '$paciente_id' WHERE service_id = '$servicio_id'");

    // Actualizar el doctor en el servicio relacionado
    $conn->query("UPDATE service SET user_id = '$doctor_id' WHERE service_id = '$servicio_id'");
  }

  // Eliminar cita
  if (isset($_POST['eliminar_id'])) {
    $id = $_POST['eliminar_id'];
    $conn->query("DELETE FROM agenda WHERE id_agenda = $id");
    $conn->query("DELETE FROM appointments WHERE service_id IN (SELECT service_id FROM agenda WHERE id_agenda = $id)");
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
<div class="min-height-300 bg-dark position-absolute w-100"></div>
    <?php include 'Navbar.php';?>
    <?php $current_page = 'citas'; ?>
    <?php include 'sidenav_admin.php';?>
    <main class="main-content position-relative border-radius-lg ">

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <nav aria-label="breadcrumb">
          <h2 class="font-weight-bolder text-white mb-0">Listado de citas</h2>
        </nav>
        </div>
        <div>
          <div class="card">
          </div>
          <div class="card-body">
          <div class="chart">
          <ul class="list-group">
            <?php
              $sql = "SELECT 
              a.id_agenda,
              a.date AS hora_cita,
              s.service_id,
              s.name AS servicio,
              s.user_id AS doctor_id,
              CONCAT(doc.name, ' ', doc.last_name) AS doctor,
              CONCAT(pac.name, ' ', pac.last_name) AS paciente,
              pac.user_id AS paciente_id
              FROM agenda a
              JOIN service s ON a.service_id = s.service_id
              JOIN user doc ON s.user_id = doc.user_id
              JOIN appointments ap ON ap.service_id = s.service_id
              JOIN user pac ON ap.user_id = pac.user_id
              WHERE ap.user_id = pac.user_id
              ORDER BY a.date ASC";
              $result = $conn->query($sql);
              while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
          <div>
            <strong><?= htmlspecialchars($row['paciente']) ?></strong><br>
            Servicio: <?= htmlspecialchars($row['servicio']) ?><br>
            Doctor: <?= htmlspecialchars($row['doctor']) ?><br>
            Hora: <?= date("d/m/Y H:i", strtotime($row['hora_cita'])) ?>
          </div>
          <div>
            <button class="btn btn-sm btn-primary" onclick="abrirModalEditar(<?= $row['id_agenda'] ?>, '<?= addslashes(htmlspecialchars($row['hora_cita'])) ?>', <?= $row['doctor_id'] ?>, <?= $row['service_id'] ?>, <?= $row['paciente_id'] ?>)">Editar</button>
            <button class="btn btn-sm btn-danger" onclick="abrirModalEliminar(<?= $row['id_agenda'] ?>)">Eliminar</button>
          </div>
        </li>
      <?php endwhile; ?>
    </ul>
  </div>
          </div>
        </div>
        </div>
      </div>
    </div>
</div>
    

  <!-- Modal Editar -->
  <div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Cita</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_agenda" id="edit_id_agenda">
          <div class="mb-3">
            <label>Fecha y hora</label>
            <input type="datetime-local" name="fecha" id="edit_fecha" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" class="form-control" id="edit_doctor" required>
              <?php
              $doctores = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS full_name FROM user WHERE rol = 'professional'");
              while ($d = $doctores->fetch_assoc()) {
                echo "<option value='{$d['user_id']}'>{$d['full_name']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Servicio</label>
            <select name="servicio_id" class="form-control" id="edit_servicio" required>
              <?php
              $servicios = $conn->query("SELECT service_id, name FROM service");
              while ($s = $servicios->fetch_assoc()) {
                echo "<option value='{$s['service_id']}'>{$s['name']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Paciente</label>
            <select name="paciente_id" class="form-control" id="edit_paciente" required>
              <?php
              $pacientes = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS full_name FROM user WHERE rol = 'pacient'");
              while ($p = $pacientes->fetch_assoc()) {
                echo "<option value='{$p['user_id']}'>{$p['full_name']}</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Eliminar -->
  <div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar Eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>¿Estás seguro de eliminar esta cita?</p>
          <input type="hidden" name="eliminar_id" id="eliminar_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function abrirModalEditar(id, fecha, doctor_id, servicio_id, paciente_id) {
      document.getElementById('edit_id_agenda').value = id;
      document.getElementById('edit_fecha').value = fecha.slice(0, 16);
      document.getElementById('edit_doctor').value = doctor_id;
      document.getElementById('edit_servicio').value = servicio_id;
      document.getElementById('edit_paciente').value = paciente_id;
      new bootstrap.Modal(document.getElementById('modalEditar')).show();
    }

    function abrirModalEliminar(id) {
      document.getElementById('eliminar_id').value = id;
      new bootstrap.Modal(document.getElementById('modalEliminar')).show();
    }
  </script>
</body>
</html>
