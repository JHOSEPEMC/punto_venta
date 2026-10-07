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

        //recibe el id de la boleta desde la URL
        $id_boleta = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        //si no hay id, redirige al listado de ventas
        if ($id_boleta === 0) {
            header('Location: ' . BASE_URL . 'index.php?controller=ventas&action=mostrar');
            exit;
        }

        //instancia modelos
        $boletaModel = new Boleta();
        $ventaModel  = new Venta();

        //obtiene la cabecera y el detalle de la boleta
        $cabecera = $boletaModel->obtener_por_id($id_boleta);
        if (!$cabecera) {
            header('Location: ' . BASE_URL . 'index.php?controller=ventas&action=mostrar');
            exit;
        }

        $detalle = $ventaModel->obtener_por_boleta($id_boleta);

        //arma la estructura que la vista necesita
        $boleta = [
            'numero_boleta' => $cabecera['numero_boleta'],
            'fecha'         => $cabecera['fecha_emision'],
            'cliente'       => $cabecera['cliente'] !== null ? $cabecera['cliente'] : 'fuera del sistema',
            'clienteDNI'    => $cabecera['dni_cliente'],
            'empleado'      => $cabecera['empleado'] !== null ? $cabecera['empleado'] : 'fuera del sistema',
            'items'         => [],
            'total'         => $cabecera['total'],
        ];

        //recorre el detalle y calcula subtotales
        while ($fila = $detalle->fetch_assoc()) {
            $boleta['items'][] = [
                'nombre'   => $fila['producto'] !== null ? $fila['producto'] : 'Producto eliminado',
                'cantidad' => $fila['cantidad'],
                'precio'   => $fila['precio_unitario'],
                'subtotal' => $fila['precio_unitario'] * $fila['cantidad'],
            ];
        }

        //captura el HTML generado por la vista
        ob_start();
        include APP_PATH . '/views/boleta.php';
        $html = ob_get_clean();

        //usa el servicio para generar el PDF
        $pdf = new PdfService();
        $pdf->generar($html, 'boleta_' . $boleta['numero_boleta'] . '.pdf', $descargar);
        exit;
    }
}