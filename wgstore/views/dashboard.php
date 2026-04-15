<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

// Consulta todos los productos disponibles
$productos = $conexion->query("SELECT * FROM productos")->fetchAll(PDO::FETCH_ASSOC);
// Consulta las compras del usuario usando JOIN
$stmt = $conexion->prepare("
    SELECT p.nombre_producto, p.precio 
    FROM compras c
    JOIN productos p ON c.producto_id = p.id 
    WHERE c.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta los productos favoritos del usuario
$stmt = $conexion->prepare("
    SELECT p.nombre_producto, p.precio 
    FROM favoritos f
    JOIN productos p ON f.producto_id = p.id 
    WHERE f.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Productos relacionados (aleatorios)
$relacionados = $conexion->query("
    SELECT * FROM productos ORDER BY RAND() LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

//Categoria//
$categoria = $productos[0]["categoria"] ?? '';

$stmt = $conexion->prepare("
    SELECT * FROM productos 
    WHERE categoria = ? 
    ORDER BY RAND() 
    LIMIT 6
");
$stmt->execute([$categoria]);
$relacionados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tienda</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <h1>🛒 WG Store</h1>
    <p>Bienvenid@ <?php echo $_SESSION["usuario"]; ?></p>
    <a class="btn btn-logout" href="../auth/logout.php">Cerrar sesión</a>
</header>

<div class="container">

    <h2>Productos</h2>
    <div class="productos">
        <?php foreach ($productos as $p): ?>
            <div class="card">
                <img src="../img/<?php echo $p["imagen"]; ?>" class="img-producto">
                <h3><?php echo $p["nombre_producto"]; ?></h3>
                <p><?php echo $p["descripcion"]; ?></p>
                <p class="precio">$<?php echo $p["precio"]; ?></p>

                <a class="btn btn-comprar" href="compras.php?id=<?php echo $p["id"]; ?>">Comprar</a>
                <a class="btn btn-fav" href="favoritos.php?id=<?php echo $p["id"]; ?>">❤️</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <h2>🧾 Tus compras</h2>
        <div class="lista">
            <?php foreach ($compras as $c): ?>
                <p><?php echo $c["nombre_producto"]; ?> - $<?php echo $c["precio"]; ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <h2>❤️ Favoritos</h2>
        <div class="lista">
            <?php foreach ($favoritos as $f): ?>
                <p><?php echo $f["nombre_producto"]; ?> - $<?php echo $f["precio"]; ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
    <h2>🔥 Más productos relacionados</h2>

    <div class="slider">
        <?php foreach ($relacionados as $r): ?>
            <div class="card">
                <img src="../img/<?php echo $r["imagen"]; ?>" class="img-producto">

                <h3><?php echo $r["nombre_producto"]; ?></h3>
                <p class="precio">$<?php echo $r["precio"]; ?></p>

                <a class="btn btn-comprar" href="comprar.php?id=<?php echo $r["id"]; ?>">Comprar</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</div>

</body>
</html>