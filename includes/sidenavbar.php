<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    
    <div class="text-center mt-4">
                <img src="img/bicentenario.png" alt="Logo" style="max-width: 80%; opacity: 0.8;" class="img-fluid">
            </div>
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Principal</div>
            <a class="nav-link" href="index.php">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <div class="sb-sidenav-menu-heading">Mensajería</div>
            
            <a class="nav-link" href="nueva_solicitud.php">
                <div class="sb-nav-link-icon"><i class="fas fa-plus-circle"></i></div>
                Nueva Solicitud
            </a>

            <a class="nav-link" href="recibidos.php">
                <div class="sb-nav-link-icon"><i class="fas fa-inbox"></i></div>
                Bandeja de Entrada
            </a>

            <a class="nav-link" href="pendientes.php">
                <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                Por Procesar
            </a>

            <a class="nav-link" href="enviados.php">
                <div class="sb-nav-link-icon"><i class="fas fa-paper-plane"></i></div>
                Solicitudes Enviadas
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Conectado como:</div>
        <?php 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        echo isset($_SESSION['nombre_depto']) ? htmlspecialchars($_SESSION['nombre_depto']) : 'Invitado'; 
        ?>
    </div>
</nav>