<?php
include("includes/db.php");
include("includes/verificar_sesion.php");

// 1. Verificación de Seguridad: Si no hay sesión, mandarlo al login
if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php");
    exit();
}

// DEFINICIÓN DE VARIABLES CRÍTICAS (Se definen arriba para que las consultas SQL funcionen)
$id_actual = $_SESSION['id_depto'];
$nombre_depto_actual = $_SESSION['nombre_depto'];

// 2. Datos para el gráfico de pastel (Estatus de solicitudes enviadas por mi departamento)
$res_proc = mysqli_query($conexion, "SELECT COUNT(*) as total FROM solicitudes WHERE id_remitente = $id_actual AND estatus = 1");
$enviados_procesados = mysqli_fetch_assoc($res_proc)['total'];

$res_pend = mysqli_query($conexion, "SELECT COUNT(*) as total FROM solicitudes WHERE id_remitente = $id_actual AND estatus = 0");
$enviados_pendientes = mysqli_fetch_assoc($res_pend)['total'];

// 3. Consultas para los contadores de las tarjetas (Cards)
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
                                <div class="card bg-primary text-white mb-4 shadow">
                                    <div class="card-body">Recibidos: <strong><?php echo $total_recibidos; ?></strong></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="recibidos.php">Ver bandeja</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-danger text-white mb-4 shadow">
                                    <div class="card-body">Pendientes: <strong><?php echo $total_pendientes; ?></strong></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="pendientes.php">Ver por procesar</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="card bg-success text-white mb-4 shadow">
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
                                <div class="card mb-4 shadow">
                                    <div class="card-header bg-light">
                                        <i class="fas fa-chart-pie me-1"></i> Resumen de Mis Solicitudes
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 300px;">
                                            <canvas id="myPieChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="card-footer small text-muted">Datos de envíos actuales</div>
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
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Procesadas", "Pendientes"],
                datasets: [{
                    data: [<?php echo $enviados_procesados; ?>, <?php echo $enviados_pendientes; ?>],
                    backgroundColor: ['#198754', '#ffc107'], // Verde (Success) y Amarillo (Warning)
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        </script>
    </body>
</html>