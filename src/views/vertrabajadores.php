<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

include_once('../controllers/TrabController.php');

$controller = new TrabController();
$datos = $controller->getTrabajadores();

if ($tipo === 'proveedor') {
    $idProv = $usuario['id_prov'];
    $datos = $controller->getTrabajadoresPorProveedor($idProv);
} else {

    $datos = $controller->getTrabajadores();
}
?>
<div id="datosUsuario"
    data-tipo="<?= $tipo ?>"
    data-id-prov="<?= $usuario['id_prov'] ?? '' ?>">
</div>

<h2>Lista de Trabajadores</h2>
<div class="table-responsive">
    <table id="trabajadoresTabla">
        <thead>
            <tr>
                <th class="ocultar-sm">ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>DNI</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Documentos</th>
                <th>Empresa</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $trabajador): ?>
                <tr data-id="<?= $trabajador['id_trab'] ?>">
                    <td class="ocultar-sm"><?= $trabajador['id_trab'] ?></td>
                    <td class='editable'><?= $trabajador['nombre'] ?></td>
                    <td class='editable'><?= $trabajador['apellidos'] ?></td>
                    <td class='editable'><?= $trabajador['dni'] ?></td>
                    <td class='editable'><?= $trabajador['email'] ?></td>
                    <td class='editable'><?= $trabajador['telefono'] ?></td>
                    <td class='editable'><?= $trabajador['direccion'] ?></td>
                    <td><?= $trabajador['documentos'] ? 'Sí' : 'No' ?></td>
                    <td><?= $trabajador['nombre_proveedor'] ?? 'No disponible' ?> <?= $trabajador['apellidos_proveedor'] ?? 'No disponible' ?></td>
                    <td>
                        <button class="editar">Modificar</button>
                        <button class="guardar" style="display:none;">Guardar</button>
                    </td>
                    <td>
                        <button class="eliminar" onclick="eliminarTrabajador(<?= $trabajador['id_trab'] ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="boton_crear">
    <a href="javascript:cargar('#portada','/views/nuevo_trab.php');">
        <button>Crear trabajador</button>
    </a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/trabajadores.js"></script>
<script src="/assets/js/modificar_trab.js"></script>