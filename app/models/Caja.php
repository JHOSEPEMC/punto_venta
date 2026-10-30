<?php
class Caja {
    public $id, $nombre, $dni_empleado, $sencillo_inicial, $total_ventas, $hora_apertura, $hora_cierre, $estado;

    public function __construct($datos = []) {
        $this->id = $datos['id_caja'] ?? null;
        $this->nombre = $datos['nombre_caja'] ?? '';
        $this->dni_empleado = $datos['dni_empleado'] ?? '';
        $this->sencillo_inicial = $datos['sencillo_inicial'] ?? 0;
        $this->total_ventas = $datos['total_ventas'] ?? 0;
        $this->hora_apertura = $datos['hora_apertura'] ?? null;
        $this->hora_cierre = $datos['hora_cierre'] ?? null;
        $this->estado = $datos['estado'] ?? '';
    }
}