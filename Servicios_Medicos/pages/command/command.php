<?php
require_once 'manager.php';

interface Command {
    public function ejecutar();
}

class CancelarCitaCommand implements Command {
    private $citaManager;
    private $cita_id;

    public function __construct(CitaManager $citaManager, $cita_id) {
        $this->citaManager = $citaManager;
        $this->cita_id = $cita_id;
    }

    public function ejecutar() {
        return $this->citaManager->cancelarCita($this->cita_id);
    }
}
?>