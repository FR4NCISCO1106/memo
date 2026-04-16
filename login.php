<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php include("includes/header.php");?>
    <style>
    /* 1. Fondo con degradado púrpura */
    .fondo-login {
        /* Cambiamos el linear-gradient por la ruta de tu imagen */
        background-image: url('img/fondo11.jpg') !important; 
        
        /* Propiedades para que la imagen se vea bien */
        background-size: cover !important;       /* Cubre toda la pantalla */
        background-position: center !important;  /* Centra la imagen */
        background-repeat: no-repeat !important; /* No se repite */
        background-attachment: fixed !important; /* La imagen se queda fija al hacer scroll */
        min-height: 100vh;
    }

    /* 2. Opcional: Si la imagen es muy clara, puedes poner una capa oscura encima */
    .fondo-login::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4); /* Capa negra con 40% de opacidad */
        z-index: -1;
    }


    /* 2. Tarjeta con efecto de cristal (Glassmorphism) */
    .card {
        background: rgba(255, 255, 255, 0.1) !important; /* Transparencia */
        backdrop-filter: blur(10px); /* Desenfoque del fondo */
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 20px !important;
        color: white;
        min-height: 650px;
    }

    /* 3. Inputs estilizados (púrpuras y semitransparentes) */
    .form-control {
        background: rgba(255, 255, 255, 0.2) !important;
        border: none !important;
        border-radius: 50px !important; /* Forma de cápsula como la imagen */
        color: white !important;
        padding: 1.5rem 1.5rem !important;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    /* Color del texto de las etiquetas */
    .form-floating > label {
        color: rgba(255, 255, 255, 0.8) !important;
        padding-left: 1.5rem;
    }
    .form-floating.mb-4 {
        margin-bottom: 2.5rem !important; /* Aumenta este valor para dar más espacio entre campos */
    }
    .form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    transform: scale(.85) translateY(-0.8rem) translateX(1rem) !important;
}

    /* 4. Botón con degradado brillante */
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

    /* Ajuste para el logo en este fondo */
    .logo-login {
        filter: brightness(0) invert(1); /* Hace el logo blanco para que resalte */
        max-width: 350px;
        margin-bottom: 20px;

    }

    #togglePassword:hover {
    color: #ffffff !important;
    transition: 0.3s;
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
                    <form action="validar_login.php" method="POST">
                        <div class="form-floating mb-4">
                            <input class="form-control" name="usuario" id="inputEmail" type="text" placeholder="Usuario" required />
                            <label for="inputEmail">Usuario</label>
                        </div>
                        <div class="form-floating mb-4 position-relative">
                            <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required style="padding-right: 60px !important;">
                            <label for="inputPassword">Contraseña Especial</label>
                            
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
            
            console.log("Configurando:", {contenedor, icono, input});
            
            if (contenedor && icono && input) {
                // Remover eventos anteriores
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
    
    // Ejecutar cuando el DOM esté listo y también cuando termine de cargar todo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', configurarOjoPassword);
    } else {
        configurarOjoPassword();
    }
    
    // También ejecutar después de que todo esté completamente cargado
    window.addEventListener('load', configurarOjoPassword);
</script>

    <?php include("includes/script.php");?>

</body>
</html>