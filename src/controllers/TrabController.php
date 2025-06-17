<?php
include_once(__DIR__ . '/../models/basededatos.php');

class TrabController
{
    private $db;

    public function __construct()
    {
        $this->db = new basededatos();
    }
    public function getTrabajadores()
    {
        $sql = "SELECT t.*, 
                   p.nombre AS nombre_proveedor,
                   p.apellidos AS apellidos_proveedor
            FROM trabajadores t
            LEFT JOIN proveedores p ON t.id_prov = p.id_prov
            ORDER BY t.id_trab ASC";

        $resultado = $this->db->ejecutarConsulta($sql);

        if ($resultado) {
            $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

            // Si lo llamamos mediante AJAX, devolvemos JSON
            if (!empty($_GET['action']) && $_GET['action'] === 'listarTrabajadores') {
                echo json_encode($datos);
                exit;
            }

            // Si lo llamamos desde PHP directamente, devolvemos el array
            return $datos;
        } else {
            if (!empty($_GET['action']) && $_GET['action'] === 'listarTrabajadores') {
                echo json_encode([]);
                exit;
            }
            return [];
        }
    }
    public function getTrabajadorPorId($id)
    {
        $sql = "SELECT t.*, p.nombre AS nombre_proveedor
            FROM trabajadores t
            LEFT JOIN proveedores p ON t.id_prov = p.id_prov
            WHERE t.id_trab = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delTrabajador($id)
    {

        try {
            $sql = "DELETE FROM trabajadores WHERE id_trab = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Trabajador eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el trabajador para eliminar."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function updateTrabajador($datosSerializados)
    {
        try {
            header('Content-Type: application/json');

            $datos = json_decode($datosSerializados, true);

            $sql = "UPDATE trabajadores SET nombre = ?, apellidos = ?, dni = ?, email = ?, telefono = ?, direccion = ?, documentos = ? 
                WHERE id_trab = ?";

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([
                $datos['nombre'],
                $datos['apellidos'],
                $datos['dni'],
                $datos['email'],
                $datos['telefono'],
                $datos['direccion'],
                $datos['documentos'],
                $datos['id']
            ]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Trabajador actualizado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el trabajador o los datos son los mismos."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    public function setTrabajador($datos)
    {
        $dni = $datos['dni'];

        // Verificamos que el DNI del trabajador no esté ya registrado
        $sql = "SELECT COUNT(*) FROM trabajadores WHERE dni = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$dni]);
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            echo json_encode(["error" => "Ya existe un trabajador con ese DNI."]);
            exit;
        }

        $this->db->conn->beginTransaction();

        $sql = "INSERT INTO trabajadores 
            (id_trab, nombre, apellidos, dni, email, telefono, direccion, documentos, id_prov) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([
            $datos['nombre'],
            $datos['apellidos'],
            $datos['dni'],
            $datos['email'],
            $datos['telefono'],
            $datos['direccion'],
            $datos['documentos'],
            $datos['id_prov']
        ]);

        $this->db->conn->commit();

        echo json_encode(["mensaje" => "Trabajador creado exitosamente."]);
    }


    public function getTrabajadoresPorProveedor($id_prov)
    {
        try {
            $sql = "SELECT t.*, 
                   p.nombre AS nombre_proveedor,
                   p.apellidos AS apellidos_proveedor
            FROM trabajadores t
            LEFT JOIN proveedores p ON t.id_prov = p.id_prov
            WHERE p.id_prov = ?"; 

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$id_prov]);
            $trabajadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Si se llama por AJAX, devuelve JSON
            if (!empty($_GET['action']) && $_GET['action'] === 'listarTrabajadoresPorProveedor') {
                header('Content-Type: application/json');
                echo json_encode($trabajadores);
                exit;
            }

            //Si se llama desde PHP, simplemente retorna
            return $trabajadores;
        } catch (PDOException $e) {
            if (!empty($_GET['action'])) {
                header('Content-Type: application/json');
                echo json_encode(["error" => $e->getMessage()]);
                exit;
            } else {
                return [];
            }
        }
    }
    public function getTrabajadoresPorProveedorAptos($id_prov)
    {
        try {
            $sql = "SELECT t.*, 
                   p.nombre AS nombre_proveedor,
                   p.apellidos AS apellidos_proveedor
            FROM trabajadores t
            LEFT JOIN proveedores p ON t.id_prov = p.id_prov
            WHERE p.id_prov = ?
            AND t.documentos = 1"; 

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$id_prov]);
            $trabajadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Si se llama por AJAX, devuelve JSON
            if (!empty($_GET['action']) && $_GET['action'] === 'listarTrabajadoresPorProveedorAptos') {
                header('Content-Type: application/json');
                echo json_encode($trabajadores);
                exit;
            }

            //Si se llama desde PHP, simplemente retorna
            return $trabajadores;
        } catch (PDOException $e) {
            if (!empty($_GET['action'])) {
                header('Content-Type: application/json');
                echo json_encode(["error" => $e->getMessage()]);
                exit;
            } else {
                return [];
            }
        }
    }

    //Funcion para actualizar campo estado de documentacion si tiene todos los documentos.
    public function actualizarEstadoDocumentacion($id_trab, $estado)
    {
        $sql = "UPDATE trabajadores SET documentos = ? WHERE id_trab = ?";
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute([$estado ? 1 : 0, $id_trab]);
    }



}





if (isset($_GET['action'])) {
    $controller = new TrabController();

    switch ($_GET['action']) {
        case 'listarTrabajadores':
            $controller->getTrabajadores();
            break;

        case 'eliminarTrabajador':
            if (isset($_GET['id'])) {
                $controller->delTrabajador($_GET['id']);
            }
            break;

        case 'modificarTrabajador':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'modificarTrabajador') {
                header('Content-Type: application/json');

                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                if (isset($data['datos'])) {
                    $controller = new TrabController();
                    $controller->updateTrabajador(json_encode($data['datos']));
                } else {
                    echo json_encode(["error" => "No se recibieron datos válidos"]);
                }
            }
            break;

        case 'crearTrabajador':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');
                // Leer cuerpo JSON enviado
                $input = file_get_contents("php://input");
                $datos = json_decode($input, true);

                if (isset($datos['nombre'], $datos['apellidos'], $datos['dni'], $datos['email'], $datos['telefono'], $datos['direccion'], $datos['documentos'], $datos['id_prov'])) {
                    $controller->setTrabajador($datos);
                } else {
                    echo json_encode(["error" => "Datos incompletos o mal formateados"]);
                }
            }
            break;
        case 'listarTrabajadoresPorProveedor':
            if (isset($_GET['id_prov'])) {
                $controller->getTrabajadoresPorProveedor($_GET['id_prov']);
            }
            break;
                case 'listarTrabajadoresPorProveedorAptos':
            if (isset($_GET['id_prov'])) {
                $controller->getTrabajadoresPorProveedor($_GET['id_prov']);
            }
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(["error" => "Acción no válida"]);
            exit;
    }
}
