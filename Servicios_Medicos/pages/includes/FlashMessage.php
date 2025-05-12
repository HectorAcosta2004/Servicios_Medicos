<?php
// includes/FlashMessage.php

class FlashMessage {
    private static $instance;
    private $messages = [];

    private function __construct() { }

    // Método para obtener la instancia de la clase (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new FlashMessage();
        }
        return self::$instance;
    }

    // Agregar mensaje
    public function addMessage($message, $type = 'info') {
        $this->messages[] = ['message' => $message, 'type' => $type];
    }

    // Mostrar los mensajes
    public function displayMessages() {
        foreach ($this->messages as $message) {
            echo "<div class='alert alert-{$message['type']}'>
                    {$message['message']}
                  </div>";
        }
        $this->messages = []; // Limpiar los mensajes después de mostrarlos
    }
}
?>
