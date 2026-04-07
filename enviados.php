<?php
session_start();
include("includes/db.php");

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
                    <h1 class="mt-4">Mis Solicitudes Enviadas</h1>
                    <div class="card mb-4 shadow">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-paper-plane me-1"></i> Historial de envíos
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="datatablesSimple">
                              <thead>
                              <tr>
                                  <th>Fecha</th>
                                  <th>Destinatario</th>
                                  <th>Asunto</th>
                                  <th>Estatus</th>
                                  <th>Acción</th> </tr>
                          </thead>
                          <tbody>
                            <?php
                            $sql = "SELECT s.*, d.nombre_depto as destino 
                                    FROM solicitudes s 
                                    JOIN departamentos d ON s.id_destinatario = d.id_depto 
                                    WHERE s.id_remitente = $id_actual 
                                    ORDER BY s.fecha_envio DESC";
                            
                            $res = mysqli_query($conexion, $sql); 

                            if(mysqli_num_rows($res) == 0) {
                                echo '<tr><td colspan="5" class="text-center">No hay solicitudes enviadas</td></tr>';
                            }

                            while($row = mysqli_fetch_assoc($res)) {
                                $color = ($row['estatus'] == 0) ? 'bg-warning text-dark' : 'bg-success';
                                $texto = ($row['estatus'] == 0) ? 'Pendiente' : 'Procesado';
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_envio'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['destino']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['asunto']); ?></td>
                                    <td><span class="badge <?php echo $color; ?>"><?php echo $texto; ?></span></td>
                                    <td>
                                        <?php if(!empty($row['archivo'])): ?>
                                            <button type="button" class="btn btn-sm btn-info text-white view-pdf" 
                                                    data-archivo="uploads/<?php echo $row['archivo']; ?>" 
                                                    data-bs-toggle="modal" data-bs-target="#pdfModal">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted small">Sin adjunto</span>
                                        <?php endif; ?>
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