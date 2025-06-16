<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

include_once(__DIR__ . '/../controllers/ProyecController.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID del proyecto no especificado.";
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

$controller = new ProyecController();
$proyecto = $controller->getProyectoPorId($id);

if (! $proyecto) {
    echo "Proyecto no encontrado.";
    exit;
}
?>

<div class="volver">
    <?php if ($tipo === 'admin'): ?>
        <a href="/views/app_admin.php?opcion=4"><button>Atrás</button></a>
    <?php else: ?>
        <a href="/views/app_contratista.php?opcion=2"><button>Atrás</button></a>
    <?php endif; ?>
</div>
<div class="container_form">
    <h2 class="form-title">Modificar Campaña</h2>
    <form id="formEditarProyecto">
        <input type="hidden" id="id" value="<?=  $proyecto['id_proyec'] ?>">


        <label for="contratista">Contratista:</label>      
        <input type="text" id="contratista" name="contratista" value="<?=  $proyecto['nombre_contratista']?>" readonly>
    

        <label for="id_finca">Finca:</label>      
        <input type="text" id="id_finca" name="finca" value="<?=  $proyecto['localizacion_finca']?>" readonly>

        <label for="trabajo">Trabajo:</label>      
        <input type="text" id="trabajo" name="trabajo" value="<?=  $proyecto['trabajo']?>">
        <div class="error" id="errorTrabajo"></div> 

        <label for="fecha_inicio">Fecha inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?=  $proyecto['fecha_inicio'] ?>">
        <div class="error" id="errorFechaInicio"></div>

        <label for="fecha_fin">Fecha fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" value="<?=  $proyecto['fecha_fin'] ?>">
        <div class="error" id="errorFechaFin"></div>


        <button type="submit" class="submit-btn">Guardar cambios</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_proyec.js"></script>