<?php
session_start();
require_once "../config/db.php";

$usuario_id = $_SESSION["usuario_id"];
$producto_id = $_GET["id"];

$stmt = $conexion->prepare("INSERT INTO favoritos (usuario_id, producto_id) VALUES (?, ?)");
$stmt->execute([$usuario_id, $producto_id]);

header("Location: dashboard.php");