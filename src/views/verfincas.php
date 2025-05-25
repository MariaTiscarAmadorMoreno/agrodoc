<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

include_once('../controllers/FincasController.php');

$controller = new FincasController();

if ($tipo === 'contratista') {
    $idCont = $usuario['id_cont'];
    $datos = $controller->getFincasPorContratista($idCont);
} else {

    $datos = $controller->getFincas();
}
?>
<div id="datosUsuario"
    data-tipo="<?= $tipo ?>"
    data-id-cont="<?= $usuario['id_cont'] ?? '' ?>">
</div>
<h2>Lista de Fincas</h2>
<div class="table-responsive">
    <table id="fincasTabla">
        <thead>
            <tr>
                <th class="ocultar-sm">ID</th>
                <th>Contratista</th>
                <th>Cultivo</th>
                <th>Hectáreas</th>
                <th>Localización</th>
                <th>Ver en mapa</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $finca): ?>
                <tr data-id="<?= $finca['id_finca'] ?>">
                    <td class="ocultar-sm"><?= $finca['id_finca'] ?></td>
                    <td><?= $finca['nombre_contratista'] ?? 'No disponible' ?></td>
                    <td class='editable'><?= $finca['cultivo'] ?></td>
                    <td class='editable'><?= $finca['hectarea'] ?></td>
                    <td class='editable' id="localizacion"><?= $finca['localizacion'] ?></td>
                    <td><a href="javascript:void(0);" class="enlace_ver">Ver en mapa</a></td>
                    <td>
                        <button class="editar">Modificar</button>
                        <button class="guardar" style="display:none;">Guardar</button>
                    </td>
                    <td>
                        <button class="eliminar" onclick="eliminarFinca(<?= $finca['id_finca'] ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div id="mapContainer">
    <div id="map"></div>
    <button class="cerrar_mapa" onclick="cerrarMapa()">✖</button>
</div>



<div class="boton_crear">
    <a href="javascript:cargar('#portada','/views/nueva_finca.php');">
        <button>Crear finca</button>
    </a>
</div>

<!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/fincas.js"></script>
<script src="/assets/js/modificar_finca.js"></script>
<script src="/assets/js/ver_mapa.js"></script>