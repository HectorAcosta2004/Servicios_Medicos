<?php
session_start();

// Verificar si el usuario está logueado y si es un 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: index.php");
  exit();
}

// Incluir la clase Database (ajusta la ruta si es necesario)
require_once 'database.php';

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id'], $_POST['user_id'])) {
        $service_id = $_POST['service_id'];
        $user_id = $_POST['user_id'];

        // Verificar si existe el servicio
        $stmt = $conn->prepare("SELECT * FROM service WHERE service_id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Hacer la actualización
            $update = $conn->prepare("UPDATE service SET user_id = ? WHERE service_id = ?");
            $update->bind_param("ii", $user_id, $service_id);
            $update->execute();
        }

        echo "<script>alert('Asignación guardada exitosamente.'); window.location.href='asignacion.php';</script>";
        exit;
    }

} catch (Exception $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Consultas
$services_data = $conn->query("SELECT service_id, name FROM service")->fetch_all(MYSQLI_ASSOC);
$result_services = $conn->query("SELECT service_id, name FROM service");
$result_doctors = $conn->query("SELECT user_id, CONCAT(name, ' ', last_name) AS doctor_name FROM user WHERE rol = 'professional'");
$result_asignaciones = $conn->query("SELECT s.service_id, s.name AS service_name, u.user_id, CONCAT(u.name, ' ', u.last_name) AS doctor_name, s.time_consult_start, s.time_consult_finish FROM service s JOIN user u ON s.user_id = u.user_id WHERE u.rol = 'professional'");

include 'modals/ModalFactory.php';
?>

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

          <!-- Tabla Profesional-Servicio -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Profesional-Servicio</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Servicio</th>
                      <th>Profesional</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = $result_asignaciones->fetch_assoc()): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['service_name']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarPS<?= $row['service_id'] ?>">Editar</a>
                          <a href="eliminar_asignacion.php?service_id=<?= $row['service_id'] ?>" onclick="return confirm('¿Deseas eliminar esta asignación?')">Eliminar</a>
                        </td>
                      </tr>
                    <?php
                      ModalFactory::render('editar_profesional_servicio', [
                        'service' => $row,
                      'services' => $services_data,
                      'doctors' => $result_doctors,
                      'modal_id' => 'modalEditarPS' . $row['service_id']
                      ]);
                    ?>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col text-end">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarPS">Agregar</button>
            </div>
          </div>

          <?php
            ModalFactory::render('agregar_profesional_servicio', [
              'services' => $services_data,  // Array de servicios obtenidos de la base de datos
              'doctors' => $result_doctors->fetch_all(MYSQLI_ASSOC)  // Array de doctores obtenidos de la base de datos
            ]);
          ?>

          <!-- Tabla Servicio -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Servicio</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($services_data as $row): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarS<?= $row['service_id'] ?>">Editar</a>
                          <a href="eliminar_servicio.php?service_id=<?= $row['service_id'] ?>" onclick="return confirm('¿Deseas eliminar esta asignación?')">Eliminar</a>
                        </td>
                      </tr>
                      <?php
                        ModalFactory::render('editar_servicio', [
                          'service' => $row,
                          'modal_id' => 'modalEditarS' . $row['service_id']
                        ]);
                      ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col text-end">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarS">Agregar</button>
            </div>
          </div>

          <?php
          ModalFactory::render('agregar_servicio', [
            'services' => $services_data
          ]);
          ?>

        </div>
      </div>
    </div>
  </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>

<?php $conn->close(); ?>