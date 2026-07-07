<?php
session_start();

if (isset($_GET['vista']) && $_GET['vista'] === 'publica') {
    session_destroy();
    header("Location: index.php");
    exit();
}

$user_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : "Invitado";
$rol_usuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Monitoreo - Sistema Pulsaciones</title>
    <link rel="stylesheet" href="panel.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>

<header>
    <?php if($user_id > 0 && $rol_usuario !== 'admin'): ?>
        <h1>Panel de tu Grupo Familiar (Hola, <?php echo $nombre_usuario; ?>)</h1>
    <?php else: ?>
        <h1>Visualización General del Sistema (Invitado)</h1>
    <?php endif; ?>
    
    <div class="info-perfil">
        <?php if($user_id === 0): ?>
            <a href="login.html" class="btn-nav btn-azul">Iniciar Sesión</a>
            <a href="registro.html" class="btn-nav btn-verde">Crear Cuenta</a>
        <?php else: ?>
            <a href="index.php?vista=publica" class="btn-nav btn-azul">Volver al Index General</a>
            <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
        <?php endif; ?>
    </div>
    
</header>

<main style="padding: 0; margin: 0; overflow: hidden; background-color: #05050a;">
    <iframe 
        src="p5/empty-example/index.html?usuario_id=<?php echo $user_id; ?>" 
        style="width: 100vw; height: calc(100vh - 75px); border: none; display: block;"
        scrolling="no">
    </iframe>
</main>

</body>
</html>