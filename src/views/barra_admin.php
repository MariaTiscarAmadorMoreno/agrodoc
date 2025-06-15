<?php
session_start();

?>

<nav id="nav" role="navigation">
    <div class="container_nav">
        <?php
        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verusuarios.php\');" id="soporteLink">Usuarios</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/vercontratistas.php\');" id="soporteLink">Contratistas</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verfincas.php\');" id="soporteLink">Fincas</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verproyectos.php\');" id="soporteLink">Campañas</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verproveedores.php\');" id="soporteLink">Proveedores</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/vertrabajadores.php\');" id="soporteLink">Trabajadores</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verdocumentos.php\');" id="soporteLink">Documentos</a>';
        echo '</div>';

        ?>
    </div>
</nav>
