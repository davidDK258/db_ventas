<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_ventas";
$port = 3310; // Puerto específico

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nombre"]) && isset($_POST["usuario"]) && isset($_POST["contraseña"])) {
    // Obtener datos del formulario y limpiarlos
    $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
    $usuario = mysqli_real_escape_string($conn, $_POST["usuario"]);
    $contraseña = mysqli_real_escape_string($conn, $_POST["contraseña"]);

    // Hash de la contraseña
    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);

    // Consulta preparada para insertar un nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre_completo, usuario, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $usuario, $hashed_password);

    if ($stmt->execute()) {
        // Registro exitoso, redireccionar a la página de inicio de sesión
        header("Location: login.php");
        exit();
    } else {
        // Error al registrar usuario
        echo "Error al registrar el usuario: " . $conn->error;
    }
}
?>

