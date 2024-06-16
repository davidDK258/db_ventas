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

// Manejar la eliminación de usuarios si se ha enviado el formulario
if(isset($_POST['eliminar'])){
    $id_usuario = $_POST['id_usuario'];
    $sql_delete = "DELETE FROM usuarios WHERE id = $id_usuario";
    if ($conn->query($sql_delete) === TRUE) {
        echo "Usuario eliminado correctamente.";
    } else {
        echo "Error al eliminar usuario: " . $conn->error;
    }
}

// Consulta para obtener todos los usuarios
$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Género</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>";

    // Mostrar los datos de cada usuario
    while($row = $result->fetch_assoc()) {
        // Ocultar la contraseña
        $password_length = strlen($row["contraseña"]);
        $hidden_password = str_repeat('*', $password_length);
        
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nombre_completo"] . "</td>
                <td>" . $row["usuario"] . "</td>
                <td>" . $hidden_password . "</td>
                <td>" . $row["genero"] . "</td>
                <td>" . $row["direcion"] . "</td>
                <td>
                    <form method='post' style='display: inline;'>
                        <input type='hidden' name='id_usuario' value='" . $row["id"] . "'>
                        <input type='submit' name='eliminar' value='Eliminar'>
                    </form>
                    <form action='editarusu.php' method='get' style='display: inline;'>
                        <input type='hidden' name='id_usuario' value='" . $row["id"] . "'>
                        <input type='submit' value='Editar'>
                    </form>
                </td>
            </tr>";
    }
    echo "<tr><td colspan='7'><form action='interfaz.php'><input type='submit' value='Salir'></form></td></tr></table>";
} else {
    echo "No se encontraron usuarios registrados.";
}

$conn->close();
?>
<head>
    <link rel="stylesheet" type="text/css" href="estilo2.css">
</head>







