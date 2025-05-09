<?php
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Lógica para guardar asignación
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id'], $_POST['user_id'])) {
    $service_id = $conn->real_escape_string($_POST['service_id']);
    $user_id = $conn->real_escape_string($_POST['user_id']);

    // Verificar si ya hay asignación previa
    $check = $conn->query("SELECT * FROM service WHERE service_id = '$service_id'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE service SET user_id = '$user_id' WHERE service_id = '$service_id'");
    }

    // Opcional: mensaje o redirección
    echo "<script>alert('Asignación guardada exitosamente.'); window.location.href='asignacion.php';</script>";
    exit;
}

// Consultas para llenar los selects y la tabla
$result_services = $conn->query("SELECT service_id, name FROM service");
$result_doctors = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS doctor_name FROM user WHERE rol = 'professional'");
$result_asignaciones = $conn->query("
    SELECT s.service_id, s.name AS service_name, u.user_id, CONCAT(u.name, ' ', u.last_name) AS doctor_name, s.time_consult_start, s.time_consult_finish
    FROM service s
    JOIN user u ON s.user_id = u.user_id
    WHERE u.rol = 'professional'
");
?>

<!-- Aquí inicia el HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignación de Profesionales</title>
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include 'Navbar.php'; ?>
  <?php $current_page = 'asignacion'; ?>
  <?php include 'sidenav_admin.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">

          <h2 class="font-weight-bolder text-white mb-3">Asignación de Profesionales</h2>

          <!-- Tabla Profesional-Servicio-->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Profesional-Servicio</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Profesional</th>
                      <th>Servicio</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col text-end">
              <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarPS">Agregar</button>
            </div>
          </div>

          <!-- Modal Agregar Profesional-Servicio -->
          <div class="modal fade" id="modalAgregarPS" tabindex="-1" aria-labelledby="modalAgregarPSLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalAgregarPSLabel">Asignar nuevo Profesional-Servicio</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
            <!-- Formulario del Profesional-Servicio -->
                  <form method="POST" action="">
                    <div class="mb-3">
                      <label for="Servicio" class="form-label">Servicio</label>
                      <select class="form-control">
                        <option value="">Selecciona un servicio</option>
                        </select>
                      <label for="Profesional" class="form-label">Profesional</label>
                      <select class="form-control">
                        <option value="">Selecciona un profesional</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Guardar</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>


          <!-- Tabla Servicios-->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Servicios</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Servicio</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col text-end">
              <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarServicio">Agregar</button>
            </div>
          </div>

          <!-- Modal Agregar Servicio -->
          <div class="modal fade" id="modalAgregarServicio" tabindex="-1" aria-labelledby="modalAgregarServicioLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalAgregarServicioLabel">Agregar nuevo Servicio</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
            <!-- Formulario del Profesional-Servicio -->
                  <form method="POST" action="">
                    <div class="mb-3">
                      <label for="Servicio" class="form-label">Servicio</label>
                      <input type="text" class="form-control" id="nuevoServicio" name="nombre_servicio" required>
                      <label for="profesional" class="form-label">Profesional</label>
                      <select class="form-control">
                        <option value="">Selecciona un profesional</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Guardar</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabla de asignaciones- no se ocupa inicio ni fin -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Asignaciones Existentes</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Servicio</th>
                      <th>Doctor Asignado</th>
                      <th>Inicio</th>
                      <th>Fin</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = $result_asignaciones->fetch_assoc()): ?>
                      <tr>
                        <td><?= $row['service_name'] ?></td>
                        <td><?= $row['doctor_name'] ?></td>
                        <td><?= $row['time_consult_start'] ?></td>
                        <td><?= $row['time_consult_finish'] ?></td>
                        <td>
                          <a href="editar_asignacion.php?service_id=<?= $row['service_id'] ?>">Editar</a> |
                          <a href="eliminar_asignacion.php?service_id=<?= $row['service_id'] ?>" onclick="return confirm('¿Deseas eliminar esta asignación?')">Eliminar</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Formulario unificado -->
          <div class="card mb-2">
            <div class="card-header pb-0">
              <h6>Nuevo servicio</h6>
            </div>
            <div class="card-body">
              <form method="POST" action="">
                <div class="form-group mb-3">
                  <label for="service_id">Seleccionar Servicio</label>
                  <select class="form-control" name="service_id" id="service_id" required>
                    <option value="">Selecciona un Servicio</option>
                    <?php while ($service = $result_services->fetch_assoc()): ?>
                      <option value="<?= $service['service_id'] ?>"><?= $service['name'] ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>

                <div class="form-group mb-3">
                  <label for="user_id">Seleccionar Doctor</label>
                  <select class="form-control" name="user_id" id="user_id" required>
                    <option value="">Selecciona un Doctor</option>
                    <?php while ($doctor = $result_doctors->fetch_assoc()): ?>
                      <option value="<?= $doctor['user_id'] ?>"><?= $doctor['doctor_name'] ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>

                <button type="submit" class="btn btn-primary">Asignar</button>
              </form>
            </div>
          </div>

          

        </div>
      </div>
    </div>
  </main>
  
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>

<?php $conn->close(); ?>
