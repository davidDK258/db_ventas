<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_ventas";
$port = 3310; // Puerto específico

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Verificar si se ha enviado el ID del usuario a eliminar
if(isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Consulta para eliminar el usuario de la base de datos
    $sql_delete = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        // Usuario eliminado con éxito.
        echo "success";
    } else {
        // Error al eliminar el usuario.
        echo "Error al eliminar el usuario: " . $conn->error;
    }
} else {
    // No se ha enviado el ID del usuario a eliminar.
    echo "No se ha proporcionado el ID del usuario a eliminar.";
}

$conn->close();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.delete-user').on('click', function(){
            var userId = $(this).data('id');
            if(confirm("¿Estás seguro de que deseas eliminar este usuario?")){
                $.ajax({
                    type: 'POST',
                    url: 'nombre_de_tu_archivo.php', // Reemplaza 'nombre_de_tu_archivo.php' por el nombre real de tu archivo PHP
                    data: { id: userId },
                    success: function(data){
                        if(data === 'success'){
                            // Si la eliminación fue exitosa, eliminar la fila de la tabla.
                            $('#usuario-' + userId).remove();
                        } else {
                            // Si hubo un error, mostrar un mensaje de error.
                            alert(data);
                        }
                    }
                });
            }
        });
    });
</script>


