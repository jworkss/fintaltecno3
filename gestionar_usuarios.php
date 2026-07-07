<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}
require_once "conexion.php";

// 1. LÓGICA: Si el admin decide cambiar un ROL o modificar datos de un usuario
if (isset($_POST['accion']) && $_POST['accion'] === 'modificar_usuario') {
    $id_u = (int)$_POST['usuario_id'];
    $nuevo_rol = mysqli_real_escape_string($conexion, $_POST['rol']);
    $nueva_pass = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
    
    $update = "UPDATE usuarios SET rol = '$nuevo_rol', contrasenia = '$nueva_pass' WHERE id = $id_u";
    mysqli_query($conexion, $update);
    header("Location: gestionar_usuarios.php?msg=Usuario+Actualizado");
    exit();
}

// 2. LÓGICA: Si el admin decide ingresar datos de oxímetro de forma MANUAL
if (isset($_POST['accion']) && $_POST['accion'] === 'carga_manual') {
    $id_u = (int)$_POST['usuario_id'];
    $pulso = (int)$_POST['pulsaciones'];
    $oxigeno = (int)$_POST['oxigeno'];
    
    header("Location: recibirdatos_esp.php?usuario_id=$id_u&pulsaciones=$pulso&oxigeno=$oxigeno");
    exit();
}

// Traer todos los usuarios de la base para listarlos
$consulta = "SELECT id, nombre, apellido, correo, contrasenia, rol FROM usuarios";
$resultado = mysqli_query($conexion, $consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Avanzada - Administrador</title>
    <link rel="stylesheet" href="panel.css">
    <style>
        .seccion-admin { background: #222; padding: 20px; border-radius: 8px; margin: 20px auto; max-width: 900px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .form-mini { display: inline-block; background: #333; padding: 10px; border-radius: 4px; margin-top: 5px; }
        .form-mini input, .form-mini select { padding: 5px; background: #222; color: #fff; border: 1px solid #555; border-radius: 3px; width: 100px; }
        .form-mini button { padding: 5px 10px; background: #00ff88; border: none; font-weight: bold; cursor: pointer; border-radius: 3px; color: #111; }
        .btn-enlace { display: inline-block; margin-bottom: 15px; padding: 10px; background: #333; color: #00ff88; text-decoration: none; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
<header>
    <h1>Gestión de Permisos y Carga Manual</h1>
    <div class="info-perfil">
        <a href="admin.php" class="btn-salir" style="color: #00ff88; margin-right: 15px;">◄ Volver a Simulador</a>
        <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
    </div>
</header>

<div class="seccion-admin">
    <a href="registro.html" class="btn-enlace">+ Crear Nuevo Usuario Desde la Web</a>
    
    <?php if(isset($_GET['msg'])) echo "<p style='color:#00ff88;'>".$_GET['msg']."</p>"; ?>
    
    <h2>Lista General de Usuarios e Inyección de Datos</h2>
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Modificar Datos / Rol</th>
                <th>Carga Manual de Pulsaciones (Simular Oxímetro)</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['nombre'] . " " . $user['apellido']; ?></td>
                <td><?php echo $user['correo']; ?></td>
                
                <td>
                    <form action="gestionar_usuarios.php" method="POST" class="form-mini">
                        <input type="hidden" name="accion" value="modificar_usuario">
                        <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                        
                        <input type="text" name="contrasenia" value="<?php echo $user['contrasenia']; ?>" placeholder="Clave" style="width:70px;" required>
                        
                        <select name="col_rol" style="width:80px;" onchange="this.form.rol.value=this.value">
                            <option value="usuario" <?php if($user['role']=='usuario') echo 'selected';?>>Usuario</option>
                            <option value="admin" <?php if($user['rol']=='admin') echo 'selected';?>>Admin</option>
                        </select>
                        <input type="hidden" name="rol" value="<?php echo $user['rol']; ?>">
                        
                        <button type="submit">Guardar</button>
                    </form>
                </td>

                <td>
                    <form action="gestionar_usuarios.php" method="POST" class="form-mini">
                        <input type="hidden" name="accion" value="carga_manual">
                        <input type="hidden" name="usuario_id" value="<?php echo $user['id']; ?>">
                        
                        <input type="number" name="pulsaciones" placeholder="BPM (ej: 80)" style="width:75px;" required min="40" max="200">
                        <input type="number" name="oxigeno" placeholder="%SpO2 (ej: 98)" style="width:85px;" required min="70" max="100">
                        
                        <button type="submit" style="background:#00e1ff;">Inyectar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>