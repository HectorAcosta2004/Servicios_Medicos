<?php
class Database {
    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $dbname = 'servicios_medicos';
    private $username = 'root';
    private $password = '1234';
    
    // Constructor privado para evitar la creación directa de objetos
    private function __construct() {
        // Crear la conexión
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        // Verificar si hubo un error en la conexión
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    // Método público para obtener la única instancia de la clase
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Método para obtener la conexión
    public function getConnection() {
        return $this->conn;
    }

    // Prevenir la clonación del objeto
    private function __clone() {}

    // Prevenir la deserialización del objeto
    public function __wakeup() {}
}
?>