<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  
}
include_once(__DIR__ . '/../models/basededatos.php');

class AuthController {
    private $bd;

    public function __construct() {
        $this->bd = new basededatos();
    }

    // Metodo que comprueba usuario de login y los limitamos a uno y por eso solo usamos Fetch() 
    public function comprobarUsuario($usu, $pas)
    {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave LIMIT 1";
        $stmt = $this->bd->conn->prepare($sql);
        $stmt->bindParam(':usuario', $usu);
        $stmt->bindParam(':clave', $pas);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($usuario, $clave) {
        $datosdeusuario = $this->comprobarUsuario($usuario, $clave);
        return $datosdeusuario;
    }
}
?>
