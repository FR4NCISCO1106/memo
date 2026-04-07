<?php
$host = "localhost";
$user = "root"; // Usuario por defecto en XAMPP
$pass = "";     // Contraseña por defecto en XAMPP (vacía)
$db   = "solicitudes_sistema";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar para que acepte tildes y eñes
mysqli_set_charset($conexion, "utf8");
?>