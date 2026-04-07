<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])) {
    $id_solicitud = $_GET['id'];
    
    // Verificar que la solicitud existe y pertenece al departamento destinatario
    $id_actual = $_SESSION['id_depto'];
    $sql = "UPDATE solicitudes SET estatus = 1 
            WHERE id_solicitud = $id_solicitud AND id_destinatario = $id_actual AND estatus = 0";
    
    if(mysqli_query($conexion, $sql)) {
        $_SESSION['mensaje'] = "Solicitud procesada correctamente";
    } else {
        $_SESSION['error'] = "Error al procesar: " . mysqli_error($conexion);
    }
}

// Redirigir de vuelta a la página de pendientes o recibidos
$referer = $_SERVER['HTTP_REFERER'] ?? 'pendientes.php';
header("Location: $referer");
exit();
?>