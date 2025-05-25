<?php
include_once(__DIR__ . '/../models/basededatos.php');

class ProyecController
{
    private $db;

    public function __construct()
    {
        $this->db = new basededatos();
    }
    public function getProyectos()
    {
        $sql = "SELECT pr.*, 
                   c.nombre AS nombre_contratista, 
                   p.nombre AS nombre_proveedor,  
                   f.localizacion AS localizacion_finca,
                   f.cultivo AS tipo_cultivo
            FROM proyectos pr
            LEFT JOIN contratistas c ON pr.id_cont = c.id_cont
            LEFT JOIN proveedores p ON pr.id_prov = p.id_prov
            LEFT JOIN fincas f ON pr.id_finca = f.id_finca
            ORDER BY pr.id_proyec ASC";

        $resultado = $this->db->ejecutarConsulta($sql);

        if ($resultado) {
            $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

            // Si se llama mediante AJAX, devolvemos JSON
            if (!empty($_GET['action']) && $_GET['action'] === 'listarProyectos') {
                echo json_encode($datos);
                exit;
            }

            // Si se llama desde PHP directamente, devolvemos el array
            return $datos;
        } else {
            if (!empty($_GET['action']) && $_GET['action'] === 'listarProyectos') {
                echo json_encode([]);
                exit;
            }
            return [];
        }
    }

    public function delProyecto($id)
    {

        try {
            $sql = "DELETE FROM proyectos WHERE id_proyec = ?";
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Proyecto eliminado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el proyecto para eliminar."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function updateProyecto($datosSerializados)
    {
        try {
            $datos = json_decode($datosSerializados, true);

           
            if (count($datos) !== 4) {
                echo json_encode(["error" => "Número incorrecto de parámetros"]);
                return;
            }

            $sql = "UPDATE proyectos 
                    SET trabajo = ?, fecha_inicio = ?, fecha_fin = ?
                    WHERE id_proyec = ?";

            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([
                $datos[1],
                $datos[2],
                $datos[3],
                $datos[0]
            ]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["mensaje" => "Proyecto actualizado correctamente."]);
            } else {
                echo json_encode(["error" => "No se encontró el proyecto o los datos son los mismos."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }


    //Método para crear un proyecto y como no devuelve nada no usa ningun fetch
    public function setProyecto($datos)
    {

        $sql = "INSERT INTO proyectos (id_cont, id_prov, id_finca, trabajo,fecha_inicio, fecha_fin) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([
            $datos[0] ?? null,
            $datos[1] ?? null,
            $datos[2] ?? null,
            $datos[3] ?? null,
            $datos[4] ?? null,
            $datos[5] ?? null
        ]);
    }


    public function getProyectosPorProveedorYContratista($idProveedor, $idContratista)
    {
        $sql = "
            SELECT pr.*, f.localizacion, f.cultivo, pr.fecha_inicio, pr.fecha_fin
            FROM proyectos pr
            INNER JOIN fincas f ON pr.id_finca = f.id_finca
            WHERE pr.id_prov = ? AND pr.id_cont = ?
        ";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idProveedor, $idContratista]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProyectosPorContratista($idContratista)
    {
        $sql = "
        SELECT pr.*,
        c.nombre AS nombre_contratista,
        p.nombre AS nombre_proveedor, 
        f.localizacion AS localizacion_finca,
        f.cultivo AS tipo_cultivo,
        pr.fecha_inicio, pr.fecha_fin
        FROM proyectos pr
        INNER JOIN contratistas c ON pr.id_cont = c.id_cont
        INNER JOIN proveedores p ON pr.id_prov = p.id_prov
        INNER JOIN fincas f ON pr.id_finca = f.id_finca
        WHERE  pr.id_cont = ?
    ";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idContratista]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContratistasPorProveedor($idProveedor)
    {
        $sql = "
        SELECT DISTINCT c.*
        FROM contratistas c
        INNER JOIN proyectos pr ON pr.id_cont = c.id_cont
        WHERE pr.id_prov = ?
    ";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idProveedor]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProyectosPorProveedor($idProv)
    {
        $sql = "
        SELECT pr.*,
        c.nombre AS nombre_contratista,
        p.nombre AS nombre_proveedor, 
        f.localizacion AS localizacion_finca,
        f.cultivo AS tipo_cultivo,
        pr.fecha_inicio, pr.fecha_fin
        FROM proyectos pr
        INNER JOIN contratistas c ON pr.id_cont = c.id_cont
        INNER JOIN proveedores p ON pr.id_prov = p.id_prov
        INNER JOIN fincas f ON pr.id_finca = f.id_finca
        WHERE pr.id_prov = ?
    ";

        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idProv]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTrabajadoresDeProyecto($idProy)
    {
        $sql = "SELECT t.* FROM proyectos_trabajadores pt 
                INNER JOIN trabajadores t ON pt.id_trab = t.id_trab 
                WHERE pt.id_proyec = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idProy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function asociarTrabajadorAProyecto($idTrab, $idProy)
    {
        $sql = "INSERT INTO proyectos_trabajadores (id_trab, id_proyec) VALUES (?, ?)";
    
        try {
            $stmt = $this->db->conn->prepare($sql);
            $stmt->execute([$idTrab, $idProy]);
        } catch (PDOException $e) {
            echo "Error al asociar trabajador: " . $e->getMessage();
            exit;
        }
    }
    
    public function eliminarTrabajadorDeProyecto($idTrab, $idProy)
    {
        $sql = "DELETE FROM proyectos_trabajadores WHERE id_trab = ? AND id_proyec = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$idTrab, $idProy]);
        echo json_encode(["mensaje" => "Trabajador eliminado del proyecto"]);
        exit;
    }
}

if (isset($_GET['action'])) {
    $controller = new ProyecController();

    switch ($_GET['action']) {
        case 'listarProyectos':
            $controller->getProyectos();
            break;

        case 'eliminarProyecto':
            if (isset($_GET['id'])) {
                $controller->delProyecto($_GET['id']);
            }
            break;

        case 'modificarProyecto':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'modificarProyecto') {
                header('Content-Type: application/json');

                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                if (isset($data['datos'])) {
                    $controller = new ProyecController();
                    $controller->updateProyecto(json_encode($data['datos']));
                } else {
                    echo json_encode(["error" => "No se recibieron datos válidos"]);
                }
            }
            break;

        case 'crearProyecto':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->setProyecto($_POST);
            }
            break;

        case 'listarProyectosContratista':
            if (isset($_GET['id_cont'])) {
                $proyectos = $controller->getProyectosPorContratista($_GET['id_cont']);
                header('Content-Type: application/json');
                echo json_encode($proyectos);
                exit;
            }
            break;
        case 'listarProyectosPorProveedor':
            if (isset($_GET['id_prov'])) {
                $proyectos = $controller->getProyectosPorProveedor($_GET['id_prov']);
                header('Content-Type: application/json');
                echo json_encode($proyectos);
                exit;
            }
            break;
        case 'asociarTrabajador':
            if (isset($_GET['id_proyec'], $_GET['id_trab'])) {
                $controller->asociarTrabajadorAProyecto($_GET['id_trab'], $_GET['id_proyec']);
            }
            break;

        case 'eliminarTrabajador':
            if (isset($_GET['id_proyec'], $_GET['id_trab'])) {
                $controller->eliminarTrabajadorDeProyecto($_GET['id_trab'], $_GET['id_proyec']);
            }
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode(["error" => "Acción no válida"]);
            exit;
    }
}
