<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "pulsaciones"; 

$conexion = mysqli_connect($servidor, $usuario, $clave, $base_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
?>