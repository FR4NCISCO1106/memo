<?php
include("includes/db.php");
include("includes/verificar_sesion.php");

if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php");
    exit();
}

$id_actual = $_SESSION['id_depto'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/header.php"); ?>
</head>
<body class="sb-nav-fixed">
    <?php include("includes/navbar.php"); ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("includes/sidenavbar.php"); ?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 text-danger">Solicitudes Pendientes</h1>
                    <div class="card mb-4 shadow">
                        <div class="card-header bg-danger text-white">
                            <i class="fas fa-exclamation-triangle me-1"></i> Tareas por procesar
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Origen</th>
                                        <th>Asunto</th>
                                        <th>Mensaje</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT s.*, d.nombre_depto as origen 
                                            FROM solicitudes s 
                                            JOIN departamentos d ON s.id_remitente = d.id_depto 
                                            WHERE s.id_destinatario = $id_actual AND s.estatus = 0
                                            ORDER BY s.fecha_envio ASC";
                                    $res = mysqli_query($conexion, $sql);
                                    
                                    if(mysqli_num_rows($res) == 0) {
                                        echo '<tr><td colspan="5" class="text-center">No hay solicitudes pendientes</td></tr>';
                                    }
                                    
                                    while($row = mysqli_fetch_assoc($res)) {
                                        ?>
                                        <tr>
                                          <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_envio'])); ?></td>
                                          <td><strong><?php echo htmlspecialchars($row['origen']); ?></strong></td>
                                          <td><?php echo htmlspecialchars($row['asunto']); ?></td>
                                          <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                                          <td>
                                              <div class="d-flex gap-1">
                                                  <?php if(!empty($row['archivo'])): ?>
                                                      <button type="button" class="btn btn-sm btn-info text-white view-pdf" 
                                                              data-archivo="uploads/<?php echo $row['archivo']; ?>" 
                                                              data-bs-toggle="modal" data-bs-target="#pdfModal">
                                                          <i class="fas fa-eye"></i>
                                                      </button>
                                                  <?php endif; ?>
                                                  
                                                  <a href="cambiar_estatus.php?id=<?php echo $row['id_solicitud']; ?>" class="btn btn-success btn-sm">
                                                      <i class="fas fa-check"></i> Marcar como Listo
                                                  </a>
                                              </div>
                                          </td>
                                      </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include("includes/footer.php"); ?>
        </div>
    </div>
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Documento Adjunto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfViewer" src="" frameborder="0" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
// Este script hace que el botón cargue el PDF en el iframe
document.addEventListener('click', function (e) {
    if (e.target.closest('.view-pdf')) {
        const btn = e.target.closest('.view-pdf');
        const ruta = btn.getAttribute('data-archivo');
        document.getElementById('pdfViewer').setAttribute('src', ruta);
    }
});
</script>
    <?php include("includes/script.php"); ?>
</body>
</html>