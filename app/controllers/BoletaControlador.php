<?php
class BoletaControlador {

    //muestra el PDF de la boleta en el navegador
    public function mostrar(){
        $this->generar_pdf(false);
    }

    //descarga el PDF de la boleta
    public function descargar(){
        $this->generar_pdf(true);
    }

    //método privado que prepara los datos y usa el servicio PdfService
        private function generar_pdf($descargar){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //recibe id o token de la URL
        $id_boleta = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $token = isset($_GET['token']) ? trim($_GET['token']) : '';

        //instancia modelos
        $boletaModel = new Boleta();
        
        //busca por token (seguro) o por id (interno)
        if ($token !== '') {
            $cabecera = $boletaModel->obtener_por_token($token);
        } elseif ($id_boleta !== 0) {
            $cabecera = $boletaModel->obtener_por_id($id_boleta);
        } else {
            header('Location: ' . BASE_URL . 'index.php?controller=home&action=mostrar');
            exit;
        }

        //si no se encontró la boleta
        if (!$cabecera) {
            exit('Boleta no encontrada o token inválido.');
        }

        //obtenemos el id real de la boleta
        $id_boleta = $cabecera['id_boleta']; 
        $ventaModel = new Venta();
        $detalle = $ventaModel->obtener_por_boleta($id_boleta);

        //arma la estructura para la vista
        $boleta = [
            'numero_boleta' => $cabecera['numero_boleta'],
            'fecha'         => $cabecera['fecha_emision'],
            'cliente'       => $cabecera['cliente'] !== null ? $cabecera['cliente'] : 'fuera del sistema',
            'clienteDNI'    => $cabecera['dni_cliente'],
            'empleado'      => $cabecera['empleado'] !== null ? $cabecera['empleado'] : 'fuera del sistema',
            'items'         => [],
            'total'         => $cabecera['total'],
            'qr'            => '' //inicializamos vacío
        ];

        while ($fila = $detalle->fetch_assoc()) {
            $boleta['items'][] = [
                'nombre'   => $fila['producto'] !== null ? $fila['producto'] : 'Producto eliminado',
                'cantidad' => $fila['cantidad'],
                'precio'   => $fila['precio_unitario'],
                'subtotal' => $fila['precio_unitario'] * $fila['cantidad'],
            ];
        }

        //=========================================================
        // 🚀 GENERACIÓN DEL QR
        //=========================================================
        
        //1. usamos el token en lugar del id en la URL pública
        $urlBoleta = BASE_URL . 'index.php?controller=boleta&action=mostrar&token=' . $cabecera['token'];
        
        //2. instanciamos el servicio
        $qrService = new QrService();
        
        //3. definimos la ruta física (usando realpath para limpiar los ..)
        $carpetaQrs = realpath(__DIR__ . '/../../assets/qrs');
        $rutaFisicaQr = $carpetaQrs . DIRECTORY_SEPARATOR . 'boleta_' . $id_boleta . '.png';
        
        //4. generamos el QR
        $qrService->generar($urlBoleta, $rutaFisicaQr);
        
        //5. pasamos la RUTA FÍSICA a la vista (Dompdf la leerá del disco)
        $boleta['qr'] = $rutaFisicaQr;

        //=========================================================

        //carga Dompdf
        require_once VENDOR_PATH . '/autoload.php';
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        //captura el HTML de la vista
        ob_start();
        include APP_PATH . '/views/boleta.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nombreArchivo = 'boleta_' . $boleta['numero_boleta'] . '.pdf';
        $dompdf->stream($nombreArchivo, ['Attachment' => $descargar]);
        exit;
    }
}