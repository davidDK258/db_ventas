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

// Definir el valor por defecto del rut
$rut_por_defecto = "000050505005";

// Obtener la lista de usuarios para el dropdown
$sql_usuarios = "SELECT id, nombre_completo FROM usuarios";
$result_usuarios = $conn->query($sql_usuarios);

// Consulta SQL para obtener la lista de productos con el nombre del proveedor y su empresa
$sql_productos = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.cantidad_disponible, pr.nombre_proveedor, pr.nombre_empresa, pr.id as proveedor_id
                  FROM productos p
                  INNER JOIN proveedores pr ON p.proveedor_id = pr.id";
$result_productos = $conn->query($sql_productos);

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar los datos del formulario
    $usuario_id = $_POST["usuario"];
    $dni_cliente = $_POST["dni_cliente"];
    $nombre_cliente = $_POST["nombre_cliente"];
    $telefono_cliente = $_POST["telefono_cliente"];
    $correo_cliente = $_POST["correo_cliente"];
    $producto_id = $_POST["producto"];
    $cantidad = $_POST["cantidad"];

    // Verificar si el cliente ya existe en la base de datos
    $sql_cliente_existente = "SELECT dni FROM clientes WHERE dni = '$dni_cliente'";
    $result_cliente_existente = $conn->query($sql_cliente_existente);

    if ($result_cliente_existente->num_rows == 0) {
        // Insertar nuevo cliente en la tabla de clientes
        $sql_insert_cliente = "INSERT INTO clientes (dni, nombre_completo, correo, telefono) VALUES ('$dni_cliente', '$nombre_cliente', '$correo_cliente', '$telefono_cliente')";
        if (!$conn->query($sql_insert_cliente)) {
            echo "Error al insertar cliente: " . $conn->error;
            exit;
        }
    }

    // Verificar si hay suficiente stock del producto
    $sql_verificar_stock = "SELECT cantidad_disponible, precio FROM productos WHERE id = '$producto_id'";
    $result_verificar_stock = $conn->query($sql_verificar_stock);
    if ($result_verificar_stock->num_rows > 0) {
        $row_stock = $result_verificar_stock->fetch_assoc();
        if ($row_stock["cantidad_disponible"] < $cantidad) {
            $error_message = "Producto no disponible. Cantidad solicitada excede el stock disponible.";
        } else {
            // Calcular el total de la venta
            $total_venta = $cantidad * $row_stock["precio"];

            // Insertar la venta en la tabla de ventas
            $rut_empresa = $rut_por_defecto;  // Asignar el valor por defecto de rut_empresa
            $sql_venta = "INSERT INTO ventas (usuario_id, dni_cliente, producto_id, rut_empresa, precio_compra, total_venta) 
                          VALUES ('$usuario_id', '$dni_cliente', '$producto_id', '$rut_empresa', '{$row_stock["precio"]}', '$total_venta')";
            if ($conn->query($sql_venta) === TRUE) {
                $venta_id = $conn->insert_id;

                // Insertar la venta en el historial de ventas
                $sql_historial = "INSERT INTO historial_ventas (venta_id, cantidad) VALUES ('$venta_id', '$cantidad')";
                if ($conn->query($sql_historial) === TRUE) {
                    // Actualizar el stock disponible del producto vendido
                    $sql_actualizar_stock = "UPDATE productos SET cantidad_disponible = cantidad_disponible - $cantidad WHERE id = '$producto_id'";
                    if ($conn->query($sql_actualizar_stock) === TRUE) {
                        $error_message = "Venta registrada exitosamente.";
                    } else {
                        $error_message = "Error al actualizar el stock: " . $conn->error;
                    }
                } else {
                    $error_message = "Error: " . $sql_historial . "<br>" . $conn->error;
                }
            } else {
                $error_message = "Error: " . $sql_venta . "<br>" . $conn->error;
            }
        }
    } else {
        $error_message = "Error: Producto no encontrado.";
    }
}

// Cerrar la conexión
$conn->close();
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="estilo4.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrar Venta</title>
        <script>
            function actualizarProveedor() {
                var productoSelect = document.getElementById("producto");
                var proveedorId = document.getElementById("proveedor_id");
                var nombreProveedor = document.getElementById("nombre_proveedor");
                var nombreEmpresa = document.getElementById("nombre_empresa");
                var selectedOption = productoSelect.options[productoSelect.selectedIndex];

                proveedorId.value = selectedOption.getAttribute("data-proveedor-id");
                nombreProveedor.value = selectedOption.getAttribute("data-proveedor-nombre");
                nombreEmpresa.value = selectedOption.getAttribute("data-empresa-nombre");
            }
        </script>
    </head>
    <body>
        <h2>Registrar Venta</h2>
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" value="<?php echo $rut_por_defecto; ?>" readonly><br><br>
            
            <label for="usuario">Usuario:</label>
            <select id="usuario" name="usuario">
                <?php
                if ($result_usuarios->num_rows > 0) {
                    while ($row = $result_usuarios->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'>" . $row["nombre_completo"] . "</option>";
                    }
                }
                ?>
            </select><br><br>
            
            <label for="dni_cliente">DNI Cliente:</label>
            <input type="text" id="dni_cliente" name="dni_cliente" required><br><br>
            
            <label for="nombre_cliente">Nombre Cliente:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" required><br><br>
            
            <label for="telefono_cliente">Teléfono Cliente:</label>
            <input type="text" id="telefono_cliente" name="telefono_cliente" required><br><br>
            
            <label for="correo_cliente">Correo Cliente:</label>
            <input type="email" id="correo_cliente" name="correo_cliente" required><br><br>
            
            <label for="producto">Producto:</label>
            <select id="producto" name="producto" onchange="actualizarProveedor()">
                <?php
                if ($result_productos->num_rows > 0) {
                    while ($row = $result_productos->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "' data-proveedor-id='" . $row["proveedor_id"] . "' data-empresa-nombre='" . $row["nombre_empresa"] . "' data-proveedor-nombre='" . $row["nombre_proveedor"] . "'>" . $row["nombre"] . "</option>";
                    }
                }
                ?>
            </select><br><br>
            
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" required><br><br>
            
            <!-- Campos ocultos para almacenar información del proveedor -->
            <input type="hidden" id="nombre_proveedor" name="nombre_proveedor">
            <input type="hidden" id="nombre_empresa" name="nombre_empresa">
            <input type="hidden" id="proveedor_id" name="proveedor_id">
            
            <button type="submit">Registrar Venta</button>
        </form>
        <form  method="post" action="imprimir.php">
            <input type="hidden" id="venta_id" name="venta_id" value="<?php echo $venta_id; ?>">
            <input type="submit" value="Imprimir">
        </form>

        <form action="interfaz.php">
        <button type="submit">salir</button>
        </form>

    </body>
    </html>


