<?php
session_start();
include_once(__DIR__ . '/../controllers/ProyecController.php');

$id_proy = $_POST['id_proy'] ?? null;
$id_trab = $_POST['id_trab'] ?? null;

if (!$id_proy || !$id_trab) {
    echo "Faltan datos";
    exit;
}

$controller = new ProyecController();
$controller->asociarTrabajadorAProyecto($id_trab, $id_proy);

// Redirigir al panel proveedor
header("Location: /views/app_proveedor.php?opcion=3");
exit;
