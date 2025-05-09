<?php
$conn = new mysqli('localhost', 'root', '1234', 'Servicios_Medicos');
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$service_id = $_GET['service_id'];
$appointment_date = $_GET['appointment_date'];

$query = "
    SELECT u.name, u.last_name
    FROM appointments app
    JOIN user u ON app.user_id = u.user_id
    JOIN agenda a ON app.service_id = a.service_id
    JOIN service s ON app.service_id = s.service_id
    WHERE app.service_id = ? 
      AND a.date = ? 
      AND a.date BETWEEN s.time_consult_start AND s.time_consult_finish
";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $service_id, $appointment_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($paciente = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($paciente['name'] . ' ' . $paciente['last_name']) . "</li>";
    }
} else {
    echo "<li>No hay pacientes para esta cita.</li>";
}

$stmt->close();
$conn->close();
?>
