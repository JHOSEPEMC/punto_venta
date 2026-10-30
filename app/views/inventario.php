<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Inventario de Productos</h2>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card p-3 h-100">
                <h6 class="fw-bold text-center">Distribución por Categoría</h6>
                <canvas id="graficaCategorias"></canvas>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card p-3">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-sm btn-naranja filtro-cat active" data-cat="todos">Todos</button>
                    <?php foreach ($categorias as $cat): ?>
                        <button class="btn btn-sm btn-outline-secondary filtro-cat" data-cat="<?= strtolower(htmlspecialchars($cat->nombre)) ?>">
                            <?= htmlspecialchars($cat->nombre) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody id="tablaInventario">
                            <?php foreach ($productos as $p): ?>
                                <tr class="fila-producto" data-cat="<?= strtolower(htmlspecialchars($p->nombre_categoria)) ?>">
                                    <td><?= $p->id ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($p->nombre) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($p->nombre_categoria) ?></span></td>
                                    <td><?= MONEDA ?> <?= number_format($p->precio, 2) ?></td>
                                    <td><?= $p->stock ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Lógica de filtros
    document.querySelectorAll('.filtro-cat').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filtro-cat').forEach(b => b.classList.remove('btn-naranja', 'active'));
            document.querySelectorAll('.filtro-cat').forEach(b => b.classList.add('btn-outline-secondary'));
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-naranja', 'active');

            let cat = this.getAttribute('data-cat');
            document.querySelectorAll('.fila-producto').forEach(fila => {
                if (cat === 'todos' || fila.getAttribute('data-cat') === cat) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    });

    // Gráfica de Pie
    const ctxCat = document.getElementById('graficaCategorias').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($labelsCat) ?>,
            datasets: [{
                data: <?= json_encode($valoresCat) ?>,
                backgroundColor: ['#FF7800', '#FFB366', '#FFD180', '#E65C00', '#FFCC80', '#FFE0B2', '#F57C00'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>