<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

header('Content-Type: application/json');

include_once(__DIR__ . '/../controllers/DocuController.php');
include_once(__DIR__ . '/../controllers/TrabController.php');

$usuario = unserialize($_SESSION['usuario']);
$docController = new DocuController();
$trabController = new TrabController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_trab = $_POST['id_trab'] ?? null;
    $tipo = $_POST['tipo_documento'] ?? null;
    $fecha_caducidad = $_POST['fecha_caducidad'] ?? null;

    if (!$id_trab || !$tipo || !isset($_FILES['archivo'])) {
        echo json_encode(["error" => "Faltan datos requeridos."]);
        exit;
    }

    $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
    $extPermitidas = ['pdf', 'png', 'jpg', 'jpeg'];

    if (!in_array(strtolower($ext), $extPermitidas)) {
        echo json_encode(["error" => "Tipo de archivo no permitido."]);
        exit;
    }
    $trabajador = $trabController->getTrabajadorPorId($id_trab);
    if (!$trabajador) {
        echo json_encode(["error" => "Trabajador no encontrado."]);
        exit;
    }

    $dni = $trabajador['dni'];
    $directorioDestino = '/var/www/documentos_trab';



    $nombreArchivo = "{$tipo}_{$dni}.pdf";
    $rutaFinal = $directorioDestino . '/' . $nombreArchivo;


    if (!is_dir($directorioDestino)) {
        mkdir($directorioDestino, 0755, true);
    }

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaFinal)) {
        // Verificar si el documento ya existe
        $documentosExistentes = $docController->getDocumentosPorTrabajador($id_trab);
        if (isset($documentosExistentes[$tipo])) {
            // Ya existe, actualizamos
            $id_doc = $documentosExistentes[$tipo]['id_doc'];
            $docController->actualizarDocumento($id_doc, "documentos_trab/$nombreArchivo", $fecha_caducidad);
        } else {
            // Nuevo documento
            $docController->insertarDocumento($tipo, "documentos_trab/$nombreArchivo", $fecha_caducidad, $id_trab);
        }
        echo json_encode(["mensaje" => "Documento subido correctamente."]);
        exit;
    } else {
        echo json_encode(["error" => "Error al subir el archivo."]);
        exit;
    }
}
