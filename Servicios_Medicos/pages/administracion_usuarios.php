<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");
if ($conn->connect_error) {
  die("Error de conexion: ". $conn->connect_error);
  }
$sql = "SELECT user_id,name,last_name,rol FROM user";
$result_usuarios = $conn->query($sql);
if (!$result_usuarios) {
  die("Error de consulta: " . $conn->error);
}
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

          <h2 class="font-weight-bolder text-white mb-3">Administración de usaurios</h2>

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
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = $result_usuarios->fetch_assoc()): ?>
                      <tr>
                      <!-- <td><?= $row['user_id'] ?></td>-->
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['rol'] ?></td>
                        <td>
                          <a href="editar_usuario.php?user_id=<?= $row['user_id'] ?>">Editar</a> |
                          <a href="eliminar_usuario.php?user_id=<?= $row['user_id'] ?>"
                            onclick="return confirm('¿Deseas eliminar este usuario?')">Eliminar</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
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