<?php
class Boleta {
    private $bd;

    //conecta con la base de datos al instanciar
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //consulta base reutilizable con los joins de cliente y empleado
    private function consulta_base(){
        return "
            SELECT boletas.*,
                    clientes.nombre_apellido as cliente,
                    empleados.nombre_apellido as empleado
            FROM boletas
            LEFT JOIN clientes  ON boletas.dni_cliente  = clientes.dni_cliente
            LEFT JOIN empleados ON boletas.dni_empleado = empleados.dni_empleado
        ";
    }

    //inserta una boleta y devuelve el id generado usando sentencia preparada
        //inserta una boleta y devuelve el id generado
       //inserta una boleta y devuelve el id generado
    public function insertar($dni_cliente, $dni_empleado, $total, $fecha){
        //generamos un token único e imposible de adivinar (32 caracteres)
        $token = bin2hex(random_bytes(16));

        //inserta la boleta (ahora con el token)
        $sql = "INSERT INTO boletas (numero_boleta, token, dni_cliente, dni_empleado, total, fecha_emision)
                VALUES ('', ?, ?, ?, ?, ?)";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("sssds", $token, $dni_cliente, $dni_empleado, $total, $fecha);
        $stmt->execute();

        //obtiene el id recién generado
        $id_boleta = $this->bd->insert_id;

        //genera el número visible con formato BOL-000001
        $numero = 'BOL-' . str_pad($id_boleta, 6, '0', STR_PAD_LEFT);

        //actualiza la boleta con su número visible
        $stmt = $this->bd->prepare("UPDATE boletas SET numero_boleta = ? WHERE id_boleta = ?");
        $stmt->bind_param("si", $numero, $id_boleta);
        $stmt->execute();

        return $id_boleta;
    }

    //devuelve una boleta por su token seguro
    public function obtener_por_token($token){
        $sql = $this->consulta_base() . " WHERE boletas.token = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //devuelve todas las boletas ordenadas por id descendente
    public function obtener_todas(){
        return $this->bd->query($this->consulta_base() . " ORDER BY boletas.id_boleta DESC");
    }

    //devuelve las boletas de un mes específico usando sentencia preparada
    public function obtener_por_mes($mes){
        if ($mes === '') {
            return $this->obtener_todas();
        }
        $sql = $this->consulta_base() . " WHERE DATE_FORMAT(boletas.fecha_emision, '%Y-%m') = ? ORDER BY boletas.id_boleta DESC";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $mes);
        $stmt->execute();
        return $stmt->get_result();
    }

    //devuelve una boleta por su número usando sentencia preparada
    public function obtener_por_numero($numero_boleta){
        $sql = $this->consulta_base() . " WHERE boletas.numero_boleta = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $numero_boleta);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //devuelve una boleta por id usando sentencia preparada
    public function obtener_por_id($id_boleta){
        $sql = $this->consulta_base() . " WHERE boletas.id_boleta = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $id_boleta);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
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

    //elimina una boleta y su detalle usando sentencias preparadas
    public function eliminar($id_boleta){
        $stmt = $this->bd->prepare("DELETE FROM ventas WHERE id_boleta = ?");
        $stmt->bind_param("i", $id_boleta);
        $stmt->execute();

        $stmt = $this->bd->prepare("DELETE FROM boletas WHERE id_boleta = ?");
        $stmt->bind_param("i", $id_boleta);
        $stmt->execute();

        return true;
    }

    // Obtiene el total vendido agrupado por fecha (para la gráfica)
    public function obtener_ingresos_por_dia(){
        $sql = "
            SELECT DATE(fecha_emision) as dia, SUM(total) as total_dia
            FROM boletas
            WHERE fecha_emision >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fecha_emision)
            ORDER BY dia ASC
        ";
        return $this->bd->query($sql);
    }
}