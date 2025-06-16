<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /views/login.php");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';
$idCont = $usuario['id_cont'] ?? null;
?>

<div class="volver">
    <?php if ($tipo === 'admin'): ?>
        <a href="/views/app_admin.php?opcion=3"><button>Atrás</button></a>
    <?php else: ?>
        <a href="/views/app_contratista.php?opcion=1"><button>Atrás</button></a>
    <?php endif; ?>
</div>

<div class="container_form">
    <h2 class="form-title"> Nueva finca </h2>

    <form id="formNuevaFinca" method="POST" class="form">

        <fieldset>
            <legend>Localización<span>*</span></legend>

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

        <label for="cultivo">Cultivo:<span>*</span></label>
        <input placeholder="Cultivo" name="cultivo" type="text" id="cultivo">
        <div class="error" id="errorCultivo"></div>

        <label for="hectarea">Tamaño en hectáreas:<span>*</span></label>
        <input placeholder="Héctareas" name="hectarea" type="text" id="hectarea">
        <div class="error" id="errorHectarea"></div>

        <!-- Desplegable de contratistas -->
        <?php if ($tipo === 'admin'): ?>
            <!-- Mostrar selector de contratistas solo a administradores -->
            <div id="contratistaField">
                <label for="id_cont">Selecciona un contratista:<span>*</span></label>
                <select name="id_cont" id="id_cont">
                    <option value="">-- Seleccionar Contratista --</option>
                </select>
                <div class="error" id="errorContratista"></div>
            </div>

        <?php else: ?>
            <!-- Para contratistas insertamos automáticamente su id -->
            <input type="hidden" name="id_cont" value="<?= $idCont ?>">
        <?php endif; ?>

        <button type="submit" class="submit-btn">Crear finca</button>
        <div class="error" id="errorGeneral"></div>
    </form>
</div>

<script src="/assets/js/nueva_finca.js"></script>