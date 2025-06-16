<?php
include_once(__DIR__ . '/../controllers/ProvController.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de proveedor no especificado.";
    exit;
}

$controller = new ProvController();
$proveedor = $controller->getProveedorPorId($id);

if (!$proveedor) {
    echo "Proveedor no encontrado.";
    exit;
}
?>
<div class="volver">
    <a href="/views/app_admin.php?opcion=5"><button>Atrás</button></a>
</div>
<div class="container_form">
    <h2 class="form-title">Modificar Proveedor</h2>
    <form id="formEditarProveedor">
        <input type="hidden" id="id" value="<?= $proveedor['id_prov'] ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre"  name="nombre" value="<?= htmlspecialchars($proveedor['nombre']) ?>">
        <div class="error" id="errorNombre"></div>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($proveedor['apellidos']) ?>">
        <div class="error" id="errorApellidos"></div>

        <label for="cif">CIF:</label>
        <input type="text" id="cif"  name="cif" value="<?= htmlspecialchars($proveedor['cif']) ?>">
        <div class="error" id="errorCIF"></div>

        <label for="email">Correo:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($proveedor['email']) ?>">
        <div class="error" id="errorEmail"></div>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono"  value="<?= htmlspecialchars($proveedor['telefono']) ?>">
        <div class="error" id="errorTelefono"></div>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($proveedor['direccion']) ?>">
        <div class="error" id="errorDireccion"></div>

        <button type="submit" class="submit-btn">Guardar cambios</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_prov.js"></script>
