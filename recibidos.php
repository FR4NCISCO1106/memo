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
                    <h1 class="mt-4">Bandeja de Entrada</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Recibidos</li>
                    </ol>

                    <div class="card mb-4 shadow">
                        <div class="card-header">
                            <i class="fas fa-inbox me-1"></i> Solicitudes para mi Departamento
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Origen</th>
                                        <th>Asunto</th>
                                        <th>Mensaje</th>
                                        <th>Estatus</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $sql = "SELECT s.*, d.nombre_depto as origen 
                                          FROM solicitudes s 
                                          JOIN departamentos d ON s.id_remitente = d.id_depto 
                                          WHERE s.id_destinatario = $id_actual 
                                          ORDER BY s.fecha_envio DESC";
                                  
                                  $res = mysqli_query($conexion, $sql);
                                  
                                  while($row = mysqli_fetch_assoc($res)) {
                                      $is_pendiente = ($row['estatus'] == 0);
                                      $badge = $is_pendiente ? 'bg-warning text-dark' : 'bg-success';
                                      $texto = $is_pendiente ? 'Pendiente' : 'Procesado';
                                      ?>
                                      <tr>
                                          <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_envio'])); ?></td>
                                          <td><strong><?php echo htmlspecialchars($row['origen']); ?></strong></td>
                                          <td><?php echo htmlspecialchars($row['asunto']); ?></td>
                                          <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                                          <td><span class="badge <?php echo $badge; ?>"><?php echo $texto; ?></span></td>
                                          <td>
                                              <div class="d-flex gap-1">
                                                  <?php if(!empty($row['archivo'])): ?>
                                                      <button type="button" class="btn btn-sm btn-info text-white view-pdf" 
                                                              data-archivo="uploads/<?php echo $row['archivo']; ?>" 
                                                              data-bs-toggle="modal" data-bs-target="#pdfModal">
                                                          <i class="fas fa-eye"></i>
                                                      </button>
                                                  <?php endif; ?>

                                                  <?php if($is_pendiente): ?>
                                                      <a href="cambiar_estatus.php?id=<?php echo $row['id_solicitud']; ?>" 
                                                        class="btn btn-sm btn-outline-success">
                                                          <i class="fas fa-check"></i> Procesar
                                                      </a>
                                                  <?php else: ?>
                                                      <button class="btn btn-sm btn-light" disabled>Completado</button>
                                                  <?php endif; ?>
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
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="pdfModalLabel">Visualización de Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="pdfViewer" src="" frameborder="0" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>
</div>
    <?php include("includes/script.php"); ?>
</body>
</html>