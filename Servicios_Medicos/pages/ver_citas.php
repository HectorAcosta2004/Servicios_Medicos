<?php
$conn = new mysqli("localhost", "root", "", "servicios_medicos");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Actualizar cita
  if (isset($_POST['id_agenda'], $_POST['fecha'], $_POST['doctor_id'], $_POST['servicio_id'], $_POST['paciente_id'])) {
    $id_agenda = $_POST['id_agenda'];
    $fecha = $_POST['fecha'];
    $doctor_id = $_POST['doctor_id'];
    $servicio_id = $_POST['servicio_id'];
    $paciente_id = $_POST['paciente_id'];

    $conn->query("UPDATE agenda SET date = '$fecha', user_id = '$doctor_id', service_id = '$servicio_id' WHERE id_agenda = $id_agenda");
    $conn->query("UPDATE appointments SET user_id = '$paciente_id' WHERE service_id = '$servicio_id' AND user_id = '$paciente_id'");
  }

  // Eliminar cita
  if (isset($_POST['id_agenda']) && !isset($_POST['fecha'])) {
    $id = $_POST['id_agenda'];
    $conn->query("DELETE FROM agenda WHERE id_agenda = $id");
  }

  // Redirigir para evitar reenvío
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>

<!DOCTYP

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>CITAS</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>
<?php $current_page = 'citas'; ?>
<?php include 'sidenav.php'; ?>
  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-7 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <h6 class="mb-0">Citas</h6>
            </div>
            <div class="card-body pt-4 p-3">
              <?php
              $sql = "
              SELECT 
                a.id_agenda,
                a.date AS hora_cita,
                s.name AS servicio,
                CONCAT(doc.name, ' ', doc.last_name) AS doctor,
                CONCAT(pac.name, ' ', pac.last_name) AS paciente
              FROM agenda a
              JOIN service s ON a.service_id = s.service_id
              JOIN user doc ON s.user_id = doc.user_id -- Relación con el doctor (profesional)
              JOIN appointments ap ON ap.service_id = s.service_id
              JOIN user pac ON ap.user_id = pac.user_id -- Relación con el paciente
              ORDER BY a.date ASC";
              $result = $conn->query($sql);
              ?>

              <div class="container mt-4">
                <div class="row">
                  <div class="col-12">
                    <div class="card mb-4">
                      <div class="card-header pb-0">
                        <h6>Listado de Citas</h6>
                      </div>
                      <div class="card-body px-0 pt-0 pb-2">
                        <ul class="list-group px-3">
                          <?php while ($row = $result->fetch_assoc()): ?>
                            <li class="list-group-item border-0 d-flex justify-content-between align-items-center mb-2 bg-gray-100 border-radius-lg">
                              <div class="d-flex flex-column">
                                <h6 class="mb-1 text-sm"><?= $row['paciente'] ?></h6>
                                <span class="mb-1 text-xs">Servicio: <strong><?= $row['servicio'] ?></strong></span>
                                <span class="mb-1 text-xs">Doctor: <strong><?= $row['doctor'] ?></strong></span>
                                <span class="text-xs">Hora: <strong><?= date("d/m/Y H:i", strtotime($row['hora_cita'])) ?></strong></span>
                              </div>
                              <div class="ms-auto">
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="abrirModalEditar(<?= $row['id_agenda'] ?>, '<?= $row['hora_cita'] ?>')">
                                  <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="abrirModalEliminar(<?= $row['id_agenda'] ?>)">
                                  <i class="fas fa-trash-alt me-1"></i> Eliminar
                                </button>
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
        </div>
      </div>
    </div>
  </main>

  <!-- Modal de Edición -->
  <div class="modal fade" id="modalEditarCita" tabindex="-1" aria-labelledby="modalEditarCitaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" id="editarCitaForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditarCitaLabel">Editar Cita</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="id_agenda" name="id_agenda">
            <div class="mb-3">
              <label for="fecha" class="form-label">Fecha y Hora</label>
              <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal de Edición -->
<div class="modal fade" id="modalEditarCita" tabindex="-1" aria-labelledby="modalEditarCitaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="editarCitaForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarCitaLabel">Editar Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_agenda" name="id_agenda">
                    <!-- Fecha y hora -->
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <!-- Selección del Doctor -->
                    <div class="mb-3">
                        <label for="doctor" class="form-label">Seleccionar Doctor</label>
                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                            <!-- Aquí se deben cargar los doctores desde la base de datos -->
                            <?php
                            $doctors = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS full_name FROM user WHERE role = 'doctor'");
                            while ($doctor = $doctors->fetch_assoc()) {
                                echo "<option value='{$doctor['user_id']}'>{$doctor['full_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Selección del Servicio -->
                    <div class="mb-3">
                        <label for="servicio" class="form-label">Seleccionar Servicio</label>
                        <select class="form-control" id="servicio_id" name="servicio_id" required>
                            <!-- Aquí se deben cargar los servicios desde la base de datos -->
                            <?php
                            $services = $conn->query("SELECT service_id, name FROM service");
                            while ($service = $services->fetch_assoc()) {
                                echo "<option value='{$service['service_id']}'>{$service['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Selección del Paciente -->
                    <div class="mb-3">
                        <label for="paciente" class="form-label">Seleccionar Paciente</label>
                        <select class="form-control" id="paciente_id" name="paciente_id" required>
                            <!-- Aquí se deben cargar los pacientes desde la base de datos -->
                            <?php
                            $patients = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS full_name FROM user WHERE role = 'patient'");
                            while ($patient = $patients->fetch_assoc()) {
                                echo "<option value='{$patient['user_id']}'>{$patient['full_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>
