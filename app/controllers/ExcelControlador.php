<?php
//imports de PhpSpreadsheet (deben ir al inicio del archivo)
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ExcelControlador {

    //exporta el listado de ventas a Excel
    public function ventas(){
        //inicia la sesión (por consistencia)
        if (session_status() === PHP_SESSION_NONE) session_start();

        //carga el autoload de Composer (PhpSpreadsheet)
        require_once VENDOR_PATH . '/autoload.php';

        //obtiene las ventas desde el modelo
        $ventaModel = new Venta();
        $ventas = $ventaModel->obtener_todas();

        //crea un nuevo libro de Excel
        $spreadsheet = new Spreadsheet();
        //selecciona la hoja activa
        $hoja = $spreadsheet->getActiveSheet();
        //le pone nombre a la hoja
        $hoja->setTitle('Ventas');

        //escribe los encabezados en la fila 1
        $hoja->setCellValue('A1', 'ID');
        $hoja->setCellValue('B1', 'EMPLEADO');
        $hoja->setCellValue('C1', 'CLIENTE');
        $hoja->setCellValue('D1', 'PRODUCTO');
        $hoja->setCellValue('E1', 'CANTIDAD');
        $hoja->setCellValue('F1', 'FECHA');

        //da estilo a los encabezados (fondo naranja y texto en negrita)
        $estiloEncabezado = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF7800'],  // naranja
            ],
        ];
        $hoja->getStyle('A1:F1')->applyFromArray($estiloEncabezado);

        //recorre las ventas y las escribe a partir de la fila 2
        $fila = 2;
        while ($venta = $ventas->fetch_assoc()) {
            //si el cliente fue eliminado, mostrar "fuera del sistema"
            $cliente = $venta['cliente'] === null
                ? $venta['dni_cliente'] . ' | fuera del sistema'
                : $venta['cliente'];

            //si el empleado fue eliminado
            $empleado = $venta['empleado'] === null
                ? 'fuera del sistema'
                : $venta['empleado'];

            //si el producto fue eliminado
            $producto = $venta['producto'] === null
                ? 'Producto ID ' . $venta['id_producto'] . ' | fuera del sistema'
                : $venta['producto'];

            //escribe los datos de esa venta
            $hoja->setCellValue('A' . $fila, $venta['id_venta']);
            $hoja->setCellValue('B' . $fila, $empleado);
            $hoja->setCellValue('C' . $fila, $cliente);
            $hoja->setCellValue('D' . $fila, $producto);
            $hoja->setCellValue('E' . $fila, $venta['cantidad']);
            $hoja->setCellValue('F' . $fila, $venta['fecha_venta']);

            $fila++;
        }

        //ajusta el ancho de las columnas automáticamente
        foreach (range('A', 'F') as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }

        //prepara las cabeceras HTTP para forzar la descarga
        $nombreArchivo = 'ventas_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        //crea el writer y envía el archivo al navegador
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        //libera memoria
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        exit;
    }
}