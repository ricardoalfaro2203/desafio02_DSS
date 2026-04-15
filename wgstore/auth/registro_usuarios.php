<?php
require_once "../config/db.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

     // Captura los datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];

    // Cifra la contraseña usando password_hash()
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre_user, email, password) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    try {

    // Ejecuta la consulta con los valores ingresados
        $stmt->execute([$nombre, $email, $password]);
        header("Location: login.php?success=1");
    } catch (PDOException $e) {
        echo "Error";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <h2>Crear Cuenta</h2>

        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>

        <a class="link" href="login.php">Ya tengo cuenta</a>
    </div>
</div>

</body>
</html>