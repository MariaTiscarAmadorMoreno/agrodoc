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

    public function getConnection() {
        return $this->conn;
    }

    // Metodo que comprueba usuario de login y los limitamos a uno y por eso solo usamos Fetch() 
    public function comprobarUsuario($usu, $pas)
    {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario', $usu);
        $stmt->bindParam(':clave', $pas);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // //FUNCIONES PARA CONSULTAS TABLA USUARIO

    //Método para crear un usuario y como no devuelve nada no usa ningun fetch
    // public function setUsuario($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();

    //     $sql = "INSERT INTO usuarios (usuario, clave, nombre, tipo, id_cont, id_prov) VALUES (?, ?, ?, ?, ?, ?)";

    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute([
    //         $datos[0],
    //         $datos[1],
    //         $datos[2],
    //         $datos[3],
    //         $datos[4] ?? null, // id_cont (solo si es contratista, de lo contrario NULL)
    //         $datos[5] ?? null  // id_prov (solo si es proveedor, de lo contrario NULL)
    //     ]);

    //     $this->conn->commit();
    // }
    //Método para obtener los usuarios. Como son varios usurios usamos FetchAll()
    // public function getUsuarios()
    // {
    //     $sql = 'SELECT * FROM usuarios order by id_usu';
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }
    // //Método para obtener un usuario determinado solo Fetch()
    // public function getUsuario($id)
    // {
    //     $sql = "SELECT * FROM usuarios where id_usu=$id";
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->Fetch();
    //         return $datos;
    //     }
    // }

    //metodo para actualizar/modificar la tabla usuarios
    // public function updateUsuario($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE usuarios SET usuario= ?, clave= ?, nombre= ?,tipo = ? where id_usu = ?;";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($datos[1], $datos[2], $datos[3], $datos[4], $datos[0]));
    //     $this->conn->commit();
    // }

    // public function updateUsuario($id, $usuario, $clave, $nombre, $tipo)
    // {
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE usuarios SET usuario= ?, clave= ?, nombre= ?, tipo= ? WHERE id_usu = ?;";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($usuario, $clave, $nombre, $tipo, $id));
    //     $this->conn->commit();
    // }



    //metodo para borrar usuario, y como no devuelve nada no necesitamos ningun fetch
    //lo creo por si hace falta
    // public function delUsuario($id)
    // {
    //     $sql = "DELETE FROM usuarios WHERE id_usu=$id";
    //     $this->ejecutarConsulta($sql);
    // }

    //FUNCIONES PARA CONSULTAS TABLA CONTRATISTAS

    // public function getContratistas()
    // {
    //     $sql = 'SELECT * FROM contratistas order by id_cont';
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }
    //Método para obtener un contratista determinado solo Fetch()
    // public function getContratista($id)
    // {
    //     $sql = "SELECT * FROM contratistas where id_cont=$id";
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->Fetch();
    //         return $datos;
    //     }
    // }
    //Método para crear un contratista y como no devuelve nada no usa ningun fetch
    // public function setContratista($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "INSERT INTO contratistas VALUES (?,?,?,?,?,?);";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array(0, $datos[0], $datos[1], $datos[2], $datos[3], $datos[4]));
    //     $this->conn->commit();
    // }
    //metodo para borrar contratista, y como no devuelve nada no necesitamos ningun fetch
    //lo creo por si hace falta
    // public function delContratista($id)
    // {
    //     $sql = "DELETE FROM contratistas WHERE id_cont=$id";
    //     $this->ejecutarConsulta($sql);
    // }

    //metodo para actualizar/modificar la tabla usuarios
    // public function updateContratista($id, $nombre, $cif, $email, $telefono, $direccion)
    // {
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE contratistas SET nombre = ?, cif = ?, email = ?, telefono = ?, direccion = ? WHERE id_cont = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($nombre, $cif, $email, $telefono, $direccion, $id));
    //     $this->conn->commit();
    // }

    //FUNCIONES PARA CONSULTAS TABLA PROVEEDORES

    // public function getProveedores()
    // {
    //     $sql = 'SELECT * FROM proveedores order by id_prov';
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }

    // Método para obtener un proveedor determinado solo Fetch()
    // public function getProveedor($id)
    // {
    //     $sql = "SELECT * FROM proveedores where id_prov=$id";
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->Fetch();
    //         return $datos;
    //     }
    // }

    //Método para crear un contratista y como no devuelve nada no usa ningun fetch
    // public function setProveedor($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "INSERT INTO proveedores VALUES (?,?,?,?,?,?,?);";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array(0, $datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5]));
    //     $this->conn->commit();
    // }
    //metodo para actualizar/modificar la tabla proveedores
    // public function updateProveedor($id, $nombre, $apellidos, $cif, $email, $telefono, $direccion)
    // {
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE proveedores SET nombre = ?, apellidos = ?, cif = ?, email = ?, telefono = ?, direccion = ? WHERE id_prov = ?;";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($nombre, $apellidos, $cif, $email, $telefono, $direccion, $id));
    //     $this->conn->commit();
    // }
    //metodo para borrar proveedor, y como no devuelve nada no necesitamos ningun fetch
    //lo creo por si hace falta
    // public function delProveedor($id)
    // {
    //     $sql = "DELETE FROM proveedores WHERE id_prov=$id";
    //     $this->ejecutarConsulta($sql);
    // }


    //FUNCIONES PARA CONSULTAS TABLA FINCAS

    //Método para crear una finca y como no devuelve nada no usa ningun fetch
    // public function setFinca($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "INSERT INTO fincas VALUES (?,?,?,?,?);";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array(0, $datos[0], $datos[1], $datos[2], $datos[3]));
    //     $this->conn->commit();
    // }

    //Método para obtener las fincas. Como son varias fincas usamos FetchAll()
    // public function getAllFincas()
    // {
    //     $sql = 'SELECT * FROM fincas order by id_finca';
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }
    // public function getFincas()
    // {
    //     $sql = 'SELECT fincas.id_finca, fincas.localizacion, fincas.cultivo, fincas.hectarea, contratistas.nombre
    //     FROM fincas 
    //     JOIN contratistas ON fincas.id_cont = contratistas.id_cont
    //     order by id_finca';
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }
    //Método para obtener un finca determinado solo Fetch()
    // public function getFinca($id)
    // {
    //     $sql = "SELECT * FROM fincas where id_finca=$id";
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->Fetch();
    //         return $datos;
    //     }
    // }

    //metodo para actualizar/modificar la tabla fincas
    // public function updateFinca($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE fincas SET = localizacion = ?, cultivo = ?, hectarea = ?, id_cont = ? where id_finca = ?;";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($datos[1], $datos[2], $datos[3], $datos[4], $datos[0]));
    //     $this->conn->commit();
    // }

    //metodo para actualizar/modificar la tabla usuarios
    // public function updateFinca($id, $localizacion, $cultivo, $hectarea, $id_cont)
    // {
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE fincas SET localizacion = ?, cultivo = ?, hectarea = ?, id_cont = ? WHERE id_finca = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($localizacion, $cultivo, $hectarea, $id_cont, $id));
    //     $this->conn->commit();
    // }


    //metodo para borrar finca, y como no devuelve nada no necesitamos ningun fetch
    //lo creo por si hace falta
    // public function delFinca($id)
    // {
    //     $sql = "DELETE FROM fincas WHERE id_finca=$id";
    //     $this->ejecutarConsulta($sql);
    // }

    //FUNCIONES PARA CONSULTAS TABLA PROYECTOS

    //Método para crear un proyecto y como no devuelve nada no usa ningun fetch
    // public function setProyecto($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "INSERT INTO proyectos VALUES (?,?,?,?,?,?);";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array(0, $datos[0], $datos[1], $datos[2], $datos[3], $datos[4]));
    //     $this->conn->commit();
    // }

    //Método para obtener los proyectos. Como son varios  usamos FetchAll()
    // public function getProyectos()
    // {
    //     $sql = 'SELECT proyectos.id_proyec, proyectos.fecha_inicio, proyectos.fecha_fin, contratistas.nombre AS contratista, 
    //     proveedores.nombre AS proveedor, fincas.localizacion AS finca
    //     FROM proyectos 
    //     JOIN contratistas ON proyectos.id_cont = contratistas.id_cont
    //     JOIN proveedores ON proyectos.id_prov = proveedores.id_prov
    //     JOIN fincas ON proyectos.id_finca = fincas.id_finca
    //     ORDER BY id_proyec';

    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }

    //Método para obtener un proyecto  determinado solo Fetch()
    // public function getProyecto($id)
    // {
    //     $sql = "SELECT * FROM proyectos where id_proyec=$id";
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->Fetch();
    //         return $datos;
    //     }
    // }
    //metodo para borrar proyectos, y como no devuelve nada no necesitamos ningun fetch
    // public function delProyecto($id)
    // {
    //     $sql = "DELETE FROM proyectos WHERE id_proyec=$id";
    //     $this->ejecutarConsulta($sql);
    // }
    //metodo para actualizar/modificar tabla de proyectos
    // public function updateProyecto($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "UPDATE proyectos SET fecha_inicio = ?, fecha_fin = ?, id_cont = ?, id_prov = ?, id_finca = ? where id_proyec = ?;";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array($datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[0]));
    //     $this->conn->commit();
    // }

    //FUNCIONES PARA CONSULTAS TABLA TRABAJADORES

    //Método para crear un trabajador y como no devuelve nada no usa ningun fetch
    // public function setTrabajador($datos)
    // {
    //     $datos = unserialize($datos);
    //     $this->conn->beginTransaction();
    //     $sql = "INSERT INTO trabajadores VALUES (?,?,?,?,?,?,?,?,?);";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->execute(array(0, $datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7]));
    //     $this->conn->commit();
    // }
    //Método para obtener trabajadores. Como son varios  usamos FetchAll()
    // public function getTrabajadores()
    // {
    //     $sql = 'SELECT trabajadores.id_trab, trabajadores.nombre, trabajadores.apellidos, trabajadores.dni, trabajadores.email, 
    //      trabajadores.telefono, trabajadores.direccion, trabajadores.documentos, proveedores.nombre
    //      FROM trabajadores 
    //      JOIN proveedores ON trabajadores.id_prov = proveedores.id_prov
    //      order by trabajadores.id_trab';
    //     $resultado = $this->ejecutarConsulta($sql);
    //     if ($resultado) {
    //         $datos = $resultado->FetchAll();
    //         return $datos;
    //     }
    // }

    //Método para obtener los trabajadores de cada proveedor
    // public function getProveedoresConTrabajadores()
    // {
    //     $sql = "SELECT p.id_prov, p.nombre AS nombre_prov, 
    //                    t.id_trab, t.nombre, t.apellidos, t.dni, 
    //                    t.email, t.telefono, t.direccion, t.documentos 
    //             FROM proveedores p 
    //             LEFT JOIN trabajadores t ON p.id_prov = t.id_prov
    //             ORDER BY p.id_prov, t.id_trab";

    //     $stmt = $this->conn->query($sql);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    //metodo para borrar trbajador, y como no devuelve nada no necesitamos ningun fetch
    // public function delTrabajador($id)
    // {
    //     $sql = "DELETE FROM trabajadores WHERE id_trab=$id";
    //     $this->ejecutarConsulta($sql);
    // }
 }
