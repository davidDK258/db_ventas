<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'david' && $password === '123456') {
        $_SESSION['username'] = $username;
        header('Location: usuarios.php');
        exit();
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Beto</title>
    <link rel="stylesheet" href="estilo1.css">
</head>
<body>
    <h1>easy cash</h1>

    <div class="main-container">
        <div class="button-container">
            <a href="#" class="btn-neon" id="loginLink">Usuarios
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="ventas.php" class="btn-neon">Ingresar Ventas
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="ventasE.php" class="btn-neon">ventas totales
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="ventashoy.php" class="btn-neon">Ventas diarias
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="registropro.php" class="btn-neon">registar producto
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="almacen.php" class="btn-neon">Almacén
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="estado-almacen.php" class="btn-neon">Estado de Almacén
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>
            <a href="provedores.php" class="btn-neon">Proveedores
                <span id="span1"></span>
                <span id="span2"></span>
                <span id="span3"></span>
                <span id="span4"></span>
            </a>

            <div>
                <a href="login.php?logout" class="btn-eliminar">Salir</a>
            </div>
        </div>

        <div class="login-container" id="loginForm" style="display: <?php echo isset($error) ? 'flex' : 'none'; ?>;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required><br><br>
                <?php if(isset($error)) { ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php } ?>
                <input type="submit" value="Iniciar Sesión">
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginLink').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('loginForm').style.display = 'flex';
        });
    </script>
    <div class="contenedor">
        <div class="caja">
            <div class="cara cara1">
                <img src="imagen/c1.png">
            </div>
            <div class="cara cara2">
                <img src="imagen/c1.png">
            </div>
            <div class="cara cara3">
                <img src="imagen/c1.png">
            </div>
            <div class="cara cara4">
                <img src="imagen/c1.png">
            </div>
            <div class="cara cara5">
                <img src="imagen/c1.png">
            </div>
            <div class="cara cara6">
                <img src="imagen/c1.png">
            </div>
        </div>
    </div>
    

</body>
</html>


