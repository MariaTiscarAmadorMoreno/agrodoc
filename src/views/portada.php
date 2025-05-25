<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /app/login");
    exit;
}

$usuario = unserialize($_SESSION['usuario']);
$tipo = $usuario['tipo'] ?? '';
$id_contratista = $usuario['id_cont'] ?? null;
$id_proveedor = $usuario['id_prov'] ?? null;
?>

<?php if ($tipo === 'admin'): ?>
    <img src="/assets/img/logotipoAgrodoc.svg" alt="portada" title="portada">

<?php elseif ($tipo === 'contratista'): ?>
    <div class="container_datos">
        <!-- CAMPOS DE CAMPAÑAS -->
        <div class="grupo_datos">
            <h2>RESUMEN DE CAMPAÑAS</h2>
            <div class="card card-green">
                <h3>Campañas activas</h3>
                <p id="activas">0</p>
            </div>

            <div class="card card-blue">
                <h3>Campañas futuras</h3>
                <p id="futuras">0</p>
            </div>

            <div class="card card-red">
                <h3>Campañas finalizadas</h3>
                <p id="finalizadas">0</p>
            </div>

            <div class="card card-orange">
                <h3>Total de campañas</h3>
                <p id="totalCampanas">0</p>
            </div>
        </div>

        <!-- CAMPOS DE FINCAS -->
        <div class="grupo_datos">
            <h2>RESUMEN DE FINCAS</h2>
            <div class="card card-purple">
                <h3>Total de fincas</h3>
                <p id="totalFincas">0</p>
            </div>

            <div class="card card-pink">
                <h3>Superficie total (ha)</h3>
                <p id="hectareasTotales">0</p>
            </div>

            <div class="card card-teal">
                <h3>Finca más grande</h3>
                <p id="fincaMayor">-</p>
            </div>

            <div class="card card-yellow">
                <h3>Cultivo más común</h3>
                <p id="cultivoComun">-</p>
            </div>
        </div>

        <!-- PROVEEDORES -->
        <div class="grupo_datos">
            <h2>RESUMEN DE PROVEEDORES</h2>
            <div class="card card-dark">
                <h3>Proveedores asociados</h3>
                <p id="totalProveedores">0</p>
            </div>

            <div class="card card-sky">
                <h3>Proveedor más frecuente</h3>
                <p id="proveedorFrecuente">-</p>
            </div>

            <div class="card card-lightblue">
                <h3>Último proveedor usado</h3>
                <p id="proveedorReciente">-</p>
            </div>
        </div>
    </div>
    <script src="/assets/js/datos_contratista.js"></script>

<?php elseif ($tipo === 'proveedor'): ?>

    <div class="container_datos">
        <!-- CAMPOS DE CAMPAÑAS -->
        <div class="grupo_datos">
            <h2>RESUMEN DE CAMPAÑAS</h2>
            <div class="card card-green">
                <h3>Campañas activas</h3>
                <p id="activas">0</p>
            </div>

            <div class="card card-blue">
                <h3>Campañas futuras</h3>
                <p id="futuras">0</p>
            </div>

            <div class="card card-red">
                <h3>Campañas finalizadas</h3>
                <p id="finalizadas">0</p>
            </div>

            <div class="card card-orange">
                <h3>Total de campañas</h3>
                <p id="totalCampanas">0</p>
            </div>
        </div>

        <!-- CAMPOS DE FINCAS -->
        <div class="grupo_datos">
            <h2>RESUMEN DE FINCAS</h2>
            <div class="card card-purple">
                <h3>Total de fincas</h3>
                <p id="totalFincas">0</p>
            </div>

            <div class="card card-pink">
                <h3>Superficie total (ha)</h3>
                <p id="hectareasTotales">0</p>
            </div>

            <div class="card card-teal">
                <h3>Finca más grande</h3>
                <p id="fincaMayor">-</p>
            </div>

            <div class="card card-yellow">
                <h3>Cultivo más común</h3>
                <p id="cultivoComun">-</p>
            </div>
        </div>

        <!-- TRABAJADORES -->
        <div class="grupo_datos">
            <h2>RESUMEN DE TRABAJADORES</h2>
            <div class="card card-dark">
                <h3>Total de trabajadores</h3>
                <p id="totalTrabajadores">0</p>
            </div>

            <div class="card card-sky">
                <h3>Trabajador con más campañas</h3>
                <p id="trabajadorFrecuente">-</p>
            </div>

            <div class="card card-lightblue">
                <h3>Trabajadores sin documentación</h3>
                <p id="trabajadoresSinDocs">0</p>
            </div>
        </div>

        <!-- CONTRATISTAS -->
        <div class="grupo_datos">
            <h2>RESUMEN DE CONTRATISTAS</h2>
            <div class="card card-yellow">
                <h3>Contratistas activos</h3>
                <p id="contratistasActivos">0</p>
            </div>

            <div class="card card-purple">
                <h3>Contratista más frecuente</h3>
                <p id="contratistaFrecuente">-</p>
            </div>

            <div class="card card-pink">
                <h3>Último contratista usado</h3>
                <p id="contratistaReciente">-</p>
            </div>
        </div>
    </div>
    <script src="/assets/js/datos_proveedor.js"></script>
<?php endif; ?>