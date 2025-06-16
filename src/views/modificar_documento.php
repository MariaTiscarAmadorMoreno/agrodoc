<?php
session_start();
if (!isset($_SESSION['usuario']) || unserialize($_SESSION['usuario'])['tipo'] !== 'admin') {
    header("Location: /app/login");
    exit;
}

include_once(__DIR__ . '/../controllers/DocuController.php');

$id_doc = $_GET['id'] ?? null;
if (!$id_doc) {
    echo "ID de documento no especificado.";
    exit;
}

$docController = new DocuController();
$documento = $docController->getDocumentoPorId($id_doc);

if (!$documento) {
    echo "Documento no encontrado.";
    exit;
}
?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/verdocumentos.php')"><button>Volver</button></a>
</div>

<div class="container_form">
    <h2 class="form-title">Modificar Fecha de Caducidad</h2>

    <form id="formModificarDocumento" method="POST">
        <input type="hidden" name="id_doc" id="id_doc" value="<?= htmlspecialchars($documento['id_doc']) ?>">

        <label for="tipo_documento">Tipo de documento:</label>
        <input type="text" name="tipo_documento" id="tipo_documento" value="<?= strtoupper(str_replace('_', ' ', $documento['tipo_documento'])) ?>" readonly>

        <label>Archivo actual:</label>
        <a href="/api/descargar_documento.php?id=<?= $documento['id_doc'] ?>" target="_blank">Ver documento</a>

        <label for="fecha_caducidad">Fecha de caducidad:</label>
        <input type="date" name="fecha_caducidad" id="fecha_caducidad" value="<?= htmlspecialchars($documento['fecha_caducidad']) ?>">
        <div class="error" id="errorFecha"></div>

        <button type="submit" class="submit-btn">Guardar cambios</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_docu.js"></script>