<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Detalles de la Venta</title>
</head>
<body>
    <h2>Detalles de la Venta</h2>
    <?php
    if (isset($_POST["venta_id"])) {
        $venta_id = $_POST["venta_id"];

        // Conexión a la base de datos
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

        // Consulta SQL para obtener los detalles de la venta
        $sql_venta_detalle = "SELECT v.*, p.nombre AS nombre_producto, p.precio AS precio_producto, p.cantidad_disponible AS cantidad_disponible_producto, h.cantidad AS cantidad_vendida
                              FROM ventas v
                              INNER JOIN productos p ON v.producto_id = p.id
                              INNER JOIN historial_ventas h ON v.id = h.venta_id
                              WHERE v.id = '$venta_id'";
        $result_venta_detalle = $conn->query($sql_venta_detalle);

        // Verificar si la consulta fue exitosa
        if ($result_venta_detalle->num_rows > 0) {
            // Recuperar los datos de la venta
            $venta = $result_venta_detalle->fetch_assoc();

            // Mostrar los detalles de la venta en la página
            echo "<p>ID de Venta: " . $venta_id . "</p>";
            echo "<p>ID de Usuario: " . $venta["usuario_id"] . "</p>";
            echo "<p>DNI Cliente: " . $venta["dni_cliente"] . "</p>";
            echo "<p>Producto: " . $venta["nombre_producto"] . "</p>";
            echo "<p>Cantidad Vendida: " . $venta["cantidad_vendida"] . "</p>"; // Mostrar cantidad vendida
            echo "<p>Precio Unitario: $" . $venta["precio_producto"] . "</p>"; // Mostrar precio unitario
            echo "<p>Monto Total: $" . ($venta["cantidad_vendida"] * $venta["precio_producto"]) . "</p>"; // Calcular y mostrar monto total
            echo "<p>Fecha de Venta: " . $venta["fecha_venta"] . "</p>"; // Mostrar fecha de venta
        } else {
            echo "No se encontraron detalles de la venta.";
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        echo "No se proporcionó un ID de venta válido.";
    }
    ?>
    <br>
    <button onclick="window.print()">Imprimir</button> <!-- Botón para imprimir la página -->
    <form action="interfaz.php">
        <input type="submit" value="Salir">
      </form>
</body>
</html>

