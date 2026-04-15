<?php
// Inicia la sesión para poder almacenar datos del usuario autenticado
session_start();
// Importa la conexión a la base de datos usando PDO
require_once "../config/db.php";

// Verifica si el formulario fue enviado por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura los datos enviados desde el formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$email]);

     // Obtiene el usuario en forma de arreglo
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // este if verifica si el usuario existe y si la contraseña ingresada coincide con la almacenada en la base de datos (usando password_verify para comparar el hash)
    if ($usuario && password_verify($password, $usuario["password"])) {
        // Guarda datos en sesión (usuario autenticado)
        $_SESSION["usuario"] = $usuario["nombre_user"];
        $_SESSION["usuario_id"] = $usuario["id"];

        header("Location: ../views/dashboard.php");
        exit();
    } else {
        header("Location: login.php?error=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <h2>Iniciar Sesión</h2>

        <?php if(isset($_GET["error"])): ?>
            <p class="error">Correo o contraseña incorrectos</p>
        <?php endif; ?>

        <?php if(isset($_GET["success"])): ?>
            <p class="success">Registro exitoso</p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <a class="link" href="registro_usuarios.php">Crear cuenta</a>
    </div>
</div>

</body>
</html>