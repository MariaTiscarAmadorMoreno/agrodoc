<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}
$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';
$idProv = $usuario['id_prov'] ?? null;
?>

<div class="volver">
    <?php if ($tipo === 'admin'): ?>
        <a href="/views/app_admin.php?opcion=6"><button>Atrás</button></a>
    <?php else: ?>
        <a href="/views/app_proveedor.php?opcion=1"><button>Atrás</button></a>
    <?php endif; ?>
</div>


<div class="container_form">
    <h2 class="form-title"> Nuevo trabajador </h2>

    <form id="formNuevoTrabajador" method="POST">

        <label for="nombre">Nombre:</label>
        <input placeholder="Nombre" name="nombre" type="text" id="nombre">
        <div class="error" id="errorNombre"></div>

        <label for="apellidos">Apellidos:</label>
        <input placeholder="Apellidos" name="apellidos" type="text" id="apellidos">
        <div class="error" id="errorApellidos"></div>

        <label for="dni">DNI:</label>
        <input placeholder="DNI" name="dni" type="text" id="dni">
        <div class="error" id="errorDni"></div>

        <label for="email">Correo Electrónico:</label>
        <input placeholder="Email" name="email" type="email" id="email">
        <div class="error" id="errorEmail"></div>

        <label for="telefono">Teléfono:</label>
        <input placeholder="Teléfono" name="telefono" type="text" id="telefono">
        <div class="error" id="errorTelefono"></div>

        <fieldset>
            <legend>Dirección</legend>

            <label for="calle">Calle:</label>
            <input type="text" id="calle" name="calle">
            <div class="error" id="errorCalle"></div>

            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero">
            <div class="error" id="errorNumero"></div>

            <label for="cp">Código Postal:</label>
            <input type="text" id="cp" name="cp">
            <div class="error" id="errorCP"></div>

            <label for="poblacion">Población:</label>
            <input type="text" id="poblacion" name="poblacion">
            <div class="error" id="errorPoblacion"></div>

            <label for="provincia">Provincia:</label>
            <input type="text" id="provincia" name="provincia">
            <div class="error" id="errorProvincia"></div>
        </fieldset>

        <!-- Desplegable de proveedores -->
        <?php if ($tipo === 'admin'): ?>
            <div id="proveedorField">
                <label for="id_prov">Selecciona un proveedor:</label>
                <select name="id_prov" id="id_prov">
                    <option value="">-- Seleccionar Proveedor --</option>
                </select>
                <div class="error" id="errorProveedor"></div>
            </div>
        <?php else: ?>
            <!-- insertamos automáticamente su id -->
            <input type="hidden" name="id_prov" value="<?= $idProv ?>">
        <?php endif; ?>

        <button type="submit" class="submit-btn">Crear trabajador</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/nuevo_trab.js"></script>