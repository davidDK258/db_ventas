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

// Consulta para obtener las ventas del día actual
$fecha_actual = date("Y-m-d");
$sql_ventas_diarias = "SELECT usuarios.nombre_completo, COUNT(ventas.id) AS total_ventas, SUM(historial_ventas.cantidad) AS total_productos, SUM(ventas.precio_compra * historial_ventas.cantidad) AS monto 
                        FROM ventas 
                        INNER JOIN usuarios ON ventas.usuario_id = usuarios.id 
                        INNER JOIN historial_ventas ON ventas.id = historial_ventas.venta_id
                        WHERE DATE(ventas.fecha_venta) = CURDATE() 
                        GROUP BY usuarios.id";

$result_ventas_diarias = $conn->query($sql_ventas_diarias);

// Mostrar las ventas del día actual
echo "<h2>Ventas del día " . $fecha_actual . "</h2>";
if ($result_ventas_diarias->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Usuario</th>
                <th>Total de Ventas</th>
                <th>Total de Productos</th>
                <th>Monto Total</th>
            </tr>";
    while ($row = $result_ventas_diarias->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["nombre_completo"] . "</td>
                <td>" . $row["total_ventas"] . "</td>
                <td>" . $row["total_productos"] . "</td>
                <td>$" . $row["monto"] . "</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "No hay ventas registradas hoy.";
}

// Consulta para obtener el total de ventas, productos y monto de todos los usuarios
$sql_total_ventas = "SELECT COUNT(ventas.id) AS total_ventas, SUM(historial_ventas.cantidad) AS total_productos, SUM(ventas.precio_compra * historial_ventas.cantidad) AS monto 
                     FROM ventas 
                     INNER JOIN historial_ventas ON ventas.id = historial_ventas.venta_id
                     WHERE DATE(ventas.fecha_venta) = CURDATE()";

$result_total_ventas = $conn->query($sql_total_ventas);

// Mostrar el total de ventas del día
if ($result_total_ventas->num_rows > 0) {
    echo "<h2>Resumen de Ventas del Día</h2>";
    while ($row = $result_total_ventas->fetch_assoc()) {
        echo "<p>Total de Ventas: " . $row["total_ventas"] . "</p>";
        echo "<p>Total de Productos Vendidos: " . $row["total_productos"] . "</p>";
        echo "<p>Monto Total de Ventas: $" . $row["monto"] . "</p>";
    }
} else {
    echo "No hay ventas registradas hoy.";
}

// Cerrar la conexión
$conn->close();
?>
<?php
// Agregar el botón de salir
echo '<form action="interfaz.php">
        <input type="submit" value="Salir">
      </form>';
?>