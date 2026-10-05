<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelControlador {

    //exporta el listado de ventas a Excel (con filtro opcional por mes)
    public function ventas(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once VENDOR_PATH . '/autoload.php';

        //recibe el filtro por mes desde la URL
        $mesFiltro = isset($_GET['mes']) ? trim($_GET['mes']) : '';

        //instancia el modelo y aplica el filtro
        $ventaModel = new Venta();
        $ventas = $ventaModel->obtener_por_mes($mesFiltro);

        //crea el libro y la hoja
        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setTitle('Ventas');

        //encabezados
        $hoja->setCellValue('A1', 'ID');
        $hoja->setCellValue('B1', 'EMPLEADO');
        $hoja->setCellValue('C1', 'CLIENTE');
        $hoja->setCellValue('D1', 'PRODUCTO');
        $hoja->setCellValue('E1', 'CANTIDAD');
        $hoja->setCellValue('F1', 'FECHA');

        //estilo de encabezados
        $estiloEncabezado = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF7800'],
            ],
        ];
        $hoja->getStyle('A1:F1')->applyFromArray($estiloEncabezado);

        //recorre las ventas
        $fila = 2;
        while ($venta = $ventas->fetch_assoc()) {
            $cliente = $venta['cliente'] === null
                ? $venta['dni_cliente'] . ' | fuera del sistema'
                : $venta['cliente'];
            $empleado = $venta['empleado'] === null
                ? 'fuera del sistema'
                : $venta['empleado'];
            $producto = $venta['producto'] === null
                ? 'Producto ID ' . $venta['id_producto'] . ' | fuera del sistema'
                : $venta['producto'];

            $hoja->setCellValue('A' . $fila, $venta['id_venta']);
            $hoja->setCellValue('B' . $fila, $empleado);
            $hoja->setCellValue('C' . $fila, $cliente);
            $hoja->setCellValue('D' . $fila, $producto);
            $hoja->setCellValue('E' . $fila, $venta['cantidad']);
            $hoja->setCellValue('F' . $fila, $venta['fecha_venta']);
            $fila++;
        }

        //auto-ajusta ancho de columnas
        foreach (range('A', 'F') as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }

        //nombre del archivo con el mes (si hay filtro)
        $sufijo = $mesFiltro !== '' ? '_' . $mesFiltro : '';
        $nombreArchivo = 'ventas' . $sufijo . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        exit;
    }
}