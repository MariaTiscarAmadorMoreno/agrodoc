<?php
include_once(__DIR__ . '/../models/basededatos.php');

class ContController
{
    private $db;

    public function __construct()
    {
        $this->db = new basededatos();
    }

    public function getContratistas()
    {
        $sql = "SELECT * FROM contratistas ORDER BY id_cont ASC";
        $resultado = $this->db->ejecutarConsulta($sql);

        if ($resultado) {
            $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

            // Si se llama mediante AJAX, devolvemos JSON
            if (!empty($_GET['action']) && $_GET['action'] === 'listarContratistas') {
                echo json_encode($datos);
                exit;
            }

            // Si se llama desde PHP directamente, devolvemos el array
            return $datos;
        } else {
            if (!empty($_GET['action']) && $_GET['action'] === 'listarContratistas') {
                echo json_encode([]);
                exit;
            }
            return [];
        }
    }

    public function getContratistaPorId($id)
    {
        $sql = "SELECT * FROM contratistas WHERE id_cont = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delContratista($id)
    {

        try {
            $sql = "DELETE FROM contratistas WHERE id_cont= ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Contratista eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el contratista para eliminar."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function updateContratista($datosSerializados)
    {
        try {
            $datos = json_decode($datosSerializados, true);
           
            $id = $datos['id'] ?? null;
            $nombre = $datos['nombre'] ?? null;
            $cif = $datos['cif'] ?? null;
            $email = $datos['email'] ?? null;
            $telefono = $datos['telefono'] ?? null;
            $direccion = $datos['direccion'] ?? null;

            if (!$id || !$nombre || !$cif || !$email || !$telefono || !$direccion) {
                echo json_encode(["error" => "Todos los campos son obligatorios."]);
                return;
            }
            $sql = "UPDATE contratistas 
                SET nombre = ?,  cif = ?, email = ?, telefono = ?, direccion = ?
                WHERE id_cont = ?";

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$nombre, $cif, $email, $telefono, $direccion, $id]);


            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Contratista actualizado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el contratista o los datos son los mismos."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    //Método para crear un contratista y como no devuelve nada no usa ningun fetch
    public function setContratista($datos)
{
    try {
        $nombre = $datos['nombre'] ?? null;
        $cif = $datos['cif'] ?? null;
        $email = $datos['email'] ?? null;
        $telefono = $datos['telefono'] ?? null;
        $direccion = $datos['direccion'] ?? null;


        
        // Verificar duplicado por CIF
        $stmt = $this->db->conn->prepare("SELECT COUNT(*) FROM contratistas WHERE cif = ?");
        $stmt->execute([$cif]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(["error" => "Ya existe un contratista con ese CIF."]);
            return;
        }

        $sql = "INSERT INTO contratistas (nombre, cif, email, telefono, direccion) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$nombre, $cif, $email, $telefono, $direccion]);

        echo json_encode(["mensaje" => "Contratista creado exitosamente."]);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}


    public function getContratistasPorProveedor($idProveedor)
    {
        $sql = "
        SELECT c.id_cont, c.nombre, p.fecha_inicio, c.cif, c.email, c.direccion, c.telefono
        FROM proyectos p
        INNER JOIN contratistas c ON p.id_cont = c.id_cont
        WHERE p.id_prov = ?
    ";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idProveedor]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si se llama por AJAX
        if (!empty($_GET['action']) && $_GET['action'] === 'listarContratistasPorProveedor') {
            header('Content-Type: application/json');
            echo json_encode($datos);
            exit;
        }

        return $datos;
    }

    public function getTrabajadoresPorProveedor($idProveedor)
    {
        $sql = "
                SELECT DISTINCT t.*
                FROM trabajadores t
                INNER JOIN proveedores p ON t.id_prov = p.id_prov
                WHERE p.id_prov = ?
            ";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idProveedor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if (isset($_GET['action'])) {
    $controller = new ContController();

    switch ($_GET['action']) {
        case 'listarContratistas':
            $controller->getContratistas();
            break;

        case 'eliminarContratista':
            if (isset($_GET['id'])) {
                $controller->delContratista($_GET['id']);
            }
            break;

        case 'modificarContratista':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'modificarContratista') {
                header('Content-Type: application/json');

                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                if (isset($data['datos'])) {
                    $controller = new ContController();
                    $controller->updateContratista(json_encode($data['datos']));
                } else {
                    echo json_encode(["error" => "No se recibieron datos válidos"]);
                }
            }
            break;

        case 'crearContratista':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
               header('Content-Type: application/json');

                // Leer cuerpo JSON enviado
                $input = file_get_contents("php://input");
                $datos = json_decode($input, true);

                if (isset($datos['nombre'], $datos['cif'], $datos['email'], $datos['telefono'], $datos['direccion'])) {
                    $controller->setContratista($datos);
                } else {
                    echo json_encode(["error" => "Datos incompletos o mal formateados"]);
                }
            }
            break;
        case 'listarContratistasPorProveedor':
            if (isset($_GET['id_prov'])) {
                $controller->getContratistasPorProveedor($_GET['id_prov']);
            }
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(["error" => "Acción no válida"]);
            exit;
    }
}
