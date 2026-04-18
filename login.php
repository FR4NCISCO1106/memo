<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php include("includes/header.php");?>
    <style>
    /* 1. Fondo con imagen */
    .fondo-login {
        background-image: url('img/fondo11.jpg') !important; 
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-attachment: fixed !important;
        min-height: 100vh;
    }

    .fondo-login::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4); 
        z-index: -1;
    }

    /* 2. Tarjeta con efecto de cristal (Glassmorphism) */
    .card {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 20px !important;
        color: white;
        min-height: 650px;
    }

    /* 3. Inputs estilizados */
    .form-control {
        background: rgba(255, 255, 255, 0.2) !important;
        border: none !important;
        border-radius: 50px !important;
        color: white !important;
        padding: 1.5rem 1.5rem !important;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    .form-floating > label {
        color: rgba(255, 255, 255, 0.8) !important;
        padding-left: 1.5rem;
    }

    .form-floating.mb-4 {
        margin-bottom: 2.5rem !important;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        transform: scale(.85) translateY(-0.8rem) translateX(1rem) !important;
    }

    /* 4. Botón con degradado */
    .btn-moderno {
        background: linear-gradient(to right, #051F20, #235347) !important;
        border: none;
        border-radius: 50px !important;
        padding: 18px;
        font-weight: bold;
        letter-spacing: 3px;
        transition: all 0.3s ease;
    }

    .btn-moderno:hover {
        transform: scale(1.04);
        box-shadow: 0 5px 15px #163832;
    }

    .logo-login {
        filter: brightness(0) invert(1);
        max-width: 350px;
        margin-bottom: 20px;
    }

    /* Estilo para la alerta de error */
    .alert-error {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.4);
        color: #ff8e97;
        border-radius: 15px;
        padding: 12px;
        margin-bottom: 20px;
        backdrop-filter: blur(5px);
        font-size: 0.9rem;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<body class="fondo-login">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="col-lg-6 col-md-7">
            <div class="card shadow-lg border-0 text-center p-4">
                <div class="card-header">
                    <img src="img/bicentenario.png" alt="Logo" class="logo-login">
                    <h3 class="fw-dark my-4">Bienvenido</h3>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert-error d-flex align-items-center justify-content-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <?php 
                                    $err = $_GET['error'];
                                    if ($err == 2) echo "Por favor, rellena todos los campos.";
                                    elseif ($err == 3) echo "El usuario no existe.";
                                    elseif ($err == 4) echo "Contraseña incorrecta.";
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="validar_login.php" method="POST">
                        <div class="form-floating mb-4">
                            <input class="form-control" name="usuario" id="inputEmail" type="text" placeholder="Usuario" required />
                            <label for="inputEmail">Usuario</label>
                        </div>
                        <div class="form-floating mb-4 position-relative">
                            <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required style="padding-right: 60px !important;">
                            <label for="inputPassword">Contraseña</label>
                            
                            <span id="contenedorOjo" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 100;">
                                <i class="fas fa-eye-slash" id="ojoPassword" style="color: white; font-size: 1.2rem;"></i>
                            </span>
                        </div>
                        <button type="submit" class="btn btn-moderno w-100 text-white mt-3">INICIAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function configurarOjoPassword() {
        setTimeout(function() {
            const contenedor = document.getElementById('contenedorOjo');
            const icono = document.getElementById('ojoPassword');
            const input = document.getElementById('inputPassword');
            
            if (contenedor && icono && input) {
                const nuevoContenedor = contenedor.cloneNode(true);
                contenedor.parentNode.replaceChild(nuevoContenedor, contenedor);
                
                const nuevoIcono = nuevoContenedor.querySelector('#ojoPassword');
                const nuevoInput = document.getElementById('inputPassword');
                
                nuevoContenedor.onclick = function(e) {
                    e.preventDefault();
                    if (nuevoInput.type === 'password') {
                        nuevoInput.type = 'text';
                        nuevoIcono.className = 'fas fa-eye';
                    } else {
                        nuevoInput.type = 'password';
                        nuevoIcono.className = 'fas fa-eye-slash';
                    }
                    nuevoInput.focus();
                };
            }
        }, 100);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', configurarOjoPassword);
    } else {
        configurarOjoPassword();
    }
    window.addEventListener('load', configurarOjoPassword);

    // ... (tu código existente de configurarOjoPassword)

function limpiarURL() {
    // Verifica si hay parámetros de error en la URL
    const url = new URL(window.location.href);
    if (url.searchParams.has('error')) {
        // Creamos una nueva URL sin los parámetros de búsqueda
        const nuevaUrl = window.location.pathname;
        // Reemplazamos el estado en el historial para que la barra de direcciones se limpie
        window.history.replaceState({}, document.title, nuevaUrl);
    }
}

// Ejecutar la limpieza después de un pequeño delay o al cargar
window.addEventListener('load', () => {
    // Opcional: puedes darle 2 o 3 segundos para que el usuario alcance a leer el error
    // antes de limpiar la URL, o hacerlo de inmediato:
    limpiarURL(); 
});
</script>

    <?php include("includes/script.php");?>

</body>
</html>