<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

include_once(__DIR__ . '/../controllers/TrabController.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de trabajador no especificado.";
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

$controller = new TrabController();
$trabajador = $controller->getTrabajadorPorId($id);

if (!$trabajador) {
    echo "Finca no encontrada.";
    exit;
}
?>

<div class="volver">
    <?php if ($tipo === 'admin'): ?>
        <a href="/views/app_admin.php?opcion=5"><button>Atrás</button></a>
    <?php else: ?>
        <a href="/views/app_proveedor.php?opcion=1"><button>Atrás</button></a>
    <?php endif; ?>
</div>
<div class="container_form">
    <h2 class="form-title">Modificar Trabajador</h2>
    <form id="formEditarTrabajador">
        <input type="hidden" id="id" value="<?= $trabajador['id_trab'] ?>">


        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($trabajador['nombre']) ?>">
        <div class="error" id="errorNombre"></div>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($trabajador['apellidos']) ?>">
        <div class="error" id="errorApellidos"></div>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($trabajador['dni']) ?>">
        <div class="error" id="errorDNI"></div>

        <label for="email">Correo:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($trabajador['email']) ?>">
        <div class="error" id="errorEmail"></div>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($trabajador['telefono']) ?>">
        <div class="error" id="errorTelefono"></div>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($trabajador['direccion']) ?>">
        <div class="error" id="errorDireccion"></div>  

        <?php if ($tipo === 'admin'): ?>
            <label for="documentos">Documentación:</label>
            <select id="documentos" name="documentos">
                <option value="1" <?= $trabajador['documentos'] ? 'selected' : '' ?>>Apto</option>
                <option value="0" <?= !$trabajador['documentos'] ? 'selected' : '' ?>>No Apto</option>
            </select>
            <div class="error" id="errorDocumentos"></div>
        <?php else: ?>
            <label for="documentos">Documentación:</label>
            <input type="text" id="documentos" name="documentos" value="<?= $trabajador['documentos'] ? 'Apto' : 'No Apto' ?>" readonly>
        <?php endif; ?>


        <label for="nombre_proveedor">Proveedor:</label>
        <input type="text" id="nombre_proveedor" value="<?= htmlspecialchars($trabajador['nombre_proveedor']) ?>" readonly>
        <input type="hidden" id="id_prov" name="id_prov" value="<?= htmlspecialchars($trabajador['id_prov']) ?>">


        <button type="submit">Guardar cambios</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_trab.js"></script>