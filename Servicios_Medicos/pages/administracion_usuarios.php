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
  // Obtener la instancia y conexión
  $db = Database::getInstance();
  $conn = $db->getConnection();

  // Ejecutar consulta
  $sql = "SELECT user_id, name, last_name, rol FROM user";
  $result_usuarios = $conn->query($sql);

  // Verificar si hay resultados
  if ($result_usuarios->num_rows > 0) {
    // Obtener todos los resultados como un array asociativo
    $usuarios = [];
    while ($row = $result_usuarios->fetch_assoc()) {
      $usuarios[] = $row;
    }
  } else {
    $usuarios = [];
  }
} catch (Exception $e) {
  die("Error en la base de datos: " . $e->getMessage());
}

include 'modals/ModalFactory.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Administración de Usuarios</title>
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include 'Navbar.php'; ?>
  <?php $current_page = 'usuarios'; ?>
  <?php include 'sidenav_admin.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">

          <h2 class="font-weight-bolder text-white mb-3">Administración de usuarios</h2>

          <!-- Tabla Usuarios-->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Profesionales</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Rol</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($usuarios as $row): ?>
                      <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['rol']) ?></td>
                        <td>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditarU<?= $row['user_id'] ?>">Editar</a>
                        </td>
                      </tr>
                      <?php
                        ModalFactory::render('editar_usuarios', [
                          'user' => $row
                        ]);
                      ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
