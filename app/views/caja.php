<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold m-0 text-naranja">Caja Abierta</h4>
        <a href="<?= BASE_URL ?>index.php?controller=caja&action=cerrar_caja" 
            class="btn btn-outline-danger btn-sm"
            onclick="return confirm('¿Seguro que deseas cerrar tu turno? No podrás vender hasta que el admin te asigne una nueva caja.');">
            🔒 Cerrar Turno
        </a>
    </div>

    <?php if (!empty($mensajeCaja)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensajeCaja) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- COLUMNA IZQUIERDA: PRODUCTOS -->
        <div class="col-lg-8 mb-4">
            <div class="card p-3 mb-3">
                <label class="fw-bold mb-2">Cliente:</label>
                <div class="d-flex gap-2">
                    <select class="form-select border-warning" id="selectCliente" required>
                        <option value="GENERICO" <?= $clienteActual == 'GENERICO' ? 'selected' : '' ?>>-- Cliente Genérico (Venta Rápida) --</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c->dni ?>" <?= $clienteActual == $c->dni ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c->nombre) ?> (<?= $c->dni ?>) 
                                <?= !empty($c->ruc) ? ' - RUC: '.htmlspecialchars($c->ruc) : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-naranja fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                        + Nuevo
                    </button>
                </div>
                <div class="form-text mt-1">Selecciona un cliente registrado o usa el genérico para ventas al paso.</div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold m-0">Catálogo de Productos</h4>
                <input type="text" id="buscador" class="form-control w-50" placeholder="🔍 Buscar producto...">
            </div>

            <div class="row g-3" id="listaProductos">
                <?php foreach ($productos as $p): ?>
                    <div class="col-6 col-md-4 col-xl-3 producto-item" data-nombre="<?= strtolower(htmlspecialchars($p->nombre)) ?>">
                        <div class="producto-btn" onclick="agregarAlCarrito(<?= $p->id ?>, '<?= addslashes($p->nombre) ?>', <?= $p->precio ?>, <?= $p->stock ?>)">
                            <div class="display-5 mb-2"><?= $p->icono ?></div>
                            <div class="fw-bold" style="font-size: 0.9rem; line-height: 1.2;"><?= htmlspecialchars($p->nombre) ?></div>
                            <div class="precio mt-2"><?= MONEDA ?> <?= number_format($p->precio, 2) ?></div>
                            <div class="small text-muted mt-1">Stock: <?= $p->stock ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- COLUMNA DERECHA: CARRITO -->
        <div class="col-lg-4">
            <div class="carrito-sticky">
                <div class="p-3 bg-naranja text-white rounded-top">
                    <h5 class="m-0">🛒 Carrito de Venta</h5>
                </div>
                <div class="carrito-lista" id="carritoLista">
                    <p class="text-center text-muted mt-4" id="carritoVacio">El carrito está vacío.</p>
                </div>
                <div class="p-3 border-top bg-light rounded-bottom">
                    <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
                        <span>Total:</span>
                        <span class="text-naranja" id="totalCarrito"><?= MONEDA ?> 0.00</span>
                    </div>
                    <button class="btn btn-success w-100 mb-2" onclick="confirmarVenta()" id="btnConfirmar" disabled>✅ Confirmar Venta</button>
                    <button class="btn btn-outline-danger w-100" onclick="vaciarCarrito()" id="btnVaciar" disabled>🗑️ Vaciar Carrito</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVO CLIENTE -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-naranja text-white">
                <h5 class="modal-title fw-bold">Registrar Nuevo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alertaCliente"></div>
                <form id="formNuevoCliente">
                    <div class="mb-3">
                        <label class="fw-bold">DNI:</label>
                        <input type="text" name="dni" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Nombre Completo:</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Teléfono (Opcional):</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-naranja" id="btnGuardarCliente">Guardar Cliente</button>
            </div>
        </div>
    </div>
</div>

<form id="formConfirmar" action="<?= BASE_URL ?>index.php?controller=caja&action=confirmar" method="POST" target="_blank" class="d-none">
    <input type="hidden" name="cliente" id="inputCliente">
    <input type="hidden" name="carrito" id="inputCarrito">
</form>

