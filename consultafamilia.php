<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexion.php";

$usuario_id = isset($_GET["usuario_id"]) ? (int)$_GET["usuario_id"] : 0;

if ($usuario_id === 0) {
    $condicion = "1=1";
} else {
    $consulta_fam = "SELECT familia_id FROM usuarios WHERE id = $usuario_id LIMIT 1";
    $res_fam = mysqli_query($conexion, $consulta_fam);
    $fila_fam = mysqli_fetch_assoc($res_fam);
    $familia_id = $fila_fam['familia_id'];

    if (!$familia_id) {
        $condicion = "u.id = $usuario_id";
    } else {
        $condicion = "u.familia_id = $familia_id";
    }
}

$consulta_datos = "SELECT r.usuario_id, u.nombre, u.apellido, u.familia_id, u.edad, r.pulsaciones, r.oxigeno, r.fecha 
                   FROM registros_oximetro r
                   INNER JOIN usuarios u ON r.usuario_id = u.id
                   WHERE $condicion
                   ORDER BY r.id ASC";

$resultado = mysqli_query($conexion, $consulta_datos);
$historial = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $historial[] = [
        "usuario_id" => (int)$fila["usuario_id"],
        "nombre" => $fila["nombre"] . " " . $fila["apellido"],
        "familia_id" => (int)$fila["familia_id"],
        "edad" => (int)$fila["edad"],
        "pulsaciones" => (int)$fila["pulsaciones"],
        "oxigeno" => (int)$fila["oxigeno"],
        "fecha" => $fila["fecha"]
    ];
}

echo json_encode([
    "ok" => true,
    "familia_id" => isset($familia_id) ? (int)$familia_id : 0,
    "datos" => $historial
]);

mysqli_close($conexion);
?>