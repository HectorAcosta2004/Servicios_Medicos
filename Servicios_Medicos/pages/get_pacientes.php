<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '1234', 'Servicios_Medicos');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del servicio de la consulta AJAX
$service_id = $_GET['service_id'];

// Consultar los pacientes asociados a ese servicio
$query = "
    SELECT u.name AS patient_name
    FROM appointments app
    JOIN user u ON app.user_id = u.user_id
    WHERE app.service_id = $service_id AND u.rol = 'pacient'
";
$result = $conn->query($query);

// Mostrar los pacientes
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['patient_name'] . "</li>";
    }
} else {
    echo "<li>No hay pacientes registrados para este servicio.</li>";
}

$conn->close();
?>
