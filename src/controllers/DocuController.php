<?php
include_once(__DIR__ . '/../models/basededatos.php');

class DocuController
{
    private $db;

    public function __construct()
    {
        $this->db = new basededatos();
    }

    public function getDocumentosPorTrabajador($id_trab)
    {
        $sql = "SELECT * FROM documentos WHERE id_trab = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$id_trab]);
        $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];
        foreach ($docs as $doc) {
            $resultado[$doc['tipo_documento']] = $doc;
        }

        return $resultado;
    }

    public function insertarDocumento($tipo, $ruta, $fechaCaducidad, $id_trab)
    {
        $sql = "INSERT INTO documentos (tipo_documento, ruta_archivo, fecha_caducidad, id_trab) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute([$tipo, $ruta, $fechaCaducidad, $id_trab]);
    }

    public function actualizarDocumento($id_doc, $nuevaRuta, $nuevaFecha)
    {
        $sql = "UPDATE documentos SET ruta_archivo = ?, fecha_caducidad = ? WHERE id_doc = ?";
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute([$nuevaRuta, $nuevaFecha, $id_doc]);
    }

    public function actualizarFechaCaducidad($id_doc, $nuevaFecha)
    {
        $sql = "UPDATE documentos SET fecha_caducidad = ? WHERE id_doc = ?";
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute([$nuevaFecha, $id_doc]);
    }

    public function getDocumentoPorId($id_doc)
    {
        $sql = "SELECT * FROM documentos WHERE id_doc = ?";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$id_doc]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function borrarDocumento($id_doc)
    {
        $sql = "DELETE FROM documentos WHERE id_doc = ?";
        $stmt = $this->db->conn->prepare($sql);
        return $stmt->execute([$id_doc]);
    }
}


if (isset($_GET['action'])) {
    $controller = new DocuController();

    header('Content-Type: application/json');

    switch ($_GET['action']) {
        case 'listarDocumentos':
            if (isset($_GET['id_trab'])) {
                echo json_encode($controller->getDocumentosPorTrabajador($_GET['id_trab']));
            } else {
                echo json_encode(["error" => "ID de trabajador no especificado."]);
            }
            break;

        case 'eliminarDocumento':
            if (isset($_GET['id_doc'])) {
                $ok = $controller->borrarDocumento($_GET['id_doc']);
                echo json_encode($ok ? ["mensaje" => "Documento eliminado."] : ["error" => "No se pudo eliminar."]);
            } else {
                echo json_encode(["error" => "ID de documento no especificado."]);
            }
            break;

        case 'modificarDocumento':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                if (isset($data['id_doc'], $data['fecha_caducidad'])) {
                    $resultado = $controller->actualizarFechaCaducidad($data['id_doc'], $data['fecha_caducidad']);
                    if ($resultado) {
                        echo json_encode(["mensaje" => "Fecha de caducidad actualizada correctamente."]);
                    } else {
                        echo json_encode(["error" => "No se pudo actualizar la fecha."]);
                    }
                } else {
                    echo json_encode(["error" => "Datos incompletos."]);
                }
            } else {
                echo json_encode(["error" => "Método no permitido."]);
            }
            break;

        default:
            echo json_encode(["error" => "Acción no válida."]);
            break;
    }

    exit;
}
