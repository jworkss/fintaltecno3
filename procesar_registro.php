<?php
header("Content-Type: text/html; charset=utf-8");
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
    $apellido = mysqli_real_escape_string($conexion, trim($_POST["apellido"]));
    $correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
    $contrasenia_plana = mysqli_real_escape_string($conexion, trim($_POST["contrasenia"]));
    $tiene_familia = $_POST["tiene_familia"];
    $edad = (int)$_POST["edad"];
    
    $check_correo = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo'");
    if (mysqli_num_rows($check_correo) > 0) {
        die("<p style='color: red;'>El correo ya existe.</p><a href='registro.html'>Volver</a>");
    }

    $familia_id = null;

    if ($tiene_familia === "si") {
        $codigo_ingresado = mysqli_real_escape_string($conexion, trim($_POST["codigo_familiar"]));
        $consulta_familia = mysqli_query($conexion, "SELECT id FROM familias WHERE codigo_familiar = '$codigo_ingresado'");
        
        if (mysqli_num_rows($consulta_familia) > 0) {
            $fila_familia = mysqli_fetch_assoc($consulta_familia);
            $familia_id = $fila_familia["id"];
        } else {
            die("<p style='color: red;'>El código familiar no existe.</p><a href='registro.html'>Volver</a>");
        }
    } else {
        $codigo_nuevo = "";
        $codigo_unico = false;
        while (!$codigo_unico) {
            $codigo_nuevo = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
            $check_codigo = mysqli_query($conexion, "SELECT id FROM familias WHERE codigo_familiar = '$codigo_nuevo'");
            if (mysqli_num_rows($check_codigo) == 0) { $codigo_unico = true; }
        }
        
        $insertar_familia = "INSERT INTO familias (codigo_familiar) VALUES ('$codigo_nuevo')";
        if (mysqli_query($conexion, $insertar_familia)) {
            $familia_id = mysqli_insert_id($conexion);
            echo "<div style='color: #00ff88; font-family:Arial; margin-bottom:15px;'>Nuevo grupo familiar creado. Código: <strong>$codigo_nuevo</strong></div>";
        }
    }

    $insertar_usuario = "INSERT INTO usuarios (nombre, apellido, correo, contrasenia, rol, familia_id, edad) 
                     VALUES ('$nombre', '$apellido', '$correo', '$contrasenia_plana', 'usuario', " . ($familia_id ? $familia_id : "NULL") . ", $edad)";
    
    if (mysqli_query($conexion, $insertar_usuario)) {
        echo "<div style='font-family:Arial; color:white;'><h3>¡Registro Completo!</h3><a href='login.html' style='color:#00ff88;'>Iniciar Sesión</a></div>";
    }
}
mysqli_close($conexion);
?>