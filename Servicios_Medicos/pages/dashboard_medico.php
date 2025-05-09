<?php
session_start(); // Iniciar la sesión

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '1234', 'Servicios_Medicos');
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Obtener las citas, los servicios, las fechas y los pacientes
$query = "
    SELECT 
        app.cita_id, 
        s.name AS service_name, 
        a.date AS appointment_date, 
        app.service_id
    FROM appointments app
    JOIN service s ON app.service_id = s.service_id
    JOIN agenda a ON app.service_id = a.service_id  -- Reemplaza agenda_id con service_id si es la correcta
    GROUP BY s.name, a.date
    ORDER BY a.date";


$result = $conn->query($query);
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
  <!-- Estilos para el modal -->
  <style>
    .modal-content {
      padding: 20px;
    }

    .modal-header {
      background-color: #f8f9fa;
    }

    .modal-footer {
      border-top: none;
    }

    .modal-body ul {
      list-style-type: none;
      padding: 0;
    }

    .modal-body li {
      padding: 5px 0;
    }
  </style>
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
          <nav aria-label="breadcrumb">
            <h2 class="font-weight-bolder text-white mb-0">Mis citas</h2>
          </nav>
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
              <th>Fecha y hora</th>
              <th>Paciente</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
</body>
      
    </div>

    <!-- Tabla para mostrar citas, servicio, paciente y fecha -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h6>Mis Citas, Servicios y Pacientes</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th style="display:none;">ID Cita</th>
                    <th>Servicio</th>
                    <th>Fecha de Cita</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Verificar si hay resultados
                  if ($result->num_rows > 0) {
                    // Mostrar los resultados
                    while ($row = $result->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . $row['service_name'] . "</td>";
                      echo "<td>" . $row['appointment_date'] . "</td>";
                      echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#modal" . $row['cita_id'] . "' data-service-id='" . $row['service_id'] . "'>Ver Descripción</button></td>";
                      echo "</tr>";

                      // Modal para cada cita
                      echo "
          <div class='modal fade' id='modal" . $row['cita_id'] . "' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title' id='exampleModalLabel'>Descripción de la Cita</h5>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>
                <div class='modal-body' id='modal-body-" . $row['cita_id'] . "'>
                  <p><strong>ID Cita:</strong> " . $row['cita_id'] . "</p>
                  <p><strong>Servicio:</strong> " . $row['service_name'] . "</p>
                  <p><strong>Fecha de Cita:</strong> " . $row['appointment_date'] . "</p>
                  <p><strong>Pacientes:</strong></p>
                  <ul id='pacientes-list-" . $row['cita_id'] . "'>
                    <!-- Aquí se llenarán los pacientes de este servicio -->
                  </ul>
                </div>
                <div class='modal-footer'>
                  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          ";
                    }
                  } else {
                    echo "<tr><td colspan='4'>No se encontraron citas.</td></tr>";
                  }
                  ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>

  <!-- Scripts para el modal (Bootstrap) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Cargar los pacientes al abrir el modal -->
  <script>
$(document).ready(function() {
  // Al hacer clic en el botón "Ver Descripción"
  $('.btn-info').click(function() {
    var service_id = $(this).data('service-id'); // Obtener el ID del servicio
    var appointment_date = $(this).closest('tr').find('td').eq(1).text(); // Obtener la fecha de la cita
    
    var modalId = $(this).data('target').replace('#', ''); // Obtener el ID del modal

    // Realizar la consulta AJAX para obtener los pacientes de ese servicio y fecha
    $.ajax({
      url: 'get_pacientes.php', 
      type: 'GET',
      data: { service_id: service_id, appointment_date: appointment_date },
      success: function(data) {
        // Llenar el modal con los pacientes
        $('#pacientes-list-' + modalId).html(data);
      }
    });
  });
});
  </script>
</body>

</html>
<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>