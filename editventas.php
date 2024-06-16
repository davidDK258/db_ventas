<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_ventas";
$port = 3310;

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Verificar si 'editar_id' está presente en la URL
if (isset($_GET['editar_id']) && !empty($_GET['editar_id'])) {
    $editar_id = $_GET['editar_id'];

    // Obtener los detalles de la venta a editar
    $sql = "SELECT v.id, v.usuario_id, v.dni_cliente, v.producto_id, hv.cantidad
            FROM ventas v
            JOIN historial_ventas hv ON v.id = hv.venta_id
            WHERE v.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $editar_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $venta = $result->fetch_assoc();
    } else {
        die("No se encontró la venta.");
    }

    // Obtener la lista de usuarios para el dropdown
    $sql_usuarios = "SELECT id, nombre_completo FROM usuarios";
    $result_usuarios = $conn->query($sql_usuarios);

    // Obtener la lista de productos para el dropdown
    $sql_productos = "SELECT id, nombre, precio FROM productos";
    $result_productos = $conn->query($sql_productos);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Procesar los datos del formulario
        $usuario_id = $_POST["usuario"];
        $dni_cliente = $_POST["dni_cliente"];
        $producto_id = $_POST["producto"];
        $cantidad = $_POST["cantidad"];

        // Obtener la cantidad actual del historial de ventas
        $cantidad_actual_query = "SELECT cantidad FROM historial_ventas WHERE venta_id=?";
        $stmt_cantidad_actual = $conn->prepare($cantidad_actual_query);
        $stmt_cantidad_actual->bind_param('i', $editar_id);
        $stmt_cantidad_actual->execute();
        $result_cantidad_actual = $stmt_cantidad_actual->get_result();
        $cantidad_actual = $result_cantidad_actual->fetch_assoc()['cantidad'];

        // Verificar si hay suficiente stock del producto
        $sql_stock = "SELECT cantidad_disponible FROM productos WHERE id = ?";
        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->bind_param('i', $producto_id);
        $stmt_stock->execute();
        $result_stock = $stmt_stock->get_result();

        if ($result_stock->num_rows > 0) {
            $row_stock = $result_stock->fetch_assoc();
            $stock_disponible = $row_stock["cantidad_disponible"] + $cantidad_actual; // Incluyendo la cantidad actual en stock
            if ($cantidad <= $stock_disponible) {
                // Actualizar la tabla de ventas
                $sql_venta = "UPDATE ventas SET usuario_id=?, dni_cliente=?, producto_id=? WHERE id=?";
                $stmt_venta = $conn->prepare($sql_venta);
                $stmt_venta->bind_param('isii', $usuario_id, $dni_cliente, $producto_id, $editar_id);

                if ($stmt_venta->execute()) {
                    // Actualizar la tabla historial_ventas
                    $sql_historial = "UPDATE historial_ventas SET cantidad=? WHERE venta_id=?";
                    $stmt_historial = $conn->prepare($sql_historial);
                    $stmt_historial->bind_param('ii', $cantidad, $editar_id);

                    if ($stmt_historial->execute()) {
                        // Actualizar el stock del producto
                        $sql_actualizar_stock = "UPDATE productos SET cantidad_disponible = cantidad_disponible + ? - ? WHERE id = ?";
                        $stmt_actualizar_stock = $conn->prepare($sql_actualizar_stock);
                        $stmt_actualizar_stock->bind_param('iii', $cantidad_actual, $cantidad, $producto_id);

                        if ($stmt_actualizar_stock->execute()) {
                            // Calcular el total de la venta
                            $sql_precio = "SELECT precio FROM productos WHERE id = ?";
                            $stmt_precio = $conn->prepare($sql_precio);
                            $stmt_precio->bind_param('i', $producto_id);
                            $stmt_precio->execute();
                            $result_precio = $stmt_precio->get_result();
                            $precio_producto = $result_precio->fetch_assoc()['precio'];
                            $total_venta = $precio_producto * $cantidad;

                            // Registrar el total en la tabla ventas
                            $sql_total_venta = "UPDATE ventas SET precio_compra=? WHERE id=?";
                            $stmt_total_venta = $conn->prepare($sql_total_venta);
                            $stmt_total_venta->bind_param('di', $total_venta, $editar_id);

                            if ($stmt_total_venta->execute()) {
                                echo "<script>alert('La venta se actualizó correctamente.'); window.location.href = 'ventasE.php';</script>";
                            } else {
                                echo "Error al actualizar el total de la venta: " . $conn->error;
                            }
                        } else {
                            echo "Error al actualizar el stock: " . $conn->error;
                        }
                    } else {
                        echo "Error: " . $sql_historial . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql_venta . "<br>" . $conn->error;
                }
            } else {
                echo "<script>alert('No hay suficiente stock disponible para esta cantidad.');</script>";
            }
        } else {
            echo "Error al verificar el stock del producto.";
        }
    }
} else {
    die("No se especificó una venta a editar.");
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>
    <style>
        /* Estilos CSS aquí */
        form {
            max-width: 400px;
            margin: auto;
            padding: 1em;
            border: 1px solid #ccc;
            border-radius: 1em;
        }
        div + div {
            margin-top: 1em;
        }
        label {
            display: block;
            margin-bottom: 0.5em;
        }
        input, select, button {
            width: 100%;
            padding: 0.5em;
            font-size: 1em;
        }
        button {
            margin-top: 1em;
            padding: 0.7em;
        }
    </style>
</head>
<body>
    <h2>Editar Venta</h2>
    <form method="post">
        <div>
            <label for="usuario">Usuario:</label>
            <select id="usuario" name="usuario" required>
                <?php
                if ($result_usuarios->num_rows > 0) {
                    while ($row = $result_usuarios->fetch_assoc()) {
                        $selected = ($row["id"] == $venta["usuario_id"]) ? "selected" : "";
                        echo "<option value='" . $row["id"] . "' $selected>" . $row["nombre_completo"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label for="dni_cliente">DNI Cliente:</label>
            <input type="text" id="dni_cliente" name="dni_cliente" value="<?php echo $venta['dni_cliente']; ?>" required>
        </div>
        <div>
            <label for="producto">Producto:</label>
            <select id="producto" name="producto" required>
                <?php
                if ($result_productos->num_rows > 0) {
                    while ($row = $result_productos->fetch_assoc()) {
                        $selected = ($row["id"] == $venta["producto_id"]) ? "selected" : "";
                        echo "<option value='" . $row["id"] . "' $selected>" . $row["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" value="<?php echo $venta['cantidad']; ?>" required>
        </div>
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>

