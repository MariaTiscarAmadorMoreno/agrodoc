<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

include_once('../controllers/DocuController.php');
include_once('../controllers/TrabController.php');

$docController = new DocuController();
$trabController = new TrabController();

if ($tipo === 'proveedor') {
    $idProv = $usuario['id_prov'];
    $trabajadores = $trabController->getTrabajadoresPorProveedor($idProv);
} else {
    $trabajadores = $trabController->getTrabajadores();
}
?>

<h2>Documentación de Trabajadores</h2>
<div class="table-responsive">
    <?php foreach ($trabajadores as $trabajador): ?>
        <?php
        $documentos = $docController->getDocumentosPorTrabajador($trabajador['id_trab']);
        $tiposRequeridos = ['dni', 'alta_ss', 'prl', 'reconocimiento_medico', 'aut_maquinaria'];

        $hoy = date('Y-m-d');
        $documentosValidos = true;
     
        foreach ($tiposRequeridos as $tipoDoc) {
            $doc = $documentos[$tipoDoc] ?? null;
            $caducado = $doc && $doc['fecha_caducidad'] && $doc['fecha_caducidad'] < date('Y-m-d');

            if (!$doc || $caducado) {
                $documentosValidos = false;
                break;
            }
        }

        // Actualiza el campo en la base de datos según sea válido o no.
        $trabController->actualizarEstadoDocumentacion($trabajador['id_trab'], $documentosValidos);
        ?>

        <table class="tabla-documentos">
            <caption>
                <h3><?= htmlspecialchars($trabajador['nombre']) . ' ' . htmlspecialchars($trabajador['apellidos']) ?></h3>
            </caption>
            <thead>
                <tr>
                    <th>Tipo de Documento</th>
                    <th>Archivo</th>
                    <th>Fecha Caducidad</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tiposRequeridos as $tipoDoc): ?>
                    <?php
                    $doc = $documentos[$tipoDoc] ?? null;
                    $caducado = $doc && $doc['fecha_caducidad'] && $doc['fecha_caducidad'] < date('Y-m-d');
                    ?>
                    <tr>
                        <td><?= strtoupper(str_replace('_', ' ', $tipoDoc)) ?></td>
                        <td>
                            <?php if ($doc): ?>
                                <a href="/documentos_trab/<?= basename($doc['ruta_archivo']) ?>" target="_blank">Ver</a>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                        <td><?= $doc['fecha_caducidad'] ?? 'N/A' ?></td>
                        <td>
                            <?php if (!$doc): ?>
                                <i class="fa-solid fa-xmark text-red"></i> Falta
                            <?php elseif ($caducado): ?>
                                <i class="fa-solid fa-triangle-exclamation text-orange"></i> Caducado
                            <?php else: ?>
                                <i class="fa-solid fa-check text-green"></i> Válido
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($tipo === 'proveedor' && (!$doc || $caducado)): ?>
                                <!-- Proveedor puede subir si no existe o si está caducado -->
                                <a href="javascript:cargar('#portada','/views/subir_documento.php?id_trab=<?= $trabajador['id_trab'] ?>&tipo=<?= $tipoDoc ?>')">
                                    <img src="/assets/img/enviar.png" class="icono" alt="Subir" title="Subir documento">
                                </a>
                            <?php elseif ($tipo === 'admin' && $doc): ?>
                                <!-- Admin puede modificar si existe -->
                                <a href="javascript:cargar('#portada','/views/modificar_documento.php?id=<?= $doc['id_doc'] ?>')">
                                    <img src="/assets/img/editar.png" class="icono" alt="Modificar" title="Modificar fecha">
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
    <?php endforeach; ?>
</div>

<script src="/assets/js/validar_documentos.js"></script>