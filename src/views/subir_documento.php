<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

if ($tipo !== 'proveedor') {
    echo "Acceso denegado.";
    exit;
}

$idTrab = $_GET['id_trab'] ?? null;
$tipoDocumento = $_GET['tipo'] ?? null;

if (!$idTrab || !$tipoDocumento) {
    echo "Faltan parámetros necesarios.";
    exit;
}

include_once(__DIR__ . '/../controllers/TrabController.php');
$trabController = new TrabController();
$trabajador = $trabController->getTrabajadorPorId($idTrab);

if (!$trabajador) {
    echo "Trabajador no encontrado.";
    exit;
}

?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/verdocumentos.php');">
        <button>Volver</button>
    </a>
</div>

<div class="container_form">
    <h2>Subir Documento - <?= strtoupper(str_replace('_', ' ', $tipoDocumento)) ?></h2>

    <p>Trabajador: <strong><?= htmlspecialchars($trabajador['nombre']) . ' ' . htmlspecialchars($trabajador['apellidos']) ?></strong></p>

    <form id="formSubirDocumento" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_trab" value="<?= $idTrab ?>">
        <input type="hidden" name="tipo_documento" value="<?= $tipoDocumento ?>">

        <label for="archivo">Seleccionar archivo:</label>
        <input type="file" name="archivo" id="archivo" accept="application/pdf,image/*">
        <div class="error" id="errorArchivo"></div>

        <label for="fecha_caducidad">Fecha de caducidad:</label>
        <input type="date" name="fecha_caducidad" id="fecha_caducidad">
        <div class="error" id="errorFecha"></div>

        <button type="submit">Subir documento</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/validar_documentos.js"></script>

