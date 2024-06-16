<?php
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

// Consultar los productos con sus cantidades disponibles
$sql = "SELECT p.nombre AS producto, p.cantidad_total, p.cantidad_disponible
        FROM productos p";
$result = $conn->query($sql);

// Arrays para almacenar nombres de productos y porcentajes de disponibilidad
$productos = array();
$porcentajes_disponibilidad = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = $row["producto"];
        $porcentaje = ($row["cantidad_disponible"] / $row["cantidad_total"]) * 100;
        $porcentajes_disponibilidad[] = $porcentaje;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Estado del Almacén</title>
    <!-- Incluir la biblioteca Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>

<h2>Estado del Almacén</h2>

<!-- Contenedor para la gráfica de barras -->
<div style="width: 800px; height: 400px;">
    <canvas id="chart"></canvas>
</div>

<script>
// Obtener datos de PHP
var productos = <?php echo json_encode($productos); ?>;
var porcentajes_disponibilidad = <?php echo json_encode($porcentajes_disponibilidad); ?>;

// Configurar datos de la gráfica
var data = {
    labels: productos,
    datasets: [{
        label: 'Porcentaje de Disponibilidad',
        data: porcentajes_disponibilidad,
        backgroundColor: 'rgba(54, 162, 235, 0.5)', // Color de fondo de las barras
        borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
        borderWidth: 1
    }]
};

// Configurar opciones de la gráfica
var options = {
    scales: {
        y: {
            beginAtZero: true, // Comenzar el eje Y desde 0
            title: {
                display: true,
                text: 'Porcentaje de Disponibilidad (%)'
            }
        }
    }
};

// Crear la gráfica de barras
var ctx = document.getElementById('chart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
});
</script>
<form action="interfaz.php">
        <input type="submit" value="Salir">
      </form>
</body>
</html>

