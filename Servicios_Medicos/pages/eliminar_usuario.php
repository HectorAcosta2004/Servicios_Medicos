<?php
require_once __DIR__ . '/command/userreceiver.php';
require_once __DIR__ . '/command/deleteuser.php';
require_once __DIR__ . '/command/invoker.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];

    $receiver = new UserReceiver();
    $command = new DeleteUserCommand($receiver, $userId);
    $invoker = new CommandInvoker();
    $invoker->setCommand($command);
    $invoker->run();

    header("Location: administracion_usuarios.php?msg=Usuario eliminado");
    exit;
}
?>