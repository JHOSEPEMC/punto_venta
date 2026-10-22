<?php
class Caja {
    private $bd;

    //conecta con la base de datos al instanciar
    public function __construct(){
        $this->bd = Conexion::conectar();
    }

    //abre una nueva caja con el sencillo inicial
    public function abrir($dni_empleado, $sencillo_inicial){
        $fecha = date('Y-m-d H:i:s');
        $sql = "INSERT INTO cajas (dni_empleado, sencillo_inicial, hora_apertura, estado) 
                VALUES (?, ?, ?, 'abierta')";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("sds", $dni_empleado, $sencillo_inicial, $fecha);
        return $stmt->execute();
    }

    //obtiene la caja activa (abierta) de un empleado
    public function obtener_activa($dni_empleado){
        $sql = "SELECT * FROM cajas WHERE dni_empleado = ? AND estado = 'abierta' ORDER BY id_caja DESC LIMIT 1";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni_empleado);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //cierra una caja activa
    public function cerrar($id_caja){
        $fecha = date('Y-m-d H:i:s');
        $sql = "UPDATE cajas SET hora_cierre = ?, estado = 'cerrada' WHERE id_caja = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("si", $fecha, $id_caja);
        return $stmt->execute();
    }

    //calcula el total de ventas de una caja (sumando las boletas asociadas)
    public function calcular_ventas($id_caja){
        $sql = "SELECT SUM(total) as total FROM boletas WHERE id_caja = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("i", $id_caja);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'] ?? 0; //si no hay ventas, retorna 0
    }

    //trae solo las cajas ABIERTAS (para el control actual)
    public function obtener_todas_abiertas(){
        $sql = "SELECT c.*, e.nombre_apellido as empleado 
                FROM cajas c
                LEFT JOIN empleados e ON c.dni_empleado = e.dni_empleado
                WHERE c.estado = 'abierta'
                ORDER BY c.id_caja DESC";
        return $this->bd->query($sql);
    }

    //trae el HISTORIAL de cajas (cerradas o abiertas, ordenadas por fecha)
    public function obtener_historial(){
        $sql = "SELECT c.*, e.nombre_apellido as empleado 
                FROM cajas c
                LEFT JOIN empleados e ON c.dni_empleado = e.dni_empleado
                ORDER BY c.id_caja DESC";
        return $this->bd->query($sql);
    }

    //busca la última caja cerrada de un empleado
    public function obtener_ultima_cerrada($dni_empleado){
        // Buscamos la caja cerrada más reciente que NO tenga hora de cierre (o sea, la que asignó el admin)
        $sql = "SELECT * FROM cajas WHERE dni_empleado = ? AND estado = 'cerrada' ORDER BY id_caja DESC LIMIT 1";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("s", $dni_empleado);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //reabre una caja existente (actualiza hora y estado)
    public function reabrir($id_caja){
        $fecha = date('Y-m-d H:i:s');
        // Ponemos el estado en abierta y actualizamos la hora de apertura
        $sql = "UPDATE cajas SET hora_apertura = ?, estado = 'abierta' WHERE id_caja = ?";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("si", $fecha, $id_caja);
        return $stmt->execute();
    }

    //asigna un sencillo a un empleado creando una caja cerrada
    public function asignarSencillo($dni_empleado, $sencillo){
        // 1. Borramos cualquier caja cerrada vieja que no se haya usado
        $this->bd->query("DELETE FROM cajas WHERE dni_empleado = '$dni_empleado' AND estado = 'cerrada'");
        
        // 2. Creamos la nueva caja
        $fecha = date('Y-m-d H:i:s');
        $sql = "INSERT INTO cajas (dni_empleado, sencillo_inicial, hora_apertura, estado) 
                VALUES (?, ?, ?, 'cerrada')";
        $stmt = $this->bd->prepare($sql);
        $stmt->bind_param("sds", $dni_empleado, $sencillo, $fecha);
        return $stmt->execute();
    }
}