<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);

    // Buscamos al departamento por su nombre de usuario
    $sql = "SELECT * FROM departamentos WHERE usuario = '$usuario' AND password = '$password'";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) > 0) {
        $datos = mysqli_fetch_array($resultado);
        
        // Guardamos los datos en la SESIÓN para usarlos en todo el sistema
        $_SESSION['id_depto'] = $datos['id_depto'];
        $_SESSION['nombre_depto'] = $datos['nombre_depto'];

        header("Location: index.php"); // Redirige al Dashboard
    } else {
        header("Location: login.php?error=1"); // Error de datos incorrectos
    }
}
?>