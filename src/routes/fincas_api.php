<?php
session_start();
include_once(__DIR__ . '/../controllers/FincasController.php');

header('Content-Type: application/json');

$controller = new FincasController();

// Función auxiliar para sanitizar parámetros GET
function getParam($key, $default = null) {
    return isset($_GET[$key]) ? htmlspecialchars($_GET[$key]) : $default;
}

// Función auxiliar para sanitizar input JSON
function getJsonBody() {
    $input = file_get_contents('php://input');
    return json_decode($input, true) ?? [];
}

$action = getParam('action');
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($action) {
    case 'listarFincas':
        echo json_encode($controller->getFincas());
        break;

    case 'listarFincasPorContratista':
        if ($id) {
            echo json_encode($controller->getFincasPorContratista($id));
        } else {
            echo json_encode(["error" => "ID contratista no válido"]);
        }
        break;

    case 'listarFincasPorProveedor':
        if ($id) {
            echo json_encode($controller->getFincasPorProveedor($id));
        } else {
            echo json_encode(["error" => "ID proveedor no válido"]);
        }
        break;

    case 'eliminarFinca':
        if ($id) {
            $resultado = $controller->delFinca($id);
            echo json_encode(["mensaje" => $resultado ? "Finca eliminada correctamente." : "No se encontró la finca."]);
        } else {
            echo json_encode(["error" => "ID no válido"]);
        }
        break;
    case 'modificarFinca':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'modificarFinca') {
                header('Content-Type: application/json');

                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                if (isset($data['datos'])) {
                    $controller = new FincasController();
                    $controller->updateFinca(json_encode($data['datos']));
                } else {
                    echo json_encode(["error" => "No se recibieron datos válidos"]);
                }
            }
    // case 'modificarFinca':
    //     $data = getJsonBody();
    //     if (isset($data['datos']) && is_array($data['datos'])) {
    //         $datos = $data['datos'];
    //         // Sanitizamos
    //         $datos = [
    //             'id' => intval($datos[0]),
    //             'cultivo' => htmlspecialchars($datos[1]),
    //             'hectarea' => floatval($datos[2]),
    //             'localizacion' => htmlspecialchars($datos[3])
    //         ];
    //         $ok = $controller->updateFinca($datos);
    //         echo json_encode(["mensaje" => $ok ? "Finca actualizada correctamente." : "No hubo cambios."]);
    //     } else {
    //         echo json_encode(["error" => "Datos no válidos"]);
    //     }
        break;

    case 'crearFinca':
        $data = getJsonBody();
        if (isset($data['datos']) && is_array($data['datos'])) {
            $datos = $data['datos'];           
            $datos = [
                'localizacion' => htmlspecialchars($datos['localizacion']),
                'cultivo' => htmlspecialchars($datos['cultivo']),
                'hectarea' => floatval($datos['hectarea']),
                'id_cont' => intval($datos['id_cont'])
            ];
            $id = $controller->setFinca($datos);
            echo json_encode(["mensaje" => "Finca creada correctamente.", "id" => $id]);
        } else {
            echo json_encode(["error" => "Datos no válidos"]);
        }
        break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
        break;
}
?>
