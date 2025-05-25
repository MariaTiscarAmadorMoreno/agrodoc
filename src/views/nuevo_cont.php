<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}
?>

<div class="volver">
    <a href="javascript:cargar('#portada','/views/vercontratistas.php');"><button>Volver</button></a>
</div>

<div class="container_form">
    <h2 class="form-title"> Nuevo contratista</h2>

    <form action="/controllers/ncontratista.php" method="POST">
    <div>
                    <label for="nombre">Nombre:</label>
                    <input placeholder="Nombre" required="required" name="nombre" type="text" id="nombre">
                </div>
                <div>
                    <label for="cif">CIF:</label>
                    <input placeholder="CIF" required="required" name="cif" type="text" id="cif">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input placeholder="Email" required="required" name="email" type="text" id="email">
                </div>
                <div>
                    <label for="telefono">Télefono:</label>
                    <input placeholder="Teléfono" required="required" name="telefono" type="text" id="telefono">
                </div>
                <div>
                    <label for="direccion">Dirección:</label>
                    <input placeholder="Dirección" required="required" name="direccion" type="text" id="direccion">
                </div>     

                <input type="submit" value="Insertar" class="submit-btn">
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/assets/js/nuevo_cont.js"></script>
