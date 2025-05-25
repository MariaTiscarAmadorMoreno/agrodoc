<?php
session_start();
include_once(__DIR__ . '/../controllers/ContController.php');

header('Content-Type: application/json');

$controller = new ContController();

// Función auxiliar para sanitizar parámetros GET
function getParam($key, $default = null)
{
    return isset($_GET[$key]) ? htmlspecialchars($_GET[$key]) : $default;
}

// Función auxiliar para sanitizar input JSON
function getJsonBody()
{
    $input = file_get_contents('php://input');
    return json_decode($input, true) ?? [];
}

$action = getParam('action');
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (isset($_GET['action'])) {


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
                $controller->setContratista($_POST);
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
