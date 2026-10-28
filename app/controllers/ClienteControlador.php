<?php
class ClienteControlador {
    public function registrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['UsuarioDni'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=empleado&action=login');
            exit;
        }

        $mensaje = '';
        $tipo = '';
        $exito = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['dni']);
            $nombre = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);

            if ($dni === '' || $nombre === '') {
                $mensaje = 'El DNI y el Nombre son obligatorios.';
                $tipo = 'danger';
            } else {
                // 🚀 USAMOS EL DAO
                $cliDAO = new ClienteDAO();
                $clienteExistente = $cliDAO->obtener_por_dni($dni);
                
                if ($clienteExistente) {
                    $mensaje = 'Ya existe un cliente con ese DNI.';
                    $tipo = 'warning';
                } else {
                    if ($cliDAO->insertar($dni, $nombre, $telefono)) {
                        $mensaje = 'Cliente registrado correctamente.';
                        $tipo = 'success';
                        $exito = true;
                    } else {
                        $mensaje = 'Error al registrar el cliente.';
                        $tipo = 'danger';
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'exito' => $exito,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'dni' => isset($dni) ? $dni : '',
            'nombre' => isset($nombre) ? $nombre : ''
        ]);
        exit;
    }
}