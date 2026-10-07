<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelControlador {

    //exporta las boletas a Excel (con filtro opcional por mes)
    public function ventas(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        require_once VENDOR_PATH . '/autoload.php';

        $mesFiltro = isset($_GET['mes']) ? trim($_GET['mes']) : '';

        $boletaModel = new Boleta();
        $boletas = $boletaModel->obtener_por_mes($mesFiltro);

        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setTitle('Boletas');

        //encabezados
        $hoja->setCellValue('A1', 'N° BOLETA');
        $hoja->setCellValue('B1', 'FECHA');
        $hoja->setCellValue('C1', 'CLIENTE');
        $hoja->setCellValue('D1', 'EMPLEADO');
        $hoja->setCellValue('E1', 'TOTAL');

        $estiloEncabezado = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF7800'],
            ],
        ];
        $hoja->getStyle('A1:E1')->applyFromArray($estiloEncabezado);

        $fila = 2;
        while ($b = $boletas->fetch_assoc()) {
            $cliente = $b['cliente'] !== null ? $b['cliente'] : $b['dni_cliente'] . ' | fuera del sistema';
            $empleado = $b['empleado'] !== null ? $b['empleado'] : 'fuera del sistema';

            $hoja->setCellValue('A' . $fila, $b['numero_boleta']);
            $hoja->setCellValue('B' . $fila, $b['fecha_emision']);
            $hoja->setCellValue('C' . $fila, $cliente);
            $hoja->setCellValue('D' . $fila, $empleado);
            $hoja->setCellValue('E' . $fila, $b['total']);
            $fila++;
        }

        foreach (range('A', 'E') as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }

        $sufijo = $mesFiltro !== '' ? '_' . $mesFiltro : '';
        $nombreArchivo = 'boletas' . $sufijo . '_' . date('Ymd_His') . '.xlsx';

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