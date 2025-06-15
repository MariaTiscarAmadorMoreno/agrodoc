<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

include_once('../controllers/ProyecController.php');

$controller = new ProyecController();

if ($tipo === 'contratista') {
    $idCont = $usuario['id_cont'];
    $datos = $controller->getProyectosPorContratista($idCont);
} else if ($tipo === 'proveedor') {
    $idProv = $usuario['id_prov'];
    $datos = $controller->getProyectosPorProveedor($idProv);
} else {

    $datos = $controller->getProyectos();
}

?>
<div id="datosUsuario"
    data-tipo="<?= $tipo ?>"
    data-id-cont="<?= $usuario['id_cont'] ?? '' ?>">
</div>
<h2>Lista de Campañas</h2>
<div class="table-responsive">
    <table id="proyectosTabla">
        <thead>
            <tr>
                <th class="ocultar-sm">ID Campaña</th>
                <th>Tipo de trabajo</th>
                <th>Finca</th>
                <th>Ver en mapa</th>
                <th>Tipo de cultivo</th>
                <?php if ($tipo === 'admin' || $tipo === 'proveedor'): ?>
                    <th>Contratista</th>
                <?php endif; ?>
                <?php if ($tipo === 'admin' || $tipo === 'contratista'): ?>
                    <th>Proveedor</th>
                    <th>Trabajadores</th>
                <?php endif; ?>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <?php if ($tipo === 'admin' || $tipo === 'contratista'): ?>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $proyecto): ?>
                <tr data-id="<?= $proyecto['id_proyec'] ?>">
                    <td class="ocultar-sm"><?= $proyecto['id_proyec'] ?></td>
                    <td class='editable'><?= $proyecto['trabajo'] ?? 'No disponible' ?></td>
                    <td id="localizacion"><?= $proyecto['localizacion_finca'] ?? 'No disponible' ?></td>
                    <td><a href="javascript:void(0);" class="enlace_mapa">Ver en mapa</a></td>
                    <td><?= $proyecto['tipo_cultivo'] ?? 'No disponible' ?></td>
                    <?php if ($tipo === 'admin' || $tipo === 'proveedor'): ?>
                        <td><?= $proyecto['nombre_contratista'] ?? 'No disponible' ?></td>
                    <?php endif; ?>
                    <?php if ($tipo === 'admin' || $tipo === 'contratista'): ?>
                        <td><?= $proyecto['nombre_proveedor'] ?? 'No disponible' ?></td>
                        <td><a href="javascript:cargar('#portada','/views/vertrab_proyec_admin_cont.php');" class="enlace_ver">Ver trabajadores</a></td>
                    <?php endif; ?>
                    <td class="fecha-inicio editable"><?= $proyecto['fecha_inicio'] ?></td>
                    <td class="fecha-fin editable"><?= $proyecto['fecha_fin'] ?></td>

                    <?php if ($tipo === 'admin' || $tipo === 'contratista'): ?>
                        <td>
                            <a href="javascript:cargar('#portada','/views/modificar_proyec.php?id=<?= $proyecto['id_proyec'] ?>')">
                                <img src="/assets/img/editar.png" class="editar icono">
                            </a>
                        </td>
                        <td>
                            <a href="javascript:eliminarProyecto(<?= $proyecto['id_proyec'] ?>)">
                                <img src="/assets/img/eliminar.png" class="eliminar icono" alt="Eliminar">
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div id="mapContainer">
    <div id="map"></div>
    <button class="cerrar_mapa" onclick="cerrarMapa()">✖</button>
</div>
<?php if ($tipo === 'admin' || $tipo === 'contratista'): ?>
    <div class="boton_crear">
        <a href="javascript:cargar('#portada','/views/nuevo_proyec.php');">
            <button>Nueva campaña</button>
        </a>
    </div>
<?php endif; ?>

<?php if ($tipo === 'proveedor'): ?>
    <div class="boton_crear">
        <a href="javascript:cargar('#portada','/views/vertrabajadores_proyecto.php');">
            <button>Ver trabajadores por campaña</button>
        </a>
    </div>
<?php endif; ?>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/proyectos.js"></script>
<script src="/assets/js/ver_mapa.js"></script>