<script>
    // Variables globales
    let carrito = {}; 
    let clienteSeleccionado = document.getElementById('selectCliente').value;

    // Filtrar productos
    document.getElementById('buscador').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase();
        document.querySelectorAll('.producto-item').forEach(item => {
            let nombre = item.getAttribute('data-nombre');
            item.style.display = nombre.includes(filtro) ? '' : 'none';
        });
    });

    // Detectar cambio de cliente
    document.getElementById('selectCliente').addEventListener('change', function() {
        let nuevoCliente = this.value;
        if (clienteSeleccionado !== '' && clienteSeleccionado !== nuevoCliente) {
            if (Object.keys(carrito).length > 0) {
                if(confirm('Cambiaste de cliente. ¿Deseas vaciar el carrito actual?')) {
                    carrito = {};
                    renderizarCarrito();
                }
            }
        }
        clienteSeleccionado = nuevoCliente;
    });

    // Agregar producto
    function agregarAlCarrito(id, nombre, precio, stock) {
        if (carrito[id]) {
            if (carrito[id].cantidad >= stock) {
                alert("No hay más stock de este producto.");
                return;
            }
            carrito[id].cantidad++;
        } else {
            carrito[id] = { id, nombre, precio, cantidad: 1, stock };
        }
        renderizarCarrito();
    }

    // Modificar cantidad
    function cambiarCantidad(id, delta) {
        if (carrito[id]) {
            carrito[id].cantidad += delta;
            if (carrito[id].cantidad <= 0) {
                delete carrito[id];
            } else if (carrito[id].cantidad > carrito[id].stock) {
                carrito[id].cantidad = carrito[id].stock;
                alert("Límite de stock alcanzado.");
            }
            renderizarCarrito();
        }
    }

    // Vaciar carrito
    function vaciarCarrito() {
        if(confirm('¿Seguro que deseas vaciar el carrito?')) {
            carrito = {};
            renderizarCarrito();
        }
    }

    // Dibujar carrito
    function renderizarCarrito() {
        let lista = document.getElementById('carritoLista');
        let btnConfirmar = document.getElementById('btnConfirmar');
        let btnVaciar = document.getElementById('btnVaciar');
        let total = 0;
        let itemsHtml = '';

        if (Object.keys(carrito).length === 0) {
            lista.innerHTML = '<p class="text-center text-muted mt-4" id="carritoVacio">El carrito está vacío.</p>';
            document.getElementById('totalCarrito').innerText = '<?= MONEDA ?> 0.00';
            btnConfirmar.disabled = true;
            btnVaciar.disabled = true;
            return;
        }

        for (let id in carrito) {
            let item = carrito[id];
            let subtotal = item.precio * item.cantidad;
            total += subtotal;

            itemsHtml += `
                <div class="carrito-item d-flex justify-content-between align-items-center">
                    <div style="flex: 1;">
                        <div class="fw-bold" style="font-size: 0.9rem;">${item.nombre}</div>
                        <div class="text-muted small"><?= MONEDA ?> ${item.precio.toFixed(2)}</div>
                    </div>
                    <div class="d-flex align-items-center mx-2">
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="cambiarCantidad(${id}, -1)">-</button>
                        <span class="mx-2 fw-bold">${item.cantidad}</span>
                        <button class="btn btn-sm btn-outline-success py-0 px-2" onclick="cambiarCantidad(${id}, 1)">+</button>
                    </div>
                    <div class="fw-bold" style="width: 70px; text-align: right;"><?= MONEDA ?> ${subtotal.toFixed(2)}</div>
                </div>
            `;
        }

        lista.innerHTML = itemsHtml;
        document.getElementById('totalCarrito').innerText = '<?= MONEDA ?> ' + total.toFixed(2);
        btnConfirmar.disabled = false;
        btnVaciar.disabled = false;
    }

    // Enviar al servidor
    function confirmarVenta() {
        let cliente = document.getElementById('selectCliente').value;
        if (cliente === '') {
            alert('Por favor, selecciona un cliente.');
            return;
        }

        let carritoArray = Object.values(carrito);
        if (carritoArray.length === 0) return;

        document.getElementById('inputCliente').value = cliente;
        document.getElementById('inputCarrito').value = JSON.stringify(carritoArray);
        
        // Enviamos el formulario (se abre en pestaña nueva por el target="_blank")
        document.getElementById('formConfirmar').submit();
        
        // Limpiamos el carrito visualmente
        carrito = {};
        renderizarCarrito();

        // Recargamos la pestaña después de 1.5 segundos para actualizar stock
        setTimeout(function() { window.location.reload(); }, 1500); 
    }

    // Guardar cliente vía AJAX
    document.getElementById('btnGuardarCliente').addEventListener('click', function() {
        const form = document.getElementById('formNuevoCliente');
        const formData = new FormData(form);
        const alertaDiv = document.getElementById('alertaCliente');
        
        fetch('<?= BASE_URL ?>index.php?controller=cliente&action=registrar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.exito) {
                alertaDiv.innerHTML = `<div class="alert alert-${data.tipo}">${data.mensaje}</div>`;
                
                // Agregar nuevo cliente al selector
                const select = document.getElementById('selectCliente');
                const newOption = new Option(`${data.nombre} (${data.dni})`, data.dni, true, true);
                select.add(newOption);
                
                clienteSeleccionado = data.dni;

                // Cerrar el modal y limpiar
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('modalNuevoCliente')).hide();
                    form.reset();
                    alertaDiv.innerHTML = '';
                }, 1500);
            } else {
                alertaDiv.innerHTML = `<div class="alert alert-${data.tipo}">${data.mensaje}</div>`;
            }
        })
        .catch(error => {
            alertaDiv.innerHTML = `<div class="alert alert-danger">Error de conexión.</div>`;
        });
    });
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>