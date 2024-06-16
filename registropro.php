<?php
session_start();

// Configuración de la conexión a la base de datos
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

// Mensaje para la inserción de productos
$message = "";

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conn, $_POST['precio']);
    $cantidad_total  = mysqli_real_escape_string($conn, $_POST['cantidad_total']);
    $proveedor_id = mysqli_real_escape_string($conn, $_POST['proveedor_id']);

    // Insertar datos en la tabla de productos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad_total, cantidad_disponible, proveedor_id) 
            VALUES ('$nombre', '$descripcion', '$precio', '$cantidad_total', '$cantidad_total', '$proveedor_id')";

    if ($conn->query($sql) === TRUE) {
        $message = "Producto registrado exitosamente.";
    } else {
        $message = "Error al registrar el producto: " . $conn->error;
    }
}

// Consultar la lista de proveedores para el formulario
$sql_proveedores = "SELECT id, nombre_empresa FROM proveedores";
$result_proveedores = $conn->query($sql_proveedores);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Productos</title>
</head>
<body>

<h2>Registro de Productos</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="nombre">Nombre del Producto:</label><br>
    <input type="text" id="nombre" name="nombre" required><br>
    
    <label for="descripcion">Descripción:</label><br>
    <textarea id="descripcion" name="descripcion"></textarea><br>
    
    <label for="precio">Precio:</label><br>
    <input type="text" id="precio" name="precio" required><br>

    <label for="cantidad_total">Cantidad Total:</label><br>
    <input type="text" id="cantidad_total" name="cantidad_total" required><br>

    <label for="proveedor_id">Proveedor:</label><br>
    <select id="proveedor_id" name="proveedor_id">
        <?php
        if ($result_proveedores->num_rows > 0) {
            while ($row = $result_proveedores->fetch_assoc()) {
                echo "<option value='".$row["id"]."'>".$row["nombre_empresa"]."</option>";
            }
        } else {
            echo "<option value=''>No hay proveedores registrados</option>";
        }
        ?>
    </select><br><br>
    
    <input type="submit" value="Registrar Producto">
    <a href="interfaz.php"><button type="button">Salir</button></a> <!-- Botón de salida -->
</form>

<p><?php echo $message; ?></p>

</body>
</html>
