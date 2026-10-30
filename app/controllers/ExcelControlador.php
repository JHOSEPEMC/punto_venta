<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelControlador {
    public function ventas(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once VENDOR_PATH . '/autoload.php';

        $mesFiltro = trim($_GET['mes'] ?? '');
        $boletas = (new BoletaDAO())->obtener_por_mes($mesFiltro);

        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setTitle('Boletas');

        $hoja->setCellValue('A1', 'N° BOLETA');
        $hoja->setCellValue('B1', 'FECHA');
        $hoja->setCellValue('C1', 'CLIENTE');
        $hoja->setCellValue('D1', 'EMPLEADO');
        $hoja->setCellValue('E1', 'TOTAL');

        $hoja->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF7800']],
        ]);

        $fila = 2;
        foreach ($boletas as $b) { // $b es Objeto
            $cliente = $b->cliente_nombre !== null ? $b->cliente_nombre : $b->dni_cliente . ' | fuera del sistema';
            $empleado = $b->empleado_nombre !== null ? $b->empleado_nombre : 'fuera del sistema';

            $hoja->setCellValue('A' . $fila, $b->numero_boleta);
            $hoja->setCellValue('B' . $fila, $b->fecha_emision);
            $hoja->setCellValue('C' . $fila, $cliente);
            $hoja->setCellValue('D' . $fila, $empleado);
            $hoja->setCellValue('E' . $fila, $b->total);
            $fila++;
        }

        foreach (range('A', 'E') as $col) $hoja->getColumnDimension($col)->setAutoSize(true);

        $sufijo = $mesFiltro !== '' ? '_' . $mesFiltro : '';
        $nombreArchivo = 'boletas' . $sufijo . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}