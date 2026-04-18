<?php
include("includes/db.php");
session_start();

if (isset($_POST['usuario']) && isset($_POST['password'])) {
    
    // Validar que no estén vacíos
    if(empty($_POST['usuario']) || empty($_POST['password'])) {
        header("Location: login.php?error=2"); // Error: campos vacíos
        exit();
    }
    
    $usuario = trim($_POST['usuario']); // Limpiar espacios en blanco
    $password = $_POST['password'];

    // Usar prepared statement para prevenir SQL injection
    $stmt = $conexion->prepare("SELECT id_depto, nombre_depto FROM departamentos WHERE usuario = ? AND password = ?");
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $datos = $resultado->fetch_assoc();
        
        $_SESSION['id_depto'] = $datos['id_depto'];
        $_SESSION['nombre_depto'] = $datos['nombre_depto'];

        header("Location: index.php");
        exit();
    } else {
        // Verificar si el usuario existe para dar mensaje más específico (opcional)
        $stmt2 = $conexion->prepare("SELECT id_depto FROM departamentos WHERE usuario = ?");
        $stmt2->bind_param("s", $usuario);
        $stmt2->execute();
        $resultado2 = $stmt2->get_result();
        
        if($resultado2->num_rows == 0) {
            // El usuario no existe
            header("Location: login.php?error=3");
        } else {
            // Usuario existe pero contraseña incorrecta
            header("Location: login.php?error=4");
        }
        $stmt2->close();
        exit();
    }
    
    $stmt->close();
} else {
    // Si intentan entrar a validar_login.php sin enviar datos
    header("Location: login.php");
    exit();
}
?>