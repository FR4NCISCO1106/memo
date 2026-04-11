<?php
session_start();

if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php"); // Lo mandamos al login
    exit(); // Detenemos la ejecución del resto de la página
}
?>