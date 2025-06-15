<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

include_once(__DIR__ . '/../controllers/FincasController.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID de finca no especificado.";
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

$controller = new FincasController();
$finca = $controller->getFincaPorId($id);

if (!$finca) {
    echo "Finca no encontrada.";
    exit;
}
?>

<div class="volver">
    <?php if ($tipo === 'admin'): ?>
        <a href="/views/app_admin.php?opcion=3"><button>Atrás</button></a>
    <?php else: ?>
        <a href="/views/app_contratista.php?opcion=1"><button>Atrás</button></a>
    <?php endif; ?>
</div>
<div class="container_form">
    <h2 class="form-title">Modificar Finca</h2>
    <form id="formEditarFinca">
        <input type="hidden" id="id" value="<?= $finca['id_finca'] ?>">


        <label for="localizacion">Localizacion:</label>
        <input type="text" id="localizacion" name="localizacion" value="<?= htmlspecialchars($finca['localizacion']) ?>">
        <div class="error" id="errorLocalizacion"></div>

        <label for="cultivo">Cultivo:</label>
        <input name="cultivo" type="text" id="cultivo" value="<?= htmlspecialchars($finca['cultivo']) ?>">
        <div class="error" id="errorCultivo"></div>

        <label for="hectarea">Tamaño en hectáreas:</label>
        <input name="hectarea" type="text" id="hectarea" value="<?= htmlspecialchars($finca['hectarea']) ?>">
        <div class="error" id="errorHectarea"></div>

        <label for="nombre_contratista">Contratista:</label>
        <input type="text" id="nombre_contratista" value="<?= htmlspecialchars($finca['nombre_contratista']) ?>" readonly>
        <input type="hidden" id="id_cont" name="id_cont" value="<?= htmlspecialchars($finca['id_cont']) ?>">


        <button type="submit">Guardar cambios</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_finca.js"></script>