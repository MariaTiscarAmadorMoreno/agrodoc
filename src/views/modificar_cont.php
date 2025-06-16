<?php
include_once(__DIR__ . '/../controllers/ContController.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de contratista no especificado.";
    exit;
}

$controller = new ContController();
$contratista = $controller->getContratistaPorId($id);

if (!$contratista) {
    echo "Contratista no encontrado.";
    exit;
}
?>
<div class="volver">
    <a href="/views/app_admin.php?opcion=2"><button>Atrás</button></a>
</div>
<div class="container_form">
    <h2 class="form-title">Modificar Contratista</h2>
    <form id="formEditarContratista">
        <input type="hidden" id="id" value="<?= $contratista['id_cont'] ?>">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($contratista['nombre']) ?>">
        <div class="error" id="errorNombre"></div>

        <label for="cif">CIF:</label>
        <input type="text" id="cif"  name="cif" value="<?= htmlspecialchars($contratista['cif']) ?>">
        <div class="error" id="errorCIF"></div>

        <label for="email">Correo:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($contratista['email']) ?>">
        <div class="error" id="errorEmail"></div>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($contratista['telefono']) ?>">
        <div class="error" id="errorTelefono"></div>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($contratista['direccion']) ?>">
        <div class="error" id="errorDireccion"></div>

        <button type="submit" class="submit-btn">Guardar cambios</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_cont.js"></script>
