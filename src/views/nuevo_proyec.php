<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';
$idCont = $usuario['id_cont'] ?? null;
?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/verproyectos.php');"><button>Volver</button></a>
</div>
<!-- fecha_inicio, fecha_fin, id_cont, id_prov, id_finca -->
<div class="container_form">
    <h2 class="form-title"> Nueva Campaña </h2>

    <form action="/controllers/nproyecto.php" method="POST">

        <!-- Desplegable de contratistas -->
        <?php if ($tipo === 'admin'): ?>
            <!-- Mostrar selector de contratistas solo a administradores -->
            <div id="contratistaField">
                <label for="id_cont">Selecciona un contratista:</label>
                <select name="id_cont" id="id_cont">
                    <option value="">-- Seleccionar Contratista --</option>
                </select>
            </div>
        <?php else: ?>
            <!-- Para contratistas insertamos automáticamente su id -->
            <input type="hidden" name="id_cont" value="<?= $idCont ?>">
        <?php endif; ?>

        <!-- Desplegable de fincas asociadas al contratista. -->
        <div id="fincaField">
            <label for="id_finca">Selecciona una Finca:</label>
            <select name="id_finca" id="id_finca">
                <option value="">-- Seleccionar Finca --</option>
            </select>
        </div>
        <div>
            <label for="trabajo">Trabajo:</label>
            <input placeholder="Trabajo" required="required" name="trabajo" type="text" id="trabajo">
        </div>
        <!-- Desplegable de proveedores -->
        <div id="proveedorField">
            <label for="id_prov">Selecciona un proveedor:</label>
            <select name="id_prov" id="id_prov">
                <option value="">-- Seleccionar Proveedor --</option>
            </select>
        </div>

        <div>
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input placeholder="Fecha Inicio" required name="fecha_inicio" type="date" id="fecha_inicio">
        </div>
        <div>
            <label for="fecha_fin">Fecha Fin:</label>
            <input placeholder="Fecha Fin" required name="fecha_fin" type="date" id="fecha_fin">
        </div>

        <input type="submit" value="Insertar" class="submit-btn">
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/nuevo_proyec.js"></script>