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

// Consultar los productos con sus proveedores
$sql = "SELECT p.nombre AS producto, p.descripcion, p.precio, p.cantidad_total, p.cantidad_disponible, pr.nombre_empresa AS proveedor
        FROM productos p
        INNER JOIN proveedores pr ON p.proveedor_id = pr.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Lista de Productos</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Producto</th><th>Descripción</th><th>Precio Unitario</th><th>Cantidad Total</th><th>Cantidad Disponible</th><th>Proveedor</th></tr>";
    // Salida de datos de cada fila
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row["producto"]."</td>";
        echo "<td>".$row["descripcion"]."</td>";
        echo "<td>".$row["precio"]."</td>";
        echo "<td>".$row["cantidad_total"]."</td>";
        echo "<td>".$row["cantidad_disponible"]."</td>";
        echo "<td>".$row["proveedor"]."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No hay productos registrados.";
}
$conn->close();
?>
<form action="interfaz.php">
        <input type="submit" value="Salir">
      </form>
</body>
</html>