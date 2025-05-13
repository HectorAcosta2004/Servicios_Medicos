<?php
class CitaManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function cancelarCita($cita_id) {
        $stmt = $this->conn->prepare("DELETE FROM appointments WHERE cita_id = ?");
        $stmt->bind_param("i", $cita_id);
        return $stmt->execute();
    }
}
?>