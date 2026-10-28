<?php
class InventarioControlador {
    public function mostrar(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        // 1. Instanciamos los DAO necesarios
        $pdtoDAO = new ProductoDAO();
        $catDAO  = new CategoriaDAO();

        // 2. Obtenemos los productos (con su categoría)
        $productos = $pdtoDAO->obtener_todos_con_categoria();
        
        // 3. Obtenemos la lista de categorías para los botones de filtro
        $categorias = $catDAO->obtener_todas();

        // 4. Datos para la gráfica (usando el método del DAO que cuenta productos)
        $datosGrafica = $pdtoDAO->contar_productos_por_categoria();
        $labelsCat = [];
        $valoresCat = [];
        
        foreach ($datosGrafica as $row) {
            $labelsCat[] = $row['nombre_catg'];
            $valoresCat[] = $row['cantidad'];
        }

        // 5. Cargamos la vista
        require_once APP_PATH . '/views/inventario.php';
    }
}