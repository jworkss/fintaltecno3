<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { exit("Acceso denegado."); }

if (isset($_GET['usuario_id'])) {
    $usuario_id = (int)$_GET['usuario_id'];
    $pulsaciones = rand(65, 110); 
    $oxigeno = rand(95, 100); 

    header("Location: recibirdatos_esp.php?usuario_id=$usuario_id&pulsaciones=$pulsaciones&oxigeno=$oxigeno");
    exit();
} else {
    header("Location: admin.php");
    exit();
}
?>