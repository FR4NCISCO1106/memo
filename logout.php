<?php
session_start(); // Inicia la sesión para poder destruirla
session_unset(); // Libera todas las variables de sesión
session_destroy(); // Destruye la sesión físicamente

// Redirigir al login
header("Location: login.php");
exit();
?>