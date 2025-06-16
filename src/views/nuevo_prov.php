<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}
?>

<div class="volver">
    <a href="/views/app_admin.php?opcion=5"><button>Atrás</button></a>
</div>

<div class="container_form">
    <h2 class="form-title"> Nuevo proveedor </h2>

    <form id="formNuevoProveedor" method="POST">
        <label for="nombre">Nombre:<span>*</span></label>
        <input type="text" id="nombre" name="nombre">
        <div class="error" id="errorNombre"></div>

        <label for="apellidos">Apellidos:<span>*</span></label>
        <input type="text" id="apellidos" name="apellidos">
        <div class="error" id="errorApellidos"></div>

        <label for="cif">CIF:<span>*</span></label>
        <input type="text" id="cif" name="cif">
        <div class="error" id="errorCIF"></div>

        <label for="email">Correo electrónico:<span>*</span></label>
        <input type="email" id="email" name="email">
        <div class="error" id="errorEmail"></div>

        <label for="telefono">Teléfono:<span>*</span></label>
        <input type="text" id="telefono" name="telefono">
        <div class="error" id="errorTelefono"></div>
        <fieldset>
            <legend>Dirección<span>*</span></legend>

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

        <button type="submit" class="submit-btn">Crear contratista</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/nuevo_prov.js"></script>