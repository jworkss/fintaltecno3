<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();
    $correo = mysqli_real_escape_string($conexion, trim($_POST["correo"]));
    $contrasenia_ingresada = $_POST["contrasenia"];

    $consulta = "SELECT id, nombre, contrasenia, rol FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if ($contrasenia_ingresada === $usuario["contrasenia"]) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];

            if ($usuario["rol"] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta. <a href='login.html'>Volver</a>";
        }
    } else {
        echo "Usuario no encontrado. <a href='login.html'>Volver</a>";
    }
}
mysqli_close($conexion);
?>