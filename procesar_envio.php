<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_remitente = $_SESSION['id_depto'];
    $id_destinatario = $_POST['id_destinatario'];
    $asunto = mysqli_real_escape_string($conexion, $_POST['asunto']);
    $mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);
    $fecha_envio = date('Y-m-d H:i:s');
    
    // Procesar archivo si se subió
    $nombre_archivo = NULL;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $allowed = ['pdf', 'PDF'];
        $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
        
        if (in_array($extension, $allowed)) {
            $nombre_archivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['archivo']['name']);
            $ruta_destino = 'uploads/' . $nombre_archivo;
            
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_destino)) {
                // Archivo subido correctamente
            } else {
                $_SESSION['error'] = "Error al subir el archivo";
                header("Location: nueva_solicitud.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Solo se permiten archivos PDF";
            header("Location: nueva_solicitud.php");
            exit();
        }
    }
    
    $sql = "INSERT INTO solicitudes (id_remitente, id_destinatario, asunto, mensaje, archivo, fecha_envio, estatus) 
            VALUES ($id_remitente, $id_destinatario, '$asunto', '$mensaje', " . ($nombre_archivo ? "'$nombre_archivo'" : "NULL") . ", '$fecha_envio', 0)";
    
    if (mysqli_query($conexion, $sql)) {
        $_SESSION['mensaje'] = "Solicitud enviada correctamente";
        header("Location: enviados.php");
    } else {
        $_SESSION['error'] = "Error al enviar: " . mysqli_error($conexion);
        header("Location: nueva_solicitud.php");
    }
    exit();
}
?>