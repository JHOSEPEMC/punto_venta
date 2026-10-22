<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Estilos para impresora térmica de 80mm */
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace; /* Fuente de ticket */
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
            width: 95%; /*Para mostrar la boleta completa*/
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        hr { border-top: 1px dashed #000; margin: 5px 0; }
        
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { padding: 2px 0; }
        
        .logo { max-width: 150px; max-height: 80px; margin-bottom: 5px; }
        
        .qr-container { text-align: center; margin-top: 15px; }
        .qr-img { width: 120px; height: 120px; }
    </style>
</head>
<body>

    <!-- Datos del Emisor -->
    <div class="text-center bold mb-1">
        MINIMARKET "EL PROVEEDOR"
    </div>
    <div class="text-center mb-2">
        RUC: 10456789123<br>
        Av. Principal 123 - Lima<br>
        Teléfono: (01) 456-7890
    </div>

    <hr>

    <!-- Tipo de Documento -->
    <div class="text-center bold mb-1">
        BOLETA DE VENTA ELECTRÓNICA<br>
        <?= htmlspecialchars($boleta['numero_boleta']) ?>
    </div>

    <hr>

    <!-- Datos de la Venta -->
    <div class="mb-1">
        <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($boleta['fecha'])) ?><br>
        <strong>Cajero:</strong> <?= htmlspecialchars($boleta['empleado']) ?><br>
        
        <!-- Lógica para el cliente (Genérico vs Registrado) -->
        <strong>Cliente:</strong> 
        <?php if ($boleta['clienteDNI'] === 'GENERICO'): ?>
            Clientes Varios<br>
            <strong>DNI/RUC:</strong> -
        <?php else: ?>
            <?= htmlspecialchars($boleta['cliente']) ?><br>
            <strong>DNI/RUC:</strong> <?= htmlspecialchars($boleta['clienteDNI']) ?>
            <?php if(!empty($boleta['ruc'])): ?>
                <br><strong>RUC:</strong> <?= htmlspecialchars($boleta['ruc']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <hr>

    <!-- Detalle de Productos -->
    <table>
        <thead>
            <tr class="bold">
                <th class="text-left" style="width: 15%;">Cant</th>
                <th class="text-left" style="width: 55%;">Descripción</th>
                <th class="text-right" style="width: 30%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($boleta['items'] as $item): ?>
                <tr>
                    <td class="text-left"><?= $item['cantidad'] ?></td>
                    <td class="text-left"><?= htmlspecialchars(substr($item['nombre'], 0, 25)) ?></td>
                    <td class="text-right">S/ <?= number_format($item['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <!-- Totales -->
    <div class="text-right bold mb-2">
        TOTAL: S/ <?= number_format($boleta['total'], 2) ?>
    </div>
    
    <!-- (Opcional) Puedes calcular el IGV aquí si lo deseas -->
    <div class="text-left mb-2" style="font-size: 9px;">
        OP. GRAVADA: S/ <?= number_format($boleta['total'] / 1.18, 2) ?><br>
        IGV (18%): S/ <?= number_format($boleta['total'] - ($boleta['total'] / 1.18), 2) ?>
    </div>

    <hr>

    <!-- Código QR -->
    <div class="qr-container">
        <?php 
            //lee la imagen física y la convierte a base64 para que Dompdf la entienda
            if (!empty($boleta['qr']) && file_exists($boleta['qr'])) {
                $datosQr = file_get_contents($boleta['qr']);
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

    <!-- Pie de Página Legal -->
    <div class="text-center" style="font-size: 8px;">
        Autorizado mediante Resolución Nro.<br>
        034-005-0005315/SUNAT<br><br>
        Representación impresa de la Boleta<br>
        de Venta Electrónica.<br>
        Consulte su documento en:<br>
        www.tu-minimarket.com/boleta<br><br>
        ¡Gracias por su compra!
    </div>

    <script>
        // Cierra la pestaña automáticamente después de 3 segundos
        setTimeout(function(){ 
            window.close(); 
        }, 3000);
    </script>

</body>
</html>