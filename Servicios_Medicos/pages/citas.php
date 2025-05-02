<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "servicios_medicos");

if (isset($_GET['id_agenda'])) {
    $id_agenda = $_GET['id_agenda'];
    
    // Consulta para obtener los detalles de la cita
    $sql = "
    SELECT 
        a.id_agenda,
        a.date AS hora_cita,
        s.service_id, 
        s.name AS servicio,
        doc.user_id AS doctor_id,
        CONCAT(doc.name, ' ', doc.last_name) AS doctor,
        pac.user_id AS paciente_id,
        CONCAT(pac.name, ' ', pac.last_name) AS paciente
    FROM agenda a
    JOIN service s ON a.service_id = s.service_id
    JOIN user doc ON s.user_id = doc.user_id -- Relación con el doctor
    JOIN appointments ap ON ap.service_id = s.service_id
    JOIN user pac ON ap.user_id = pac.user_id -- Relación con el paciente
    WHERE a.id_agenda = $id_agenda";  // Inyectar el valor de id_agenda directamente
    
    // Ejecutar la consulta
    $result = $conn->query($sql);
    
    // Comprobar si se obtuvo algún resultado
    if ($result->num_rows > 0) {
        $cita = $result->fetch_assoc();
        // Devolver los datos como JSON
        echo json_encode($cita);
    } else {
        // Si no se encuentra la cita
        echo json_encode(["error" => "Cita no encontrada"]);
    }
}
?>
