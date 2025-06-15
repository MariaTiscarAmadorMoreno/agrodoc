<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$datos = unserialize($_SESSION['usuario']);
$nombre = $datos['nombre'] ?? 'Proveedor';
$id_proveedor = $datos['id_prov'];
?>
<nav id="nav" role="navigation">
    <div class="container_nav">
        <?php
        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/vertrabajadores.php\');" id="soporteLink">Trabajadores</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verproyectos.php\');" id="soporteLink">Campañas</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/vercontratistas.php\');" id="soporteLink">Contratistas</a>';
        echo '</div>';

        echo '<div class="container-selector">';
        echo '<a href="javascript:cargar(\'#portada\',\'/views/verdocumentos.php\');" id="soporteLink">Documentación</a>';
        echo '</div>';
        ?>
    </div>
</nav>
<aside id="aside" role="complementary">
    <div class="sidebar-header">
        <h3 class="sidebar-title">Menú</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="javascript:cargar('#portada','/views/vertrabajadores.php?id=<?= $id_proveedor ?>');"><i class="fa-regular fa-user"></i><span class="menu-text"> Trabajadores</span></a></li>
        <li><a href="javascript:cargar('#portada','/views/verproyectos.php?id=<?= $id_proveedor ?>');"><i class="fa-solid fa-tractor"></i><span class="menu-text"> Campañas</span></a></li>
        <li><a href="javascript:cargar('#portada','/views/vercontratistas.php');"><i class="fa-regular fa-address-book"></i><span class="menu-text"> Contratistas</span></a></li>
        <li><a href="javascript:cargar('#portada','/views/verdocumentos.php');"><i class="fa-regular fa-file-lines"></i><span class="menu-text"> Documentación</span></a></li>
    </ul>
    <div class="sidebar-footer">
        <span class="usuario-nombre menu-text"><?= $nombre ?></span>
        <a class="logout-btn" href="/app/logout">
            <i class="fa-solid fa-right-from-bracket"></i> <span class="menu-text">Salir</span>
        </a>
    </div>
</aside>
<script src="/assets/js/trabajadores.js"></script>
<script src="/assets/js/modificar_trab.js"></script>
