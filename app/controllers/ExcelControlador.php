<?php
class ExcelControlador {

    //exporta las boletas a Excel (con filtro opcional por mes)
    public function ventas(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        //recibe el filtro por mes desde la URL
        $mesFiltro = isset($_GET['mes']) ? trim($_GET['mes']) : '';

        //instancia el modelo de boletas
        $boletaModel = new Boleta();
        $boletas = $boletaModel->obtener_por_mes($mesFiltro);

        //prepara los encabezados del Excel
        $encabezados = ['N° BOLETA', 'FECHA', 'CLIENTE', 'EMPLEADO', 'TOTAL'];

        //prepara las filas del Excel
        $filas = [];
        while ($b = $boletas->fetch_assoc()) {
            $cliente = $b['cliente'] !== null ? $b['cliente'] : $b['dni_cliente'] . ' | fuera del sistema';
            $empleado = $b['empleado'] !== null ? $b['empleado'] : 'fuera del sistema';

            $filas[] = [
                $b['numero_boleta'],
                $b['fecha_emision'],
                $cliente,
                $empleado,
                $b['total'],
            ];
        }

        //nombre del archivo con el mes si hay filtro
        $sufijo = $mesFiltro !== '' ? '_' . $mesFiltro : '';
        $nombreArchivo = 'boletas' . $sufijo . '_' . date('Ymd_His') . '.xlsx';

        //usa el servicio para generar el Excel
        $excel = new ExcelService();
        $excel->generar($encabezados, $filas, $nombreArchivo);
        exit;
    }
}