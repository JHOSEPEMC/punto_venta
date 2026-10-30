<?php
class AdministradorDAO {
    private $pdo;
    public function __construct() { $this->pdo = Conexion::conectar(); }
    public function obtener_por_dni($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM administradores WHERE dni_administrador = ?");
        $stmt->execute([$dni]);
        $fila = $stmt->fetch();
        return $fila ? new Administrador($fila) : null;
    }
}