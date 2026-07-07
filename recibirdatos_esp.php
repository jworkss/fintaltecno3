<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexion.php";

if (!isset($_GET["usuario_id"]) || !isset($_GET["pulsaciones"]) || !isset($_GET["oxigeno"])) {
    echo json_encode(["ok" => false, "mensaje" => "Faltan datos obligatorios"]);
    exit();
}

$usuario_id = (int)$_GET["usuario_id"];
$pulsaciones = (int)$_GET["pulsaciones"];
$oxigeno = (int)$_GET["oxigeno"];

$sql = "INSERT INTO registros_oximetro (usuario_id, pulsaciones, oxigeno) VALUES ($usuario_id, $pulsaciones, $oxigeno)";

if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "Lectura guardada",
        "usuario_id" => $usuario_id,
        "pulsaciones" => $pulsaciones,
        "oxigeno" => $oxigeno
    ]);
} else {
    echo json_encode(["ok" => false, "mensaje" => "Error de inserción"]);
}

mysqli_close($conexion);
?>