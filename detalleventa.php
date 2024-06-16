<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $usuario_id = $_POST["usuario"];
    $dni_cliente = $_POST["dni_cliente"];
    $nombre_cliente = $_POST["nombre_cliente"];
    $telefono_cliente = $_POST["telefono_cliente"];
    $correo_cliente = $_POST["correo_cliente"];
    $producto_id = $_POST["producto"];
    $cantidad = $_POST["cantidad"];

    // Aquí podrías realizar cualquier otro procesamiento necesario, como buscar información adicional de la base de datos, calcular el precio total, etc.
    
    // Obtener el Rut por defecto
    $rut_por_defecto = "rut000050505005";

    // Obtener el nombre del producto seleccionado
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "db_ventas";
    $port = 3310;

    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
    }

    $sql_producto_nombre = "SELECT nombre FROM productos WHERE id = '$producto_id'";
    $result_producto_nombre = $conn->query($sql_producto_nombre);
    $row_producto_nombre = $result_producto_nombre->fetch_assoc();
    $nombre_producto = $row_producto_nombre["nombre"];

    // Cerrar la conexión
    $conn->close();

    // Obtener la fecha actual
    $fecha_actual = date("Y-m-d");

    // Mostrar los detalles de la venta
    echo "<div style='text-align: center;'>";
    echo "<div style='border: 1px solid black; padding: 20px;'>";
    echo "<h2>Detalles de la Venta</h2>";
    echo "<p><strong>RUT:</strong> $rut_por_defecto</p>";
    echo "<p><strong>Usuario:</strong> $usuario_id</p>";
    echo "<p><strong>DNI Cliente:</strong> $dni_cliente</p>";
    echo "<p><strong>Nombre Cliente:</strong> $nombre_cliente</p>";
    echo "<p><strong>Teléfono Cliente:</strong> $telefono_cliente</p>";
    echo "<p><strong>Correo Cliente:</strong> $correo_cliente</p>";
    echo "<p><strong>Producto:</strong> $nombre_producto</p>";
    echo "<p><strong>Cantidad:</strong> $cantidad</p>";
    echo "<p><strong>Fecha:</strong> $fecha_actual</p>";
    echo "<button onclick='window.print()'>Imprimir</button>";
    echo "</div>";
    echo "</div>";
} else {
    // Si no se han recibido datos del formulario, mostrar el formulario
    ?>
    <div style="text-align: center;">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario"><br>
        
        <label for="dni_cliente">DNI Cliente:</label><br>
        <input type="text" id="dni_cliente" name="dni_cliente"><br>
        
        <label for="nombre_cliente">Nombre Cliente:</label><br>
        <input type="text" id="nombre_cliente" name="nombre_cliente"><br>
        
        <label for="telefono_cliente">Teléfono Cliente:</label><br>
        <input type="text" id="telefono_cliente" name="telefono_cliente"><br>
        
        <label for="correo_cliente">Correo Cliente:</label><br>
        <input type="text" id="correo_cliente" name="correo_cliente"><br>
        
        <label for="producto">Producto:</label><br>
        <select id="producto" name="producto">
            <option value="1">Producto 1</option>
            <option value="2">Producto 2</option>
            <!-- Agrega más opciones según tus productos -->
        </select><br>
        
        <label for="cantidad">Cantidad:</label><br>
        <input type="number" id="cantidad" name="cantidad"><br><br>
        
        <input type="submit" value="Enviar">
    </form>
    </div>
    <?php
}
?>


