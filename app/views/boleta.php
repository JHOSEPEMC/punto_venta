<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /*estilos básicos para la boleta*/
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #e65c00; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #ffcc99; }
        .total { text-align: right; font-size: 16px; font-weight: bold; }
        .info { margin-top: 10px; }
    </style>
</head>
<body>
    <h1>MINIMARKET PUNTO DE VENTA</h1>
    <p style="text-align:center;">Boleta de Venta</p>

    <div class="info">
        <p><strong>Fecha:</strong> <?= htmlspecialchars($boleta['fecha']) ?></p>
        <p><strong>Cliente DNI:</strong> <?= htmlspecialchars($boleta['clienteDNI']) ?></p>
        <p><strong>Empleado DNI:</strong> <?= htmlspecialchars($boleta['empleado']) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="background-color: orange;">Producto</th>
                <th style="background-color: orange;">Cantidad</th>
                <th style="background-color: orange;">Precio</th>
                <th style="background-color: orange;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($boleta['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>S/. <?= number_format($item['precio'], 2) ?></td>
                    <td>S/. <?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">TOTAL: S/. <?= number_format($boleta['total'], 2) ?></p>
</body>
</html>