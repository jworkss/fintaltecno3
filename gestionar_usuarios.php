<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}
require_once "conexion.php";

if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar_usuario') {
    $id_a_borrar = (int)$_POST['usuario_id'];
    
    if ($id_a_borrar === (int)$_SESSION['usuario_id']) {
        header("Location: gestionar_usuarios.php?msg=Error:+No+puedes+eliminar+tu+propia+cuenta");
        exit();
    }
    
    $delete = "DELETE FROM usuarios WHERE id = $id_a_borrar";
    if (mysqli_query($conexion, $delete)) {
        header("Location: gestionar_usuarios.php?msg=Usuario+y+sus+registros+eliminados+con+exito");
    } else {
        header("Location: gestionar_usuarios.php?msg=Error+al+eliminar+usuario");
    }
    exit();
}

if (isset($_POST['accion']) && $_POST['accion'] === 'modificar_usuario') {
    $id_u = (int)$_POST['usuario_id'];
    $nuevo_rol = mysqli_real_escape_string($conexion, $_POST['rol']);
    $nueva_pass = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
    $update = "UPDATE usuarios SET rol = '$nuevo_rol', contrasenia = '$nueva_pass' WHERE id = $id_u";
    mysqli_query($conexion, $update);
    header("Location: gestionar_usuarios.php?msg=Usuario+Actualizado");
    exit();
}

if (isset($_POST['accion']) && $_POST['accion'] === 'carga_manual') {
    $id_u = (int)$_POST['usuario_id'];
    $pulso = (int)$_POST['pulsaciones'];
    $oxigeno = (int)$_POST['oxigeno'];
    header("Location: recibirdatos_esp.php?usuario_id=$id_u&pulsaciones=$pulso&oxigeno=$oxigeno");
    exit();
}

$consulta = "SELECT id, nombre, apellido, correo, contrasenia, rol, edad FROM usuarios";
$resultado = mysqli_query($conexion, $consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Avanzada - Administrador</title>
    <link rel="stylesheet" href="panel.css">
    <style>
        .seccion-admin { background: #222; padding: 20px; border-radius: 8px; margin: 20px auto; max-width: 1100px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .form-mini { display: inline-block; background: #333; padding: 8px; border-radius: 4px; margin-top: 5px; }
        .form-mini input, .form-mini select { padding: 5px; background: #222; color: #fff; border: 1px solid #555; border-radius: 3px; }
        .form-mini button { padding: 5px 10px; background: #00ff88; border: none; font-weight: bold; cursor: pointer; border-radius: 3px; color: #111; }
        .btn-eliminar { background-color: #ff4444 !important; color: white !important; }
        .btn-eliminar:hover { background-color: #cc0000 !important; }
        .btn-enlace { display: inline-block; margin-bottom: 15px; padding: 10px; background: #333; color: #00ff88; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
<header>
    <h1>Gestión de Permisos y Control Destructivo</h1>
    <div class="info-perfil">
        <a href="admin.php" class="btn-salir" style="color: #00ff88; margin-right: 15px;">◄ Volver a Simulador</a>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>
</header>

<div class="seccion-admin">
    <a href="registro.html" class="btn-enlace">+ Crear Nuevo Usuario Desde la Web</a>
    
    <?php if(isset($_GET['msg'])): ?>
        <p style="color:#00ff88; background:#111; padding:10px; border-radius:4px;"><?php echo $_GET['msg']; ?></p>
    <?php endif; ?>
    
    <h2>Lista General de Usuarios</h2>
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Edad</th>
                <th>Modificar / Cambiar Rol</th>
                <th>Inyectar Registro</th>
                <th>Acción Crítica</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['nombre'] . " " . $user['apellido']; ?></td>
                <td><?php echo $user['correo']; ?></td>
                <td><?php echo $user['edad'] ?? 'N/A'; ?></td>
                
                <td>
                    <form action="gestionar_usuarios.php" method="POST" class="form-mini">
                        <input type="hidden" name="accion" value="modificar_usuario">
                        <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                        <input type="text" name="contrasenia" value="<?php echo $user['contrasenia']; ?>" style="width:70px;" required>
                        <select name="rol" style="width:85px;">
                            <option value="usuario" <?php if($user['rol']=='usuario') echo 'selected';?>>Usuario</option>
                            <option value="admin" <?php if($user['rol']=='admin') echo 'selected';?>>Admin</option>
                        </select>
                        <button type="submit">Guardar</button>
                    </form>
                </td>

                <td>
                    <form action="gestionar_usuarios.php" method="POST" class="form-mini">
                        <input type="hidden" name="accion" value="carga_manual">
                        <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                        <input type="number" name="pulsaciones" placeholder="BPM" style="width:55px;" required min="40" max="200">
                        <input type="number" name="oxigeno" placeholder="%SpO2" style="width:65px;" required min="70" max="100">
                        <button type="submit" style="background:#00e1ff;">Inyectar</button>
                    </form>
                </td>

                <td>
                    <form action="gestionar_usuarios.php" method="POST" style="margin:0;" onsubmit="return confirm('¿Estás seguro de que querés eliminar por completo a este usuario y todo su historial de oxímetro? Esta acción no se puede deshacer.');">
                        <input type="hidden" name="accion" value="eliminar_usuario">
                        <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>