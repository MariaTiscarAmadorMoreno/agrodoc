<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';

if (!in_array($tipo, ['admin', 'contratista'])) {
    echo "Acceso no autorizado.";
    exit;
}

include_once('../controllers/ProyecController.php');
$proyecController = new ProyecController();

$proyectos = [];

if ($tipo === 'admin') {
    $proyectos = $proyecController->getProyectos(); // Ver todos
} elseif ($tipo === 'contratista') {
    $idCont = $usuario['id_cont'];
    $proyectos = $proyecController->getProyectosPorContratista($idCont);
}
?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/verproyectos.php');"><button>Volver a Campañas</button></a>
</div>

<h2>Trabajadores asignados por campaña</h2>

<?php if (empty($proyectos)): ?>
    <p>No hay campañas disponibles.</p>
<?php else: ?>
    <?php foreach ($proyectos as $proyecto): ?>
        <div class="container_form">
            <h3>Campaña #<?= $proyecto['id_proyec'] ?> - <?= $proyecto['trabajo'] ?> (<?= $proyecto['localizacion_finca'] ?>)</h3>
            <p><strong>Proveedor:</strong> <?= $proyecto['nombre_proveedor'] ?? 'No disponible' ?></p>
            <p><strong>Contratista:</strong> <?= $proyecto['nombre_contratista'] ?? 'No disponible' ?></p>
            <p><strong>Fechas:</strong> <?= $proyecto['fecha_inicio'] ?> a <?= $proyecto['fecha_fin'] ?></p>
            
            <strong>Trabajadores asignados:</strong>
            <ul>
                <?php
                $trabajadores = $proyecController->getTrabajadoresDeProyecto($proyecto['id_proyec']);
                if (empty($trabajadores)) {
                    echo "<li>No hay trabajadores asignados.</li>";
                } else {
                    foreach ($trabajadores as $trab) {
                        echo "<li>{$trab['nombre']} {$trab['apellidos']}</li>";
                    }
                }
                ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
