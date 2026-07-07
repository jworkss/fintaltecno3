<?php
session_start();
if (!isset($_SESSION['usuario_id'])) { header("Location: login.html"); exit(); }

require_once "conexion.php"; 
$user_id = $_SESSION['usuario_id'];
$consulta = "SELECT u.nombre, u.apellido, f.codigo_familiar 
             FROM usuarios u 
             LEFT JOIN familias f ON u.familia_id = f.id 
             WHERE u.id = $user_id";

$resultado = mysqli_query($conexion, $consulta); 
$datos_usuario = mysqli_fetch_assoc($resultado); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Monitoreo</title>
    <link rel="stylesheet" href="panel.css">
</head>
<body>
<header>
    <h1>Panel de <?php echo $datos_usuario['nombre'] . " " . $datos_usuario['apellido']; ?></h1>
    <div class="info-perfil">
        <div class="codigo-badge">Código Familiar: <?php echo $datos_usuario['codigo_familiar'] ?? 'Sin grupo'; ?></div>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>
</header>
<main id="contenedor"></main>
<script> const ID_USUARIO_LOGUEADO = <?php echo $user_id; ?>; </script>
<script src="https://cdn.jsdelivr.net/npm/p5@1.9.4/lib/p5.min.js"></script>
<script src="sketch.js"></script>
</body>
</html>