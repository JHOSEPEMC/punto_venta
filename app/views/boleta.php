<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #e65c00; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #ffcc99; }
        .total { text-align: right; font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>MINIMARKET PUNTO DE VENTA</h1>
    <p style="text-align:center;">Boleta N° <?= htmlspecialchars($boleta['numero_boleta']) ?></p>

    <p><strong>Fecha:</strong> <?= htmlspecialchars($boleta['fecha']) ?></p>
    <p><strong>Cliente:</strong> <?= htmlspecialchars($boleta['cliente']) ?> (<?= htmlspecialchars($boleta['clienteDNI']) ?>)</p>
    <p><strong>Empleado:</strong> <?= htmlspecialchars($boleta['empleado']) ?></p>

    <table>
        <thead>
            <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            <?php foreach ($boleta['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>S/. <?= number_format($item['precio'], 2) ?></td>
                    <td>S/. <?= number_format($item['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">TOTAL: S/. <?= number_format($boleta['total'], 2) ?></p>
</body>
</html>