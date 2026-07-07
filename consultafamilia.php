<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexion.php";

if (!isset($_GET["usuario_id"])) {
    echo json_encode(["ok" => false, "mensaje" => "Falta ID de usuario de referencia"]);
    exit();
}

$usuario_id = (int)$_GET["usuario_id"];

// 1. SELECT: Encontrar cuál es el familia_id del usuario logueado
$consulta_fam = "SELECT familia_id FROM usuarios WHERE id = $usuario_id LIMIT 1";
$res_fam = mysqli_query($conexion, $consulta_fam);
$fila_fam = mysqli_fetch_assoc($res_fam);
$familia_id = $fila_fam['familia_id'];

if (!$familia_id) {
    // Si no tiene familia, solo nos traemos sus propios datos
    $condicion = "u.id = $usuario_id";
} else {
    // Si tiene, traemos las lecturas de CUALQUIER miembro de esa familia
    $condicion = "u.family_id = $familia_id";
}

// 2. Consulta avanzada cruzando los últimos registros de cada miembro
$consulta_datos = "SELECT r.usuario_id, u.nombre, u.familia_id, r.pulsaciones, r.oxigeno, r.fecha 
                   FROM registros_oximetro r
                   INNER JOIN usuarios u ON r.usuario_id = u.id
                   WHERE $condicion AND r.id IN (SELECT MAX(id) FROM registros_oximetro GROUP BY usuario_id)";

$resultado = mysqli_query($conexion, $consulta_datos);
$datos_grupo = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos_grupo[] = [
        "usuario_id" => (int)$fila["usuario_id"],
        "nombre" => $fila["nombre"],
        "familia_id" => (int)$fila["familia_id"],
        "pulsaciones" => (int)$fila["pulsaciones"],
        "oxigeno" => (int)$fila["oxigeno"]
    ];
}

echo json_encode([
    "ok" => true,
    "familia_id" => (int)$familia_id,
    "datos" => $datos_grupo
]);

mysqli_close($conexion);
?>