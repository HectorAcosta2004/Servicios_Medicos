<?php
session_start(); // Iniciar la sesión

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>SERVICIOS MEDICOS</title>
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
  <?php $current_page = 'dashboardm'; ?>
  <?php include 'sidenav_medico.php';?>
  <main class="main-content position-relative border-radius-lg ">

     
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <nav aria-label="breadcrumb">
          <h2 class="font-weight-bolder text-white mb-0">Mis citas</h2>
        </nav>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
          </div>
        </div>
      </div>
    </div>
    </div>

        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary"
              onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
      </div>
    </div>
  </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!--DATOS DE LA GRAFICA DE PASIENTES POR SERVICIO AL DIA -->
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
        // Realizar la consulta al PHP para obtener los datos
        fetch('datos_grafica.php')
            .then(response => response.json())
            .then(data => {
                // Procesar los datos y construir las etiquetas y valores para la gráfica
                const services = [...new Set(data.map(item => item.service_name))]; // Servicios únicos
                const weeks = [...new Set(data.map(item => item.week_number))]; // Semanas únicas

                // Inicializar un objeto para almacenar los datos por servicio y semana
                const serviceData = {};

                // Llenar el objeto con los datos de pacientes por semana
                services.forEach(service => {
                    serviceData[service] = new Array(weeks.length).fill(0);
                });

                // Llenar los datos en el objeto serviceData
                data.forEach(item => {
                    const serviceIndex = services.indexOf(item.service_name);
                    const weekIndex = weeks.indexOf(item.week_number);
                    serviceData[item.service_name][weekIndex] = item.patients_count;
                });

                // Crear los datasets para cada servicio
                const datasets = services.map(service => ({
                    label: service,
                    data: serviceData[service],
                    borderColor: getRandomColor(),
                    backgroundColor: getRandomColor(0.2),
                    borderWidth: 1
                }));

                // Crear la gráfica
                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: weeks,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error:', error));

        // Función para generar colores aleatorios
        function getRandomColor(alpha = 1) {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgba(${r},${g},${b},${alpha})`;
        }
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>