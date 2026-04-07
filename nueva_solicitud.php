<?php 
session_start();
include("includes/db.php");

if (!isset($_SESSION['id_depto'])) {
    header("Location: login.php");
    exit();
}

$id_actual = $_SESSION['id_depto'];

// Obtener departamentos excepto el actual
$sql_deptos = "SELECT id_depto, nombre_depto FROM departamentos WHERE id_depto != $id_actual ORDER BY nombre_depto";
$res_deptos = mysqli_query($conexion, $sql_deptos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/header.php");?>
</head>
<body class="sb-nav-fixed">
    <?php include("includes/navbar.php");?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <?php include("includes/sidenavbar.php");?>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Nueva Solicitud</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nueva Solicitud</li>
                    </ol>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i>
                            <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-xl-8 col-lg-10"> 
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-edit me-1"></i> Redactar Solicitud
                                </div>
                                <div class="card-body">
                                    <form action="procesar_envio.php" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Departamento Destino</label>
                                            <select name="id_destinatario" class="form-select" required>
                                                <option value="">Seleccione un departamento...</option>
                                                <?php while($depto = mysqli_fetch_assoc($res_deptos)): ?>
                                                    <option value="<?php echo $depto['id_depto']; ?>">
                                                        <?php echo htmlspecialchars($depto['nombre_depto']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Asunto</label>
                                            <input type="text" name="asunto" class="form-control" placeholder="Ej: Falla de impresora" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mensaje / Detalle</label>
                                            <textarea name="mensaje" class="form-control" rows="5" placeholder="Describa su solicitud aquí..." required></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="archivo" class="form-label">Adjuntar documento (PDF)</label>
                                            <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf">
                                            <small class="text-muted">Solo archivos PDF (máx. 10MB)</small>
                                        </div>
                                                  
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane"></i> Enviar Solicitud
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include("includes/footer.php");?>
        </div>
    </div>
    <?php include("includes/script.php");?>
</body>
</html>