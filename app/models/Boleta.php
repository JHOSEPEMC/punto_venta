<?php
class Boleta {
    private $bd;

    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //inserta una boleta y devuelve el id generado
    public function insertar($dni_cliente, $dni_empleado, $total, $fecha){
        //insertamos la boleta (numero_boleta se genera después con el id)
        $sql = "INSERT INTO boletas (numero_boleta, dni_cliente, dni_empleado, total, fecha_emision)
                VALUES ('', '$dni_cliente', '$dni_empleado', '$total', '$fecha')";
        $this->bd->query($sql);

        //obtenemos el id_boleta recién generado
        $id_boleta = $this->bd->insert_id;

        //generamos el número visible con formato BOL-000001
        $numero = 'BOL-' . str_pad($id_boleta, 6, '0', STR_PAD_LEFT);

        //actualizamos la boleta con su número visible
        $this->bd->query("UPDATE boletas SET numero_boleta = '$numero' WHERE id_boleta = $id_boleta");

        return $id_boleta;
    }

    //devuelve todas las boletas con datos cruzados
    public function obtener_todas(){
        $sql = "
            SELECT boletas.id_boleta,
                    boletas.numero_boleta,
                    boletas.dni_cliente,
                    boletas.dni_empleado,
                    boletas.total,
                    boletas.fecha_emision,
                    clientes.nombre_apellido as cliente,
                    empleados.nombre_apellido as empleado
            FROM boletas
            LEFT JOIN clientes  ON boletas.dni_cliente  = clientes.dni_cliente
            LEFT JOIN empleados ON boletas.dni_empleado = empleados.dni_empleado
            ORDER BY boletas.id_boleta DESC
        ";
        return $this->bd->query($sql);
    }

    //devuelve boletas filtradas por mes (YYYY-MM)
    public function obtener_por_mes($mes){
        if ($mes === '') {
            return $this->obtener_todas();
        }
        $sql = "
            SELECT boletas.id_boleta,
                    boletas.numero_boleta,
                    boletas.dni_cliente,
                    boletas.dni_empleado,
                    boletas.total,
                    boletas.fecha_emision,
                    clientes.nombre_apellido as cliente,
                    empleados.nombre_apellido as empleado
            FROM boletas
            LEFT JOIN clientes  ON boletas.dni_cliente  = clientes.dni_cliente
            LEFT JOIN empleados ON boletas.dni_empleado = empleados.dni_empleado
            WHERE DATE_FORMAT(boletas.fecha_emision, '%Y-%m') = '$mes'
            ORDER BY boletas.id_boleta DESC
        ";
        return $this->bd->query($sql);
    }

    //devuelve una boleta por su número
    public function obtener_por_numero($numero_boleta){
        $sql = "
            SELECT boletas.*,
                    clientes.nombre_apellido as cliente,
                    empleados.nombre_apellido as empleado
            FROM boletas
            LEFT JOIN clientes  ON boletas.dni_cliente  = clientes.dni_cliente
            LEFT JOIN empleados ON boletas.dni_empleado = empleados.dni_empleado
            WHERE boletas.numero_boleta = '$numero_boleta'
        ";
        return $this->bd->query($sql)->fetch_assoc();
    }

    //devuelve una boleta por su id
    public function obtener_por_id($id_boleta){
        $sql = "
            SELECT boletas.*,
                    clientes.nombre_apellido as cliente,
                    empleados.nombre_apellido as empleado
            FROM boletas
            LEFT JOIN clientes  ON boletas.dni_cliente  = clientes.dni_cliente
            LEFT JOIN empleados ON boletas.dni_empleado = empleados.dni_empleado
            WHERE boletas.id_boleta = " . (int)$id_boleta;
        return $this->bd->query($sql)->fetch_assoc();
    }

    //devuelve los meses disponibles para el filtro
    public function obtener_meses_disponibles(){
        $sql = "
            SELECT DISTINCT DATE_FORMAT(fecha_emision, '%Y-%m') as mes
            FROM boletas
            WHERE fecha_emision IS NOT NULL
            ORDER BY mes DESC
        ";
        return $this->bd->query($sql);
    }

    //elimina una boleta y su detalle
    public function eliminar($id_boleta){
        //borra primero el detalle
        $this->bd->query("DELETE FROM ventas WHERE id_boleta = " . (int)$id_boleta);
        //luego la boleta
        $this->bd->query("DELETE FROM boletas WHERE id_boleta = " . (int)$id_boleta);
        return true;
    }
}