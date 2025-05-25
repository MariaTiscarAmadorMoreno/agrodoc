<?php
include_once(__DIR__ . '/../models/basededatos.php');
error_log("UserController cargado correctamente");


class UserController
{
    private $db;

    public function __construct()
    {

        $this->db = new basededatos();
    }

    public function getUsuarios()
    {
        $sql = "SELECT u.*, 
                   c.nombre AS nombre_contratista, 
                   p.nombre AS nombre_proveedor, 
                   p.apellidos AS apellidos_proveedor
            FROM usuarios u
            LEFT JOIN contratistas c ON u.id_cont = c.id_cont
            LEFT JOIN proveedores p ON u.id_prov = p.id_prov
            ORDER BY u.id_usu ASC";

        $resultado = $this->db->ejecutarConsulta($sql);

        if ($resultado) {
            $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

            // Si se llama mediante AJAX, devolvemos JSON
            if (!empty($_GET['action']) && $_GET['action'] === 'listarUsuarios') {
                echo json_encode($datos);
                exit;
            }

            // Si se llamamos desde PHP directamente, devolvemos el array
            return $datos;
        } else {
            if (!empty($_GET['action']) && $_GET['action'] === 'listarUsuarios') {
                echo json_encode([]);
                exit;
            }
            return [];
        }
    }


    public function setUsuario($datosSerializados)
    {
        try {
            $datos = unserialize($datosSerializados);

            $id_cont = !empty($datos[4]) ? $datos[4] : null;
            $id_prov = !empty($datos[5]) ? $datos[5] : null;

            $sql = "INSERT INTO usuarios (usuario, clave, nombre, tipo, id_cont, id_prov) 
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([
                $datos[0],
                $datos[1],
                $datos[2],
                $datos[3],
                $id_cont,
                $id_prov
            ]);

            //echo json_encode(["mensaje" => "Usuario creado exitosamente"]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo json_encode(["error" => "El nombre de usuario ya existe."]);
            } else {
                echo json_encode(["error" => $e->getMessage()]);
            }
        }
    }

    public function delUsuario($id)
    {

        try {
            $sql = "DELETE FROM usuarios WHERE id_usu = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Usuario eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el usuario para eliminar."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function updateUsuario($datosSerializados)
    {
        try {
            header('Content-Type: application/json');

            $datos = json_decode($datosSerializados, true);

            $id = $datos['id'] ?? null;
            $usuario = $datos['usuario'] ?? null;
            $clave = $datos['clave'] ?? null;
            $nombre = $datos['nombre'] ?? null;
            $tipo = $datos['tipo'] ?? 'admin';  

            if (!$id || !$usuario || !$nombre) {
                echo json_encode(["error" => "Datos incompletos"]);
                exit;
            }

            $sql = "UPDATE usuarios SET usuario = ?, clave = ?, nombre = ?, tipo = ? WHERE id_usu = ?";

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$usuario, $clave, $nombre, $tipo, $id]);


            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Usuario actualizado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el usuario o los datos son los mismos."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}

if (isset($_GET['action'])) {
    $controller = new UserController();

    switch ($_GET['action']) {
        case 'listarUsuarios':
            $controller->getUsuarios();
            break;

        case 'eliminarUsuario':
            if (isset($_GET['id'])) {
                $controller->delUsuario($_GET['id']);
            }
            break;

        case 'modificarUsuario':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'modificarUsuario') {
                header('Content-Type: application/json');

                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                if (isset($data['datos'])) {
                    $controller = new UserController();
                    $controller->updateUsuario(json_encode($data['datos']));
                } else {
                    echo json_encode(["error" => "No se recibieron datos válidos"]);
                }
            }
            break;

        case 'crearUsuario':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->setUsuario($_POST);
            }
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(["error" => "Acción no válida"]);
            exit;
    }
}
