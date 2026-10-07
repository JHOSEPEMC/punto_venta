<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class BoletaControlador {

    public function mostrar(){
        $this->generar_pdf(false);
    }

    public function descargar(){
        $this->generar_pdf(true);
    }

    private function generar_pdf($descargar){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //recibe el id de la boleta
        $id_boleta = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id_boleta === 0) {
            header('Location: ' . BASE_URL . 'index.php?controller=ventas&action=mostrar');
            exit;
        }

        //carga modelos
        $boletaModel = new Boleta();
        $ventaModel  = new Venta();

        //obtiene la cabecera y el detalle
        $cabecera = $boletaModel->obtener_por_id($id_boleta);
        if (!$cabecera) {
            header('Location: ' . BASE_URL . 'index.php?controller=ventas&action=mostrar');
            exit;
        }

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
        ];

        while ($fila = $detalle->fetch_assoc()) {
            $boleta['items'][] = [
                'nombre'   => $fila['producto'] !== null ? $fila['producto'] : 'Producto eliminado',
                'cantidad' => $fila['cantidad'],
                'precio'   => $fila['precio_unitario'],
                'subtotal' => $fila['precio_unitario'] * $fila['cantidad'],
            ];
        }

        //carga Dompdf
        require_once VENDOR_PATH . '/autoload.php';
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

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