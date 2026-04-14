<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/header.php");?>
    
</head>
<body class="text-bg-primary">
    
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div id="layoutAuthentication">
                    <div id="layoutAuthentication_content">
                        <main>
                            <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                                <div class="row justify-content-center w-100">
                                    <div class="col-lg-5">
                                        <div class="card shadow-lg border-0 rounded-lg">
                                            <div class="card-header">
                                                <div class="text-center mt-3">
                                                    <img src="img/bicentenario.png" alt="Logo" style="max-width: 280px;" class="img-fluid">
                                                </div>                                            
                            </div>
                                <div class="card-body">
                                    <?php if(isset($_GET['error'])) echo '<div class="alert alert-danger">Usuario o contraseña incorrectos.</div>'; ?>
                                    
                                    <form action="validar_login.php" method="POST">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="usuario" id="inputEmail" type="text" placeholder="Departamento" required />
                                            <label for="inputEmail">Nombre del Departamento</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Contraseña Especial</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary w-100">Ingresar al Sistema</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto"><?php include("includes/footer.php");?></footer>
        </div>
    </div>
    <?php include("includes/script.php");?>
</body>
</html>