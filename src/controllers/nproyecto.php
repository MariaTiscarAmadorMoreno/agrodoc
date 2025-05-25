<?php
include_once(__DIR__ . '/../controllers/ProyecController.php');
session_start();

$bloque = [
    $_POST["id_cont"] ?? null,  // id_cont
    $_POST["id_prov"] ?? null,  // id_prov
    $_POST["id_finca"] ?? null, // id_finca
    $_POST["trabajo"] ?? null, // trabajo
    $_POST["fecha_inicio"] ?? null, // fecha_inicio
    $_POST["fecha_fin"] ?? null    // fecha_fin
];

$controller = new ProyecController();
$controller->setProyecto($bloque);

// Redirigimos según el tipo de usuario
$tipo = $_SESSION['usuario'] ? unserialize($_SESSION['usuario'])['tipo'] : null;

if ($tipo === 'admin') {
    header("Location: /views/app_admin.php?opcion=4");
} else {

    header("Location: /views/app_contratista.php?opcion=2");
}

exit();







