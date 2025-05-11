<?php
require_once 'models/ProfesionalServicioModel.php';
require_once 'views/ModalFactory.php';

class ModalesController {
    private $model;

    public function __construct() {
        $pdo = new PDO('mysql:host=localhost;dbname=mi_basedatos', 'usuario', 'contraseña');
        $this->model = new ProfesionalServicioModel($pdo);
    }

    public function mostrar() {
        $servicios = $this->model->obtenerServicios();
        $profesionales = $this->model->obtenerProfesionales();

        $formModal = ModalFactory::create('form', 'modalAgregarPS', 'Asignar nuevo Profesional-Servicio', [
            'servicios' => $servicios,
            'profesionales' => $profesionales
        ]);
        $confirmModal = ModalFactory::create('confirm', 'modalConfirmar', '¿Estás seguro?');
        $alertModal = ModalFactory::create('alert', 'modalAlerta', 'Atención importante');

        // Renderizar
        $formModal->render();
        $confirmModal->render();
        $alertModal->render();
    }
}
