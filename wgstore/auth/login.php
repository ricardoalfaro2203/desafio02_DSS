<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$email]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {
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

        <a class="link" href="register.php">Crear cuenta</a>
    </div>
</div>

</body>
</html>