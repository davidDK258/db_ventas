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

// Verificar si se ha enviado el formulario de edición
if(isset($_POST['editar'])){
    $id_usuario = $_POST['id_usuario'];
    $nombre_completo = $_POST['nombre_completo'];
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $genero = $_POST['genero'];
    $direcion = $_POST['direcion'];

    $sql_update = "UPDATE usuarios SET nombre_completo='$nombre_completo', usuario='$usuario', contraseña='$contraseña', genero='$genero', direcion='$direcion' WHERE id=$id_usuario";

    if ($conn->query($sql_update) === TRUE) {
        echo "Usuario actualizado correctamente.";
        // Redireccionar a usuarios.php después de actualizar exitosamente
        header("Location: usuarios.php");
        exit(); // Asegurar que el script se detenga después de redirigir
    } else {
        echo "Error al actualizar usuario: " . $conn->error;
    }
}

// Obtener el ID del usuario de la URL
$id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;

// Verificar si se ha proporcionado un ID de usuario válido
if ($id_usuario === null) {
    die("ID de usuario no proporcionado.");
}

// Consulta para obtener los datos del usuario
$sql = "SELECT * FROM usuarios WHERE id=$id_usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Mostrar el formulario de edición
    ?>
    <form method="post">
        <input type="hidden" name="id_usuario" value="<?php echo $row['id']; ?>">
        Nombre Completo: <input type="text" name="nombre_completo" value="<?php echo $row['nombre_completo']; ?>"><br>
        Usuario: <input type="text" name="usuario" value="<?php echo $row['usuario']; ?>"><br>
        Contraseña: <input type="text" name="contraseña" value="<?php echo $row['contraseña']; ?>"><br>
        Género: 
        <select name="genero">
            <option value="Masculino" <?php if($row['genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
            <option value="Femenino" <?php if($row['genero'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
        </select><br>
        Dirección: <input type="text" name="direcion" value="<?php echo $row['direcion']; ?>"><br>
        <input type="submit" name="editar" value="Guardar Cambios">
    </form>
    <?php
} else {
    echo "Usuario no encontrado.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animation 3D Card Effect</title>
    <link rel="stylesheet" href="estilo3.css">
</head>
<body>
    <a href="#">
        <div class="card">
            <div class="wrapper">
                <img src="https://ggayane.github.io/css-experiments/cards/dark_rider-cover.jpg" class="cover-image" />
            </div>
            <img src="https://ggayane.github.io/css-experiments/cards/dark_rider-title.png" class="title" />
            <img src="https://ggayane.github.io/css-experiments/cards/dark_rider-character.webp" class="character" />
        </div>
    </a>

    <a href="#">
        <div class="card">
            <div class="wrapper">
                <img src="https://ggayane.github.io/css-experiments/cards/force_mage-cover.jpg" class="cover-image" />
            </div>
            <img src="https://ggayane.github.io/css-experiments/cards/force_mage-title.png" class="title" />
            <img src="https://ggayane.github.io/css-experiments/cards/force_mage-character.webp" class="character" />
        </div>
    </a>
</body>
</html>



