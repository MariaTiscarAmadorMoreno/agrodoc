<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$idProveedor = $usuario['id_prov'];

include_once('../controllers/ContController.php');
include_once('../controllers/ProyecController.php');

$controller = new ContController();
$datos = $controller->getContratistasPorProveedor($idProveedor);

// Para obtener proyectos por proveedor
$proyecController = new ProyecController();

?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/vercontratistas.php');"><button>Volver a lista de Contratistas</button></a>
</div>

<h2>Contratistas relacionados con tus campañas</h2>
<?php foreach ($datos as $contratista): ?>
     
    <div class="container_form">
        <p><strong>Nombre:</strong> <?= $contratista['nombre'] ?></p>
        <p><strong>CIF:</strong> <?= $contratista['cif'] ?></p>
        <p><strong>Email:</strong> <?= $contratista['email'] ?></p>
        <p><strong>Teléfono:</strong> <?= $contratista['telefono'] ?></p>
        <p><strong>Dirección:</strong> <?= $contratista['direccion'] ?></p>


        <details>
            <summary><strong>Campañas en colaboración</strong></summary>
            <ul class="lista1">
                <?php
                $proyectos = $proyecController->getProyectosPorProveedorYContratista($idProveedor, $contratista['id_cont']);

                foreach ($proyectos as $p) {
                    echo "<li><strong>{$p['localizacion']}</strong>:  {$p['trabajo']} - {$p['fecha_inicio']} a {$p['fecha_fin']}</li>";
                }
                ?>
            </ul>
        </details>
    </div>
<?php endforeach; ?>