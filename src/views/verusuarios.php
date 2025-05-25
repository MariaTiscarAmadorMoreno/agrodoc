<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

include_once('../controllers/UserController.php');

$controller = new UserController();
$datos = $controller->getUsuarios();
?>


<h2>Lista de usuarios</h2>
<div class="table-responsive">
    <table id="usuariosTabla">
        <thead>
            <tr>
                <th class="ocultar-sm">ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Clave</th>
                <th>Tipo</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $usuario): ?>
                <tr data-id="<?= $usuario['id_usu'] ?>"
                    data-tipo="<?= $usuario['tipo'] ?: 'admin' ?>"
                    <?= ($usuario['tipo'] === 'contratista') ? "data-id-cont='{$usuario['id_cont']}'" : "" ?>
                    <?= ($usuario['tipo'] === 'proveedor') ? "data-id-prov='{$usuario['id_prov']}'" : "" ?>>

                    <td class="ocultar-sm"><?= $usuario['id_usu'] ?></td>
                    <td class='editable'><?= $usuario['nombre'] ?></td>
                    <td class='editable'><?= $usuario['usuario'] ?></td>
                    <td class='editable'><?= $usuario['clave'] ?></td>

                    <td>
                        <?php
                        if ($usuario['tipo'] === 'contratista') {
                            echo "Contratista: " . ($usuario['nombre_contratista'] ?? 'No disponible');
                        } elseif ($usuario['tipo'] === 'proveedor') {
                            echo "Proveedor: " . ($usuario['nombre_proveedor'] ?? 'No disponible') . " " . ($usuario['apellidos_proveedor'] ?? 'No disponible');
                        } else {
                            echo "Administrador";
                        }
                        ?>
                    </td>
                    <td>
                        <button class="editar">Modificar</button>
                        <button class="guardar" style="display:none;">Guardar</button>
                    </td>
                    <td>
                        <button class="eliminar" onclick="eliminarUsuario(<?= $usuario['id_usu'] ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Botón para crear usuario -->
<div class="boton_crear">
    <a href="javascript:cargar('#portada','/views/nuevo_usu.php');">
        <button>Crear usuario</button>
    </a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/usuarios.js">
    cargarUsuarios();
</script>
<script src="/assets/js/modificar_usu.js"></script>