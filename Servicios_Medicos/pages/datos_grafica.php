<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "Servicios_Medicos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL
$sql = "
    SELECT 
    s.name AS service_name, 
    WEEK(a.date) AS week_number, 
    COUNT(DISTINCT ap.user_id) AS patients_count
FROM 
    appointments ap
JOIN 
    service s ON ap.service_id = s.service_id
JOIN 
    user u ON ap.user_id = u.user_id
JOIN 
    agenda a ON ap.service_id = a.service_id  -- Aquí se une con la tabla agenda para obtener la fecha
WHERE 
    u.rol = 'pacient'
GROUP BY 
    service_name, week_number
ORDER BY 
    service_name, week_number;

";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}

// Convertir el array a formato JSON
echo json_encode($data);

// Cerrar la conexión
$conn->close();
?>
