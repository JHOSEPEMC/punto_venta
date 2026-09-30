<?php
//imports de Dompdf (deben ir al inicio del archivo)
use Dompdf\Dompdf;
use Dompdf\Options;

class BoletaControlador {

    //muestra la página con los botones "Ver" y "Descargar"
    public function resultado(){
        //inicia la sesión
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica que exista una boleta
        if (!isset($_SESSION['boleta'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        //pasa la boleta a la vista
        $boleta = $_SESSION['boleta'];

        //carga la vista con los botones
        require_once APP_PATH . '/views/boleta_resultado.php';
    }

    //genera y muestra el PDF en el navegador
    public function ver(){
        $this->generar_pdf(false);
    }

    //genera y descarga el PDF
    public function descargar(){
        $this->generar_pdf(true);
    }

    //método privado que genera el PDF (compartido por ver y descargar)
    private function generar_pdf($descargar){
        //inicia la sesión
        if (session_status() === PHP_SESSION_NONE) session_start();

        //verifica que exista una boleta
        if (!isset($_SESSION['boleta'])) {
            header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
            exit;
        }

        //recoge los datos de la boleta
        $boleta = $_SESSION['boleta'];

        //carga el autoload de Composer (Dompdf)
        require_once VENDOR_PATH . '/autoload.php';

        //configura Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        //captura el HTML de la vista de la boleta con output buffering
        ob_start();
        include APP_PATH . '/views/boleta.php';
        $html = ob_get_clean();

        //carga el HTML y renderiza
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //nombre del archivo con DNI del cliente y fecha
        $nombreArchivo = 'boleta_' . $boleta['clienteDNI'] . '_' . date('Ymd') . '.pdf';

        //Attachment true = descargar, false = mostrar en navegador
        $dompdf->stream($nombreArchivo, ['Attachment' => $descargar]);

        //NO borramos la boleta de la sesión aquí, para poder ver y descargar varias veces
        exit;
    }

    //limpia la boleta de la sesión (opcional, lo llamas desde el resultado)
    public function limpiar(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['boleta']);
        header('Location: ' . BASE_URL . 'index.php?controller=caja&action=mostrar');
        exit;
    }
}