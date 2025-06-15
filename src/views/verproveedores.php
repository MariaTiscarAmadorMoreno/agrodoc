<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

include_once('../controllers/ProvController.php');

$controller = new ProvController();
$datos = $controller->getProveedores();

?>

<h2> Lista de Proveedores </h2>
<div class="table-responsive">
    <table id="proveedoresTabla">
        <thead>
            <tr>
                <th class="ocultar-sm">id</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>CIF</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <?php if ($tipo === 'admin'): ?>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $proveedor): ?>
                <tr data-id="<?= $proveedor['id_prov'] ?>">
                    <td class="ocultar-sm"><?= $proveedor['id_prov'] ?></td>
                    <td class='editable'><?= $proveedor['nombre'] ?></td>
                    <td class='editable'><?= $proveedor['apellidos'] ?></td>
                    <td class='editable'><?= $proveedor['cif'] ?></td>
                    <td class='editable'><?= $proveedor['email'] ?></td>
                    <td class='editable'><?= $proveedor['telefono'] ?></td>
                    <td class='editable'><?= $proveedor['direccion'] ?></td>

                    <?php if ($tipo === 'admin'): ?>
                        <td>
                            <a href="javascript:cargar('#portada','/views/modificar_prov.php?id=<?= $proveedor['id_prov'] ?>')">
                                <img src="/assets/img/editar.png" class="editar icono">
                            </a>
                        </td>
                        <td>
                            <a href="javascript:eliminarProveedor(<?= $proveedor['id_prov'] ?>)">
                                <img src="/assets/img/eliminar.png" class="eliminar icono" alt="Eliminar">
                            </a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php if ($tipo === 'admin'): ?>
    <!-- Botón para crear proveedor solo si es administrador -->
    <div class="boton_crear">
        <a href="javascript:cargar('#portada','/views/nuevo_prov.php');">
            <button>Crear proveedor</button>
        </a>
    </div>
<?php endif; ?>

<?php if ($tipo === 'contratista'): ?>
    <div class="boton_crear">
        <a href="javascript:cargar('#portada','/views/verproveedores_contratista.php');">
            <button>Ver proveedores con los que trabajas</button>
        </a>
    </div>
<?php endif; ?>

<script src="/assets/js/proveedores.js"></script>