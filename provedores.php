<?php

// Variables para la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_ventas";
$port = 3310; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario de registro cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_empresa = $_POST['nombre_empresa'];
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $celular = $_POST['celular'];
    $rut = $_POST['rut'];

    // Insertar datos en la base de datos
    $sql = "INSERT INTO proveedores (nombre_empresa, nombre_proveedor, celular, rut) VALUES ('$nombre_empresa', '$nombre_proveedor', '$celular', '$rut')";

    if ($conn->query($sql) === TRUE) {
        echo "Proveedor registrado exitosamente.";
    } else {
        echo "Error al registrar proveedor: " . $conn->error;
    }
}

// Obtener la lista de proveedores registrados
$sql = "SELECT id, nombre_empresa, nombre_proveedor, celular, rut FROM proveedores";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Proveedores</title>
</head>
<body>

<h2>Registro de Proveedores</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="nombre_empresa">Nombre de la Empresa:</label><br>
    <input type="text" id="nombre_empresa" name="nombre_empresa" required><br>
    
    <label for="nombre_proveedor">Nombre del Proveedor:</label><br>
    <input type="text" id="nombre_proveedor" name="nombre_proveedor"><br>
    
    <label for="celular">Número de Celular:</label><br>
    <input type="text" id="celular" name="celular" required><br>

    <label for="rut">RUT:</label><br>
    <input type="text" id="rut" name="rut" required><br><br>
    
    <input type="submit" value="Registrar Proveedor">
</form>

<!-- Botón para salir -->
<form action="interfaz.php">
    <input type="submit" value="Salir">
</form>

<br><br>

<h2>Proveedores Registrados</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre Empresa</th>
        <th>Nombre Proveedor</th>
        <th>Celular</th>
        <th>RUT</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["nombre_empresa"]."</td>";
            echo "<td>".$row["nombre_proveedor"]."</td>";
            echo "<td>".$row["celular"]."</td>";
            echo "<td>".$row["rut"]."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No hay proveedores registrados.</td></tr>";
    }
    ?>
</table>

</body>
</html>
