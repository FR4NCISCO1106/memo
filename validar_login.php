<?php
include("includes/db.php");
session_start(); // Paso 1: Iniciar el motor de sesiones

if (isset($_POST['usuario']) && isset($_POST['password'])) {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);

    // Consulta para buscar al departamento por su nombre de usuario y contraseña
    $query = "SELECT id_depto, nombre_depto FROM departamentos WHERE usuario = '$usuario' AND password = '$password'";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $datos = mysqli_fetch_assoc($resultado);
        
        // Paso 2: Guardar los datos críticos en la sesión para que index.php los reconozca
        $_SESSION['id_depto'] = $datos['id_depto'];
        $_SESSION['nombre_depto'] = $datos['nombre_depto'];

        // Paso 3: Redirigir al panel principal
        header("Location: index.php");
        exit();
    } else {
        // Si los datos son incorrectos, volver al login con un error
        header("Location: login.php?error=1");
        exit();
    }
}
?>