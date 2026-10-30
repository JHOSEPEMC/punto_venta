<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { font-family: 'Courier New', Courier, monospace; font-size: 10px; line-height: 1.2; margin: 0; padding: 10px; width: 95%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        hr { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { padding: 2px 0; }
        .qr-container { text-align: center; margin-top: 15px; }
        .qr-img { width: 120px; height: 120px; }
    </style>
</head>
<body>

    <div class="text-center bold mb-1">
        <?= NOMBRE_NEGOCIO ?>
    </div>
    <div class="text-center mb-2">
        RUC: 10456789123<br>
        Av. Principal 123 - Lima<br>
        Teléfono: (01) 456-7890
    </div>

    <hr>

    <div class="text-center bold mb-1">
        BOLETA DE VENTA ELECTRÓNICA<br>
        <?= htmlspecialchars($cabecera->numero_boleta) ?>
    </div>

    <hr>

    <div class="mb-1">
        <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($cabecera->fecha_emision)) ?><br>
        <strong>Cajero:</strong> <?= htmlspecialchars($cabecera->empleado_nombre ?? 'fuera del sistema') ?><br>
        
        <strong>Cliente:</strong> 
        <?php if ($cabecera->dni_cliente === 'GENERICO'): ?>
            Clientes Varios<br>
            <strong>DNI/RUC:</strong> -
        <?php else: ?>
            <?= htmlspecialchars($cabecera->cliente_nombre ?? 'fuera del sistema') ?><br>
            <strong>DNI/RUC:</strong> <?= htmlspecialchars($cabecera->dni_cliente) ?>
        <?php endif; ?>
    </div>

    <hr>

    <table>
        <thead>
            <tr class="bold">
                <th class="text-left" style="width: 15%;">Cant</th>
                <th class="text-left" style="width: 55%;">Descripción</th>
                <th class="text-right" style="width: 30%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalle as $item): ?>
                <tr>
                    <td class="text-left"><?= $item->cantidad ?></td>
                    <td class="text-left"><?= htmlspecialchars(substr($item->producto_nombre ?? 'Producto eliminado', 0, 25)) ?></td>
                    <td class="text-right"><?= MONEDA ?> <?= number_format($item->precio_unitario * $item->cantidad, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <div class="text-right bold mb-2">
        TOTAL: <?= MONEDA ?> <?= number_format($cabecera->total, 2) ?>
    </div>
    
    <div class="text-left mb-2" style="font-size: 9px;">
        OP. GRAVADA: <?= MONEDA ?> <?= number_format($cabecera->total / 1.18, 2) ?><br>
        IGV (18%): <?= MONEDA ?> <?= number_format($cabecera->total - ($cabecera->total / 1.18), 2) ?>
    </div>

    <hr>

    <div class="qr-container">
        <?php 
            if (!empty($rutaFisicaQr) && file_exists($rutaFisicaQr)) {
                $datosQr = file_get_contents($rutaFisicaQr);
                $base64Qr = 'data:image/png;base64,' . base64_encode($datosQr);
            } else {
                $base64Qr = '';
            }
        ?>
        <?php if($base64Qr): ?>
            <img src="<?= $base64Qr ?>" class="qr-img">
            <div class="mb-1" style="font-size: 8px;">Escanea para ver tu boleta</div>
        <?php endif; ?>
    </div>

    <hr>

    <div class="text-center" style="font-size: 8px;">
        Autorizado mediante Resolución Nro.<br>
        034-005-0005315/SUNAT<br><br>
        Representación impresa de la Boleta<br>
        de Venta Electrónica.<br>
        ¡Gracias por su compra!
    </div>

</body>
</html>