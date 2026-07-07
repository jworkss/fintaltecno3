<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}
require_once "conexion.php";
$consulta = "SELECT id, nombre, apellido, correo FROM usuarios WHERE rol != 'admin'";
$resultado = mysqli_query($conexion, $consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Simulador</title>
    <link rel="stylesheet" href="css/panel.css">
</head>
<body>
<header>
    <h1>Panel de Administración</h1>
    <div class="info-perfil">
        <span class="codigo-badge">ADMIN</span>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>
</header>
<div class="panel-admin-contenedor">
    <h2>Usuarios del Sistema</h2>
    <div class="panel-admin-contenedor">
    <h2>Usuarios del Sistema</h2>
    
    <a href="gestionar_usuarios.php" class="btn-accion btn-generar" style="text-decoration:none; display:inline-block; margin-bottom:15px; background:#00e1ff;">
        ⚙️ Ir a Gestión de Roles y Carga Manual
    </a>
    
    <p>Hacé clic en "Generar Muestra" para simular el envío de datos de un oxímetro...</p>
    <table class="tabla-usuarios">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Acción</th></tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['nombre'] . " " . $user['apellido']; ?></td>
                <td><?php echo $user['correo']; ?></td>
                <td>
                    <a href="simulador_esp.php?usuario_id=<?php echo $user['id']; ?>">
                        <button class="btn-accion btn-generar">Generar Muestra</button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>