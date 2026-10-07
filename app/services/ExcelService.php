<?php
//imports de PhpSpreadsheet (deben ir al inicio del archivo)
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelService {

    //genera un Excel a partir de encabezados + filas y lo envía al navegador
    public function generar($encabezados, $filas, $nombreArchivo){
        //carga el autoload de Composer para usar PhpSpreadsheet
        require_once VENDOR_PATH . '/autoload.php';

        //crea el libro y la hoja activa
        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();

        //escribe los encabezados en la fila 1
        $columna = 'A';
        foreach ($encabezados as $encabezado) {
            $hoja->setCellValue($columna . '1', $encabezado);
            $columna++;
        }

        //aplica el estilo naranja a los encabezados
        $ultimaCol = chr(ord('A') + count($encabezados) - 1);
        $hoja->getStyle('A1:' . $ultimaCol . '1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF7800'],
            ],
        ]);

        //escribe las filas a partir de la fila 2
        $fila = 2;
        foreach ($filas as $datos) {
            $columna = 'A';
            foreach ($datos as $valor) {
                $hoja->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }

        //auto-ajusta el ancho de todas las columnas usadas
        foreach (range('A', $ultimaCol) as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }

        //envía las cabeceras HTTP para descargar el archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        //genera el archivo y lo envía al navegador
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        //libera memoria
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}