<?php
session_start();

require_once 'message_cmponent.php';
require_once 'alert_decorator.php';

// Lógica para manejar el mensaje de alerta
$_SESSION['global_alert'] = [
    'message' => '¡Acción exitosa!',
    'type' => 'success', 
];
?>
