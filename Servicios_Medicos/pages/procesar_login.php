<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'decorator/basic_message.php';
require_once 'decorator/icon_decorator.php';
require_once 'decorator/alert_decorator.php';

/////////////////////
// CLASE FACHADA   //
/////////////////////
class LoginFacade {
    public static function authenticate($username, $password) {
        $db = new Database();
        $conn = $db->connect();
        $userService = new UserService($conn);
        $user = $userService->getUserByUsername($username);

        if ($user && $password === $user['password']) { // Aquí puedes usar password_verify()
            return $user;
        }

        return false;
    }
}

/////////////////////////
// CLASE DB CONNECTION //
/////////////////////////
class Database {
    private $host = 'localhost';
    private $db = 'Servicios_Medicos';
    private $user = 'root';
    private $pass = '1234';

    public function connect() {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        return $conn;
    }
}

/////////////////////////
// SERVICIO DE USUARIO //
/////////////////////////
class UserService {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

/////////////////////////
// MANEJO PRINCIPAL    //
/////////////////////////

// Recibir datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Autenticación
$user = LoginFacade::authenticate($username, $password);

if ($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['rol'];

    // Redirigir al dashboard según rol
    switch ($user['rol']) {
        case 'admin':
            header("Location: dashboard_admin.php");
            break;
        case 'pacient':
            header("Location: dashboard_patient.php");
            break;
        case 'professional':
            header("Location: dashboard_medico.php");
            break;
        default:
            header("Location: index.php");
            break;
    }
    exit();
} else {
    $msg = new BasicMessage("Usuario o contraseña incorrectos.");
    $msgWithIcon = new IconDecorator($msg, '🔐');
    $alert = new AlertDecorator($msgWithIcon, 'error', 'Error de inicio de sesión');
    echo $alert->render();
}
?>
