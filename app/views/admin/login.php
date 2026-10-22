<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador - Punto de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            margin: 0;
            background-color: #1a1a1a; /* Fondo oscuro para admin */
        }
        .split-screen {
            display: flex;
            height: 100vh;
        }
        .left-side {
            flex: 1;
            background: #FF7800; /* Naranja */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
            text-align: center;
        }
        .left-side img {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .right-side {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1a1a1a;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
            background: #2a2a2a;
            color: white;
        }
        .login-title {
            font-weight: 800;
            color: #FF7800;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #444;
            background-color: #333;
            color: white;
        }
        .form-control:focus {
            background-color: #333;
            color: white;
            border-color: #FF7800;
            box-shadow: 0 0 0 0.2rem rgba(255, 120, 0, 0.25);
        }
        .btn-custom {
            background-color: #FF7800;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            background-color: #E65C00;
        }
        .input-group-text {
            background-color: #333;
            border: 1px solid #444;
            border-right: none;
            color: #888;
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control {
            border-left: none;
        }
        .text-muted-link {
            color: #888;
            text-decoration: none;
        }
        .text-muted-link:hover {
            color: #FF7800;
        }
    </style>
</head>
<body>

<div class="split-screen">
    <!-- Lado Izquierdo -->
    <div class="left-side d-none d-md-flex">
        <img src="<?= BASE_URL ?>assets/icono-transparent.webp" alt="Logo Minimarket">
        <h2 class="fw-bold">Panel de Control</h2>
        <p class="text-white-50">Gestión administrativa y reportes de ventas.</p>
    </div>

    <!-- Lado Derecho -->
    <div class="right-side">
        <div class="login-card">
            <h3 class="login-title"><i class="fas fa-shield-alt me-2"></i> Acceso Admin</h3>

            <?php if ($mensaje): ?>
                <div class="alert alert-danger bg-danger text-white border-0 text-center rounded-3">
                    Credenciales incorrectas
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?controller=admin&action=login" method="POST">
                <div class="mb-4">
                    <label class="form-label text-muted">DNI del Administrador</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                        <input type="text" name="AdminDni" class="form-control" placeholder="DNI Admin" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="AdminContra" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-custom mt-2">
                    Ingresar al Panel
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>index.php?controller=empleado&action=login" class="text-muted-link">
                    <small><i class="fas fa-user"></i> Ir a Login de Empleados</small>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>