<?php
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

// Inicializar variable de fecha
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Construir la consulta SQL con filtro de fecha
$sql_ventas = "SELECT v.id AS venta_id, u.nombre_completo AS nombre_usuario, c.dni AS dni_cliente, c.nombre_completo AS nombre_cliente, 
               p.nombre AS nombre_producto, h.cantidad AS cantidad_vendida, v.total_venta
               FROM ventas v
               INNER JOIN usuarios u ON v.usuario_id = u.id
               INNER JOIN clientes c ON v.dni_cliente = c.dni
               INNER JOIN historial_ventas h ON v.id = h.venta_id
               INNER JOIN productos p ON v.producto_id = p.id";

// Añadir condición de fecha si se especifica
if (!empty($fecha)) {
    $sql_ventas .= " WHERE DATE(v.fecha_venta) = '$fecha'";
}

$result_ventas = $conn->query($sql_ventas);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Realizadas</title>
</head>
<body>
    <h2>Ventas Realizadas</h2>

    <form method="GET" action="">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        <input type="submit" value="Buscar">
    </form>

    <table border="1">
        <tr>
            <th>ID de Venta</th>
            <th>Usuario</th>
            <th>DNI Cliente</th>
            <th>Nombre Cliente</th>
            <th>Producto</th>
            <th>Cantidad Vendida</th>
            <th>Total Venta</th>
        </tr>
        <?php
        if ($result_ventas->num_rows > 0) {
            while ($row = $result_ventas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["venta_id"] . "</td>";
                echo "<td>" . $row["nombre_usuario"] . "</td>";
                echo "<td>" . $row["dni_cliente"] . "</td>";
                echo "<td>" . $row["nombre_cliente"] . "</td>";
                echo "<td>" . $row["nombre_producto"] . "</td>";
                echo "<td>" . $row["cantidad_vendida"] . "</td>";
                echo "<td>" . $row["total_venta"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No hay ventas registradas.</td></tr>";
        }
        ?>
    </table>

    <form action="interfaz.php">
        <input type="submit" value="Salir">
    </form>
</body>
</html>

