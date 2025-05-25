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
                            <button class="editar">Modificar</button>
                            <button class="guardar" style="display:none;">Guardar</button>
                        </td>
                        <td>
                            <button class="eliminar" onclick="eliminarProveedor(<?= $proveedor['id_prov'] ?>)">Eliminar</button>
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


<!-- Scripts solo si es admin -->
<?php if ($tipo === 'admin'): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/assets/js/proveedores.js"></script>
    <script src="/assets/js/modificar_prov.js"></script>
<?php endif; ?>