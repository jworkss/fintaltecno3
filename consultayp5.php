<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexion.php";

if (!isset($_GET["usuario_id"])) {
    echo json_encode(["ok" => false, "mensaje" => "Falta ID de usuario"]);
    exit();
}

$usuario_id = (int)$_GET["usuario_id"];

$consulta = "SELECT id, pulsaciones, oxigeno, fecha 
             FROM registros_oximetro 
             WHERE usuario_id = $usuario_id 
             ORDER BY id ASC 
             LIMIT 15";

$resultado = mysqli_query($conexion, $consulta);
$lecturas = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $lecturas[] = $fila;
}

echo json_encode([
    "ok" => true,
    "cantidad" => count($lecturas),
    "datos" => $lecturas
]);

mysqli_close($conexion);
?>