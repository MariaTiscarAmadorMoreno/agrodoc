<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$idProveedor = $usuario['id_prov'];

include_once('../controllers/ProyecController.php');
include_once('../controllers/TrabController.php');

$proyecController = new ProyecController();
$trabController = new TrabController();

// Obtener campañas del proveedor
$proyectos = $proyecController->getProyectosPorProveedor($idProveedor);

// Obtener trabajadores disponibles del proveedor
$trabajadoresDisponibles = $trabController->getTrabajadoresPorProveedor($idProveedor);
?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/verproyectos.php');"><button>Volver a lista de Campañas</button></a>
</div>

<h2>Gestión de trabajadores por campaña</h2>

<?php foreach ($proyectos as $proyecto): ?>

    <div class="container_form">

        <h3>Campaña #<?= $proyecto['id_proyec'] ?> - <?= $proyecto['localizacion_finca'] ?> - <?= $proyecto['trabajo'] ?></h3>

        <!-- Trabajadores asignados -->
        <strong>Trabajadores asignados:</strong>
        <ul>
            <?php
            $asignados = $proyecController->getTrabajadoresDeProyecto($proyecto['id_proyec']);
            foreach ($asignados as $t) {
                echo "<li>{$t['nombre']} {$t['apellidos']}
                 <button class='btn-eliminar-trabajador' 
                 data-id-trab='{$t['id_trab']}' 
                 data-id-proyec='{$proyecto['id_proyec']}'>
                 Eliminar
                 </button>
                </li>";
            }
            ?>
        </ul>


        <!-- Formulario para añadir trabajador -->
        <form action="/controllers/ntrabajador_proyecto.php" method="POST">
            <?php
            // Array de los trabajadores ya asignados
            $idsAsignados = array_column($asignados, 'id_trab');
            ?>
            <input type="hidden" name="id_proy" value="<?= $proyecto['id_proyec'] ?>">
            <label>Añadir trabajador:</label>
            <select name="id_trab" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($trabajadoresDisponibles as $trab): ?>
                    <?php if (!in_array($trab['id_trab'], $idsAsignados)): ?>
                        <option value="<?= $trab['id_trab'] ?>"><?= $trab['nombre'] ?> <?= $trab['apellidos'] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <button type="submit">Añadir</button>
        </form>
    </div>
<?php endforeach; ?>
<script src="/assets/js/proyectos.js"></script>