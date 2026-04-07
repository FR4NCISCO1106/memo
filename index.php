<?php
session_start();
include("includes/db.php");

// 1. Verificación de Seguridad: Si no hay sesión, mandarlo al login
if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php");
    exit();
}

// DEFINICIÓN DE VARIABLES CRÍTICAS
$id_actual = $_SESSION['id_depto'];
$nombre_depto_actual = $_SESSION['nombre_depto'];

// 2. Consultas para los contadores de las tarjetas (Cards) usando $id_actual
$res_recibidos = mysqli_query($conexion, "SELECT COUNT(*) as total FROM solicitudes WHERE id_destinatario = $id_actual");
$total_recibidos = mysqli_fetch_assoc($res_recibidos)['total'];

$res_pendientes = mysqli_query($conexion, "SELECT COUNT(*) as total FROM solicitudes WHERE id_destinatario = $id_actual AND estatus = 0");
$total_pendientes = mysqli_fetch_assoc($res_pendientes)['total'];

$res_enviados = mysqli_query($conexion, "SELECT COUNT(*) as total FROM solicitudes WHERE id_remitente = $id_actual");
$total_enviados = mysqli_fetch_assoc($res_enviados)['total'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php include("includes/header.php");?>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
          <?php include("includes/navbar.php");?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                  <?php include("includes/sidenavbar.php");?>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Panel: <?php echo $nombre_depto_actual; ?></h1>
                        
                        <?php if(isset($_GET['enviado'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>¡Enviado!</strong> La solicitud se registró con éxito.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Recibidos: <strong><?php echo $total_recibidos; ?></strong></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="recibidos.php">Ver bandeja</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Pendientes: <strong><?php echo $total_pendientes; ?></strong></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="pendientes.php">Ver por procesar</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Enviados: <strong><?php echo $total_enviados; ?></strong></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="enviados.php">Ver historial</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-5">
                                <div class="card mb-4 border-left-primary shadow">
                                    <div class="card-header bg-light">
                                        <i class="fas fa-paper-plane me-1"></i> Enviar Solicitud
                                    </div>
                                    <div class="card-body">
                                        <form action="procesar_envio.php" method="POST">
                                            <div class="mb-3">
                                                <label class="form-label font-weight-bold">Destinatario</label>
                                                <select name="id_destinatario" class="form-select" required>
                                                    <option value="">Seleccione departamento...</option>
                                                    <?php
                                                    $q_deptos = mysqli_query($conexion, "SELECT * FROM departamentos WHERE id_depto != $id_actual");
                                                    while($d = mysqli_fetch_assoc($q_deptos)){
                                                        echo "<option value='".$d['id_depto']."'>".$d['nombre_depto']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-weight-bold">Asunto</label>
                                                <input type="text" name="asunto" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-weight-bold">Mensaje</label>
                                                <textarea name="mensaje" class="form-control" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">Enviar Ahora</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <div class="card mb-4 shadow">
                                    <div class="card-header bg-light">
                                        <i class="fas fa-history me-1"></i> Últimos Movimientos
                                    </div>
                                    <div class="card-body">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Asunto</th>
                                                    <th>Estatus</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $q_recientes = mysqli_query($conexion, "SELECT fecha_envio, asunto, estatus FROM solicitudes WHERE id_remitente = $id_actual OR id_destinatario = $id_actual ORDER BY fecha_envio DESC LIMIT 10");
                                                while($fila = mysqli_fetch_assoc($q_recientes)) {
                                                    $color = ($fila['estatus'] == 1) ? 'bg-success' : 'bg-warning text-dark';
                                                    $texto = ($fila['estatus'] == 1) ? 'Procesado' : 'Pendiente';
                                                    echo "<tr>
                                                            <td>".date('d/m/H:i', strtotime($fila['fecha_envio']))."</td>
                                                            <td>".$fila['asunto']."</td>
                                                            <td><span class='badge $color'>$texto</span></td>
                                                          </tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <?php include("includes/footer.php");?>
                </footer>
            </div>
        </div>
        <?php include("includes/script.php");?>
    </body>
</html>