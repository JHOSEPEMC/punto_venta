<?php
class BoletaControlador {
    public function mostrar(){ $this->generar_pdf(false); }
    public function descargar(){ $this->generar_pdf(true); }

    private function generar_pdf($descargar){
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_boleta = (int)($_GET['id'] ?? 0);
        $token = trim($_GET['token'] ?? '');

        $boletaDAO = new BoletaDAO();
        $ventaDAO = new VentaDAO();
        
        $cabecera = $token !== '' ? $boletaDAO->obtener_por_token($token) : $boletaDAO->obtener_por_id($id_boleta);

        if (!$cabecera) exit('Boleta no encontrada o token inválido.');

        $detalle = $ventaDAO->obtener_por_boleta($cabecera->id);

        $boleta = [
            'numero_boleta' => $cabecera->numero_boleta,
            'fecha'         => $cabecera->fecha_emision,
            'cliente'       => $cabecera->cliente_nombre ?? 'fuera del sistema',
            'clienteDNI'    => $cabecera->dni_cliente,
            'empleado'      => $cabecera->empleado_nombre ?? 'fuera del sistema',
            'items'         => [],
            'total'         => $cabecera->total,
            'qr'            => ''
        ];

        foreach ($detalle as $fila) { // $fila es un Objeto Venta
            $boleta['items'][] = [
                'nombre'   => $fila->producto_nombre ?? 'Producto eliminado',
                'cantidad' => $fila->cantidad,
                'precio'   => $fila->precio_unitario,
                'subtotal' => $fila->precio_unitario * $fila->cantidad,
            ];
        }

        $urlBoleta = BASE_URL . 'index.php?controller=boleta&action=mostrar&token=' . $cabecera->token;
        $qrService = new QrService();
        $carpetaQrs = realpath(__DIR__ . '/../../assets/qrs');
        $rutaFisicaQr = $carpetaQrs . DIRECTORY_SEPARATOR . 'boleta_' . $cabecera->id . '.png';
        $qrService->generar($urlBoleta, $rutaFisicaQr);
        $boleta['qr'] = $rutaFisicaQr;

        require_once VENDOR_PATH . '/autoload.php';
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        ob_start();
        include APP_PATH . '/views/boleta.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 226, 800], 'portrait'); 
        $dompdf->render();

        if (!$descargar) {
            $output = $dompdf->output();
            $base64 = base64_encode($output);
            echo "<html><head><title>Imprimiendo...</title></head><body style='margin:0;'><iframe id='pdfFrame' src='data:application/pdf;base64,{$base64}#print' style='width:100vw; height:100vh; border:none;'></iframe><script>window.onload=function(){setTimeout(function(){document.getElementById('pdfFrame').contentWindow.print();},1000);};</script></body></html>";
            exit;
        }

        $dompdf->stream('boleta_' . $cabecera->numero_boleta . '.pdf', ['Attachment' => true]);
        exit;
    }
}