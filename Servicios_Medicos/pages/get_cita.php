<?php
$conn = new mysqli("localhost", "root", "", "servicios_medicos");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM agenda WHERE id_agenda = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $cita = $result->fetch_assoc();
        echo json_encode($cita); // Enviar los datos de la cita como JSON
    } else {
        echo json_encode(["error" => "Cita no encontrada"]);
    }
} else {
    echo json_encode(["error" => "No se recibió el ID de la cita"]);
}
?>
