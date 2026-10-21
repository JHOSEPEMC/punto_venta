<?php
//imports de la librería endroid (deben ir al inicio del archivo)
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrService {

    //genera un código QR y lo guarda en una ruta física
    public function generar($texto, $rutaGuardado){
        //carga el autoload de Composer para que encuentre la librería
        require_once VENDOR_PATH . '/autoload.php';

        //crea el objeto del QR con el texto
        $qrCode = new QrCode($texto);

        //crea el escritor de imagen en formato PNG
        $writer = new PngWriter();

        //genera la imagen
        $result = $writer->write($qrCode);

        //guarda la imagen en el disco
        $result->saveToFile($rutaGuardado);

        return true;
    }
}