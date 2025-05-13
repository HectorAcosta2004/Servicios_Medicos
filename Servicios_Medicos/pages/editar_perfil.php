<?php
session_start();

include_once 'database.php'; 

$db = Database::getInstance();
$conn = $db->getConnection();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirigir si no está autenticado
    exit;
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id']; 

// Obtener los datos del usuario
$user = obtenerUsuarioPorId($user_id);

if (!$user) {
    echo "No se encontró el usuario.";
    exit;
}

// Procesar la actualización del perfil si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];

    // Actualizar los datos del usuario
    actualizarUsuario($user_id, $name, $last_name);

    // Redirigir o mostrar mensaje de éxito
    echo "Perfil actualizado con éxito.";
    header("Location: editar_perfil.php"); // Para evitar reenvío del formulario
    exit;
}

// Función para obtener los datos del usuario por ID
function obtenerUsuarioPorId($user_id) {
    global $conn; // Usar la conexión a la base de datos

    $sql = "SELECT * FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id); // "i" es para enteros
    $stmt->execute();
    $result = $stmt->get_result();

    // Si se encuentra el usuario, retornar los datos
    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // Devuelve los datos del usuario
    } else {
        return null; // Si no se encuentra el usuario, retorna null
    }
}

// Función para actualizar los datos del usuario
function actualizarUsuario($user_id, $name, $last_name) {
    global $conn;

    $sql = "UPDATE user SET name = ?, last_name = ?, WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $last_name, $user_id); // "sssi" para string, string, string, integer
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>

    <?php include 'Navbar.php'; ?>
    <?php include 'sidenav_admin.php'; ?>

    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">

                    <h2 class="font-weight-bolder text-white mb-3">Editar Perfil</h2>

                    <!-- Formulario de Edición de Perfil -->
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Información del Usuario</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <form action="editar_perfil.php" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
