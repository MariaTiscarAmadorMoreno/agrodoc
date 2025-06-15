<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'proveedor') {
    header("Location: /app/login");
    exit;
}

include_once(__DIR__ . '/../models/basededatos.php');

$idTrab = $_POST['id_trab'] ?? null;
$tipo = $_POST['tipo'] ?? null;
$archivo = $_FILES['archivo'] ?? null;

if (!$idTrab || !$tipo || !$archivo) {
    echo "Faltan datos necesarios.";
    exit;
}

// Asegurarse de que sea PDF
if ($archivo['type'] !== 'application/pdf') {
    echo "Solo se permiten archivos PDF.";
    exit;
}

// Obtener nombre de archivo destino
include_once(__DIR__ . '/../controllers/TrabController.php');
$trabCtrl = new TrabController();
$trabajador = $trabCtrl->getTrabajadorPorId($idTrab);

if (!$trabajador) {
    echo "Trabajador no encontrado.";
    exit;
}

$dni = $trabajador['dni'];
$nombreFinal = $tipo . '_' . $dni . '.pdf';
$rutaDestino = __DIR__ . '/../documentos_trab/' . $nombreFinal;

// Mover archivo
if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    echo "Error al guardar el archivo.";
    exit;
}

// Guardar en base de datos
try {
    $db = new basededatos();
    $sql = "INSERT INTO documentos (id_trab, tipo, ruta, fecha_caducidad)
            VALUES (?, ?, ?, NULL)
            ON DUPLICATE KEY UPDATE ruta = VALUES(ruta)";
    $stmt = $db->conn->prepare($sql);
    $stmt->execute([$idTrab, $tipo, $nombreFinal]);

    header("Location: /views/verdocumentos.php");
    exit;
} catch (PDOException $e) {
    echo "Error al guardar en la base de datos: " . $e->getMessage();
    exit;
}
