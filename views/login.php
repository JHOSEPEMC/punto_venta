<?php include 'partials/header.php'; ?>
<?php include 'partials/nav.php'; ?>

<div class="container mt-4">
    <h2>Login</h2>
    <form action="" method="POST"> 
        <!--Esto es para el inicio de sesion, obteniendo Dni Usuario y Contraseña-->
        <span for="UsuarioDni">Usuario (DNI): </span> <br>
        <input class = 'i_s' type="text" id="UsuarioDni" required 
        placeholder = "Usuario" name="UsuarioDni"><br><br> 
    
        <span for="UsuarioContra">Contraseña: </span> <br>
        <input class = 'i_s' type="password" id="UsuarioContra" required 
        placeholder = "contraseña" name="UsuarioContra"><br><br>

        <button type="submit" class = "i_s">
            Iniciar Sesion
        </button>
    </form>
    <?php
        session_start(); //Inicia la Sesion LOL
        $carritoTotal = isset($_SESSION['totalCarrito']) ? $_SESSION['totalCarrito'] : null; //si carrito existe ya en session, lo vaceamos
        if($carritoTotal != null){$_SESSION['totalCarrito'] = null;}
        
        $contador_intentos = 0; //contador de intentos de inisio de seccion :SillyDev:
        $usuario = isset($_POST['UsuarioDni']) ? $_POST['UsuarioDni'] : ''; //para identificar por el nombre del input
        $contra = isset($_POST['UsuarioContra']) ? $_POST['UsuarioContra'] : '';
        $mensaje = false;
        $usuarioLogueado = null;
        if(($usuario == '' || $contra == '') && $contador_intentos < 1) {
            
        }
        else{
            require_once '../models/Conexion.php'; // "importamos" la conexion.php
            $db = Conexion::conectar(); //conectamos
            $sql = "SELECT * FROM empleados WHERE dni_empleado = '$usuario' AND pass = '$contra'"; 
            #Consulta para el sql :SillyDev:
            $usuarioLogueado = $db->query($sql)->fetch_assoc(); //Convierte en "lista" el resultado de la consulta
            if(isset($usuarioLogueado['dni_empleado']) == '' && isset($usuarioLogueado['pass']) == ''){
                $mensaje = true; //si nombre de usuario y contraseña no existen dentro de db, entonces son credenciales incorrectas :SillyDev:
            }
            else{
                $_SESSION['UsuarioDni'] = $usuarioLogueado['dni_empleado'];
                $_SESSION['UsuarioContra'] = $usuarioLogueado['pass'];
            }
        }
        if ($mensaje): ?>
        <div class="alert alert-info"><?= "Ingrese correctamente sus credenciales" ?></div>
        <?php endif; ?>

        <?php 
        if ($usuarioLogueado && !$mensaje): ?>
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Bienvenido, <?= htmlspecialchars($usuarioLogueado['nombre_apellido']) ?></h5>
            </div>
        </div>
        <?php 
        ?>
        <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>