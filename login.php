<?php
session_start(); // Iniciar sesión en la parte superior del script

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

// Inicializar variables para mensajes de error
$error_message = "";

// Verificar si se ha enviado el formulario de inicio de sesión
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    if (isset($_POST["usuario"]) && isset($_POST["contraseña"])) {
        $usuario = mysqli_real_escape_string($conn, $_POST["usuario"]);
        $contraseña = mysqli_real_escape_string($conn, $_POST["contraseña"]);

        // Consulta para obtener el usuario de la base de datos
        $sql = "SELECT id, usuario, contraseña FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($contraseña == $row["contraseña"]) {
                $_SESSION["usuario_id"] = $row["id"];
                header("Location: interfaz.php");
                exit();
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "Usuario no encontrado.";
        }
    }
}

// Procesar el formulario de registro
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    if (isset($_POST["nombre"]) && isset($_POST["usuario"]) && isset($_POST["contraseña"]) && isset($_POST["genero"]) && isset($_POST["direccion"])) {
        $nombre = mysqli_real_escape_string($conn, $_POST["nombre"]);
        $usuario = mysqli_real_escape_string($conn, $_POST["usuario"]);
        $contraseña = mysqli_real_escape_string($conn, $_POST["contraseña"]);
        $genero = mysqli_real_escape_string($conn, $_POST["genero"]);
        $direccion = mysqli_real_escape_string($conn, $_POST["direccion"]);

        // Verificar si el usuario ya existe
        $sql = "SELECT id FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Insertar el nuevo usuario en la base de datos
            $sql = "INSERT INTO usuarios (nombre_completo, usuario, contraseña, genero, direcion) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nombre, $usuario, $contraseña, $genero, $direccion);
            if ($stmt->execute()) {
                echo "Registro exitoso. Por favor, inicia sesión.";
            } else {
                echo "Error al registrar el usuario: " . $stmt->error;
            }
        } else {
            $error_message = "El usuario ya existe. Por favor, elige otro nombre de usuario.";
        }
    }
}   
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Register - MagtimusPro</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<main>
    <div class="contenedor__todo">
        <div class="caja__trasera">
            <div class="caja__trasera-login">
                <h3>¿Ya tienes una cuenta?</h3>
                <p>Inicia sesión para entrar en la página</p>
                <button id="btn__iniciar-sesion">Iniciar Sesión</button>
            </div>
            <div class="caja__trasera-register">
                <h3>¿Aún no tienes una cuenta?</h3>
                <p>Regístrate para que puedas iniciar sesión</p>
                <button id="btn__registrarse">Regístrarse</button>
            </div>
        </div>

        <div class="contenedor__login-register">
            <!-- Login -->
            <form action="" method="POST" class="formulario__login">
                <h2>Iniciar Sesión</h2>
                <input type="text" placeholder="Usuario" name="usuario" required>
                <input type="password" placeholder="Contraseña" name="contraseña" required>
                <button type="submit" name="login">Entrar</button>
                <?php if (!empty($error_message) && isset($_POST["login"])): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </form>

            <!-- Register -->
            <form action="" class="formulario__register" method="POST">
                <h2>Regístrarse</h2>
                <input type="text" placeholder="Nombre completo" name="nombre" required>
                <input type="text" placeholder="Usuario" name="usuario" required>
                <input type="password" placeholder="Contraseña" name="contraseña" required>
                <input type="text" placeholder="Dirección" name="direccion" required>
                <label for="genero">Género:</label>
                <select name="genero" id="genero" required>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                </select>
                <button type="submit" name="register">Regístrarse</button>
                <?php if (!empty($error_message) && isset($_POST["register"])): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>

