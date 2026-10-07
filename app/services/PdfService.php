<?php
//imports de Dompdf (deben ir al inicio del archivo)
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService {

    //genera un PDF a partir de un HTML y lo envía al navegador
    public function generar($html, $nombreArchivo, $descargar){
        //carga el autoload de Composer para usar Dompdf
        require_once VENDOR_PATH . '/autoload.php';

        //configura Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        //carga el HTML y lo convierte a PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        //true = descargar, false = mostrar en navegador
        $dompdf->stream($nombreArchivo, ['Attachment' => $descargar]);
    }
}