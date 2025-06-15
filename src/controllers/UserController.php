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

    public function getUsuarioPorId($id)
    {
        $sql = "SELECT u.*, 
                   c.nombre AS nombre_contratista, 
                   p.nombre AS nombre_proveedor, 
                   p.apellidos AS apellidos_proveedor
            FROM usuarios u
            LEFT JOIN contratistas c ON u.id_cont = c.id_cont
            LEFT JOIN proveedores p ON u.id_prov = p.id_prov
            WHERE u.id_usu = ?";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function setUsuario($post)
    {
        try {
            $usuario = $post['usuario'] ?? null;
            $clave = $post['clave'] ?? null;
            $nombre = $post['nombre'] ?? null;
            $tipo = $post['tipo'] ?? null;
            $id_cont = !empty($post['id_cont']) ? $post['id_cont'] : null;
            $id_prov = !empty($post['id_prov']) ? $post['id_prov'] : null;

            if (!$usuario || !$clave || !$nombre || !$tipo) {
                echo json_encode(["error" => "Todos los campos son obligatorios."]);
                return;
            }

            // Validamos que no exista el nombre de usuario
            $stmt = $this->db->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(["error" => "El nombre de usuario ya existe."]);
                return;
            }

            // Para encriptar  la contraseña -pero no lo voy a usar-
            //$claveHash = password_hash($clave, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (usuario, clave, nombre, tipo, id_cont, id_prov) 
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([
                $usuario,
                $clave,
                $nombre,
                $tipo,
                $id_cont,
                $id_prov
            ]);

            echo json_encode(["mensaje" => "Usuario creado exitosamente."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
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

            if (!$id || !$usuario || !$nombre || !$clave || !$tipo) {
                echo json_encode(["error" => "Datos incompletos"]);
                exit;
            }

            // Verificamos si el nombre de usuario está duplicado
            $stmt = $this->db->conn->prepare("SELECT id_usu FROM usuarios WHERE usuario = ? AND id_usu != ?");
            $stmt->execute([$usuario, $id]);
            if ($stmt->fetch()) {
                echo json_encode(["error" => "El nombre de usuario ya está en uso por otro usuario."]);
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

    public function existeUsuario($usuario)
    {
        $stmt = $this->db->conn->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $existe = $stmt->fetchColumn() > 0;
        echo json_encode(['existe' => $existe]);
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
        case 'existeUsuario':
            if (isset($_GET['usuario'])) {
                $controller->existeUsuario($_GET['usuario']);
            }
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(["error" => "Acción no válida"]);
            exit;
    }
}
