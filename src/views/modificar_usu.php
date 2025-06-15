<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

include_once(__DIR__ . '/../controllers/UserController.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID de usuario no especificado.";
    exit;
}

$controller = new UserController();
$usuario = $controller->getUsuarioPorId($id);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}
?>
<div class="volver">
   <a href="/views/app_admin.php?opcion=1"><button>Atrás</button></a>
</div>

<div class="container_form">
    <h2 class="form-title"> Modificar usuario </h2>
    <form id="formEditarUsuario" method="POST" action="#">

        <input type="hidden" name="id" id="id" value="<?= $usuario['id_usu'] ?>">
        <input type="hidden" name="tipo" id="tipo" value="<?= $usuario['tipo'] ?>">

        <label>Tipo de usuario:</label>
        <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($usuario['tipo']) ?>" readonly>


        <label>Nombre completo:</label>
        <input type="text" name="nombre" id="nombre" class="editable" value="<?= htmlspecialchars($usuario['nombre']) ?>">
        <div class="error" id="errorNombre"></div>

        <label>Usuario:</label>
        <input type="text" name="usuario" id="usuario" class="editable" value="<?= htmlspecialchars($usuario['usuario']) ?>">
        <div class="error" id="errorUsuario"></div>

        <label>Contraseña:</label>
        <input type="text" name="clave" id="clave" class="editable" value="<?= htmlspecialchars($usuario['clave']) ?>">
        <div class="error" id="errorClave"></div>

        <button type="submit">Guardar cambios</button>

        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/modificar_usu.js"></script>