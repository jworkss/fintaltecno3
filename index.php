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
    <script>
    // Pasamos el rol del usuario desde PHP a JavaScript
    const ROL_USUARIO = "<?php echo $_SESSION['rol']; ?>";

    // Solo aplicamos el cierre automático si NO es administrador
    if (ROL_USUARIO !== 'admin') {
        let tiempoInactividad = 20000; // 20 segundos en milisegundos
        let temporizador;

        const reiniciarTemporizador = () => {
            clearTimeout(temporizador);
            // Si pasan 20 segundos sin acción, redirige a logout.php
            temporizador = setTimeout(() => {
                alert("Tu sesión ha expirado por inactividad (20 segundos).");
                window.location.href = "logout.php";
            }, tiempoInactividad);
        };

        // Escuchar movimientos del mouse o pulsaciones de teclado del usuario
        window.onload = reiniciarTemporizador;
        window.onmousemove = reiniciarTemporizador;
        window.onmousedown = reiniciarTemporizador; 
        window.ontouchstart = reiniciarTemporizador;
        window.onclick = reiniciarTemporizador;     
        window.onkeydown = reiniciarTemporizador;   
    }
</script>
 <script src="https://cdn.jsdelivr.net/npm/p5@2.3.0/lib/p5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/p5.sound@0.3.0/dist/p5.sound.min.js"></script>
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