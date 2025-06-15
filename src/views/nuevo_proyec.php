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
    <?php if ($tipo === 'admin'): ?>
        <a href="/views/app_admin.php?opcion=4"><button>Atrás</button></a>
    <?php else: ?>
        <a href="/views/app_proveedor.php?opcion=2"><button>Atrás</button></a>
    <?php endif; ?>
</div>

<div class="container_form">
    <h2 class="form-title"> Nueva Campaña </h2>

    <form id="formNuevoProyecto" method="POST">

        <!-- Desplegable de contratistas -->
        <?php if ($tipo === 'admin'): ?>
            <div id="contratistaField">
                <label for="id_cont">Selecciona un contratista:</label>
                <select name="id_cont" id="id_cont">
                    <option value="">-- Seleccionar Contratista --</option>
                </select>
                <div class="error" id="errorContratista"></div>
            </div>
        <?php else: ?>
            <input type="hidden" name="id_cont" value="<?= $idCont ?>">
        <?php endif; ?>

        <!-- Desplegable de fincas asociadas al contratista. -->

        <label for="id_finca">Selecciona una Finca:</label>
        <select name="id_finca" id="id_finca">
            <option value="">-- Seleccionar Finca --</option>
        </select>
        <div class="error" id="errorFinca"></div>


        <!-- Desplegable de proveedores -->
        <div id="proveedorField">
            <label for="id_prov">Selecciona un proveedor:</label>
            <select name="id_prov" id="id_prov">
                <option value="">-- Seleccionar Proveedor --</option>
            </select>
        </div>

        <label for="trabajo">Trabajo a realizar:</label>
        <input name="trabajo" type="text" id="trabajo">
        <div class="error" id="errorTrabajo"></div>


        <label for="fecha_inicio">Fecha Inicio:</label>
        <input name="fecha_inicio" type="date" id="fecha_inicio">

        <label for="fecha_fin">Fecha Fin:</label>
        <input name="fecha_fin" type="date" id="fecha_fin">


        <button type="submit" class="submit-btn">Crear campaña</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/nuevo_proyec.js"></script>