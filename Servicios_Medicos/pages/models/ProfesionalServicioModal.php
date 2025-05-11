<?php
class ProfesionalServicioModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerServicios() {
        $stmt = $this->pdo->query("SELECT id, nombre FROM servicios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProfesionales() {
        $stmt = $this->pdo->query("SELECT id, nombre FROM profesionales");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
