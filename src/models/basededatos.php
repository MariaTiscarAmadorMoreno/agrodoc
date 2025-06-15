<?php
class basededatos
{
    public $conn;

    public function __construct()
    {
        // Verificamos si el archivo config.ini existe
        $configFile = __DIR__ . '/../../config/config.ini';
        if (!file_exists($configFile)) {
            die("Error: El archivo config.ini no se encuentra en la ruta: $configFile");
        }

        //Cargamos la configuración desde config.ini
        $config = parse_ini_file($configFile);
        if (!$config) {
            die("Error: No se pudo leer el archivo config.ini");
        }

        //Intentamos la conexión a MySQL con PDO
        try {
            $dsn = "mysql:host={$config['server']};dbname={$config['base']};charset=utf8mb4";
            $this->conn = new PDO($dsn, $config['usu'], $config['pas'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]);
        } catch (PDOException $ex) {
            die("Error de conexión a MySQL: " . $ex->getMessage());
        }
    }

    public function ejecutarConsulta($sql)
    {
        try {
            return $this->conn->query($sql);
        } catch (PDOException $ex) {
            die("Error ejecutando consulta: " . $ex->getMessage());
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }

    public function getConnection()
    {
        return $this->conn;
    }


}
