<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "1234", "servicios_medicos");

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$sql = "
SELECT 
    YEAR(a.time_consult_start) AS year,
    WEEK(a.time_consult_start, 1) AS week_number,
    s.name AS service_name,
    COUNT(*) AS patients_count
FROM agenda a
JOIN service s ON a.service_id = s.service_id
GROUP BY year, week_number, service_name
ORDER BY year, week_number;
";

$result = $conn->query($sql);

$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'year' => $row['year'],
            'week_number' => $row['week_number'],
            'service_name' => $row['service_name'],
            'patients_count' => (int)$row['patients_count']
        ];
    }
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
}

$conn->close();
?>
