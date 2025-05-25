<?php 
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$idContratista = $usuario['id_cont'];

include_once('../controllers/ProvController.php');
include_once('../controllers/ProyecController.php');

$controller = new ProvController();
$datos = $controller->getProveedoresPorContratista($idContratista);

// Para obtener proyectos por proveedor
$proyecController = new ProyecController();
?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/verproveedores.php');"><button>Volver</button></a>
</div>

<h2>Proveedores relacionados con tus campañas</h2>

<?php foreach ($datos as $proveedor): ?>
    <div class="container_form">
        <p><strong>Nombre:</strong> <?= $proveedor['nombre'] ?> <?= $proveedor['apellidos'] ?></p>
        <p><strong>CIF:</strong> <?= $proveedor['cif'] ?></p>
        <p><strong>Email:</strong> <?= $proveedor['email'] ?></p>
        <p><strong>Teléfono:</strong> <?= $proveedor['telefono'] ?></p>
        <p><strong>Dirección:</strong> <?= $proveedor['direccion'] ?></p>

        <details>
            <summary><strong>Campañas</strong></summary>
            <ul class="lista1">
                <?php 
                    $proyectos = $proyecController->getProyectosPorProveedorYContratista($proveedor['id_prov'], $idContratista);

                    foreach ($proyectos as $p) {
                        echo "<li><strong>{$p['localizacion']}</strong>:  {$p['trabajo']} - {$p['fecha_inicio']} a {$p['fecha_fin']}</li>";
                    }
                ?>
            </ul>
        </details>
    </div>
<?php endforeach; ?>
