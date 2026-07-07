<?php
header("Content-Type: application/json; charset=utf-8");
require_once "conexion.php";

if (!isset($_GET["mac"])) {
    echo json_encode(["ok" => false, "mensaje" => "Falta la direccion MAC"]);
    exit();
}

$mac = mysqli_real_escape_string($conexion, trim($_GET["mac"]));

$consulta = "SELECT id FROM usuarios WHERE esp_mac = '$mac' LIMIT 1";
$resultado = mysqli_query($conexion, $consulta);

if ($resultado && mysqli_num_rows($resultado) === 1) {
    $usuario = mysqli_fetch_assoc($resultado);
    echo json_encode([
        "vinculado" => true,
        "usuario_id" => (int)$usuario["id"]
    ]);
} else {
    echo json_encode([
        "vinculado" => false,
        "mensaje" => "Dispositivo no vinculado a ningun usuario"
    ]);
}

mysqli_close($conexion);
?>