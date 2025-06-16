<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}
?>

<div class="volver">
   <a href="/views/app_admin.php?opcion=1"><button>Atrás</button></a>
</div>

<div class="container_form">
    <h2 class="form-title"> Nuevo usuario </h2>

    <form id="formNuevoUsuario" method="POST">
        <h3>Crear nuevo usuario</h3>

        <label for="usuario">Nombre de usuario:<span>*</span></label>
        <input type="text" id="usuario" name="usuario">
        <div class="error" id="errorUsuario"></div>

        <label for="clave">Contraseña:<span>*</span></label>
        <input type="text" id="clave" name="clave">
        <div class="error" id="errorClave"></div>

        <label for="nombre">Nombre completo:<span>*</span></label>
        <input type="text" id="nombre" name="nombre">
        <div class="error" id="errorNombre"></div>

        <label for="tipo">Tipo de usuario:<span>*</span></label>
        <select id="tipo" name="tipo">
            <option value="">-- Seleccionar tipo --</option>
            <option value="admin">Administrador</option>
            <option value="contratista">Contratista</option>
            <option value="proveedor">Proveedor</option>
        </select>
        <div class="error" id="errorTipo"></div>

        <!-- Contratista (aparece solo si se selecciona) -->
        <div id="contratistaField" style="display: none;">
            <label for="id_cont">Contratista:<span>*</span></label>
            <select id="id_cont" name="id_cont"></select>
            <div class="error" id="errorCont"></div>
        </div>

        <!-- Proveedor (aparece solo si se selecciona) -->
        <div id="proveedorField" style="display: none;">
            <label for="id_prov">Proveedor:<span>*</span></label>
            <select id="id_prov" name="id_prov"></select>
            <div class="error" id="errorProv"></div>
        </div>

        <br>
        <button type="submit" class="submit-btn">Crear Usuario</button>      
        <div class="error" id="errorGeneral"></div>
    </form>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/nuevo_usu.js"></script>
<!-- <script src="/assets/js/usuarios.js"></script> -->