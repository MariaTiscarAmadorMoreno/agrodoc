<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['usuario'])) {
  header("Location: /views/login.php");
  exit;
}

$usuario = unserialize($_SESSION['usuario']);
$nombre = $usuario['nombre'] ?? 'Proveedor';



if ($usuario['tipo'] !== 'proveedor') {
  echo "Acceso no autorizado.";
  exit;
}

$id_proveedor = $usuario['id_prov'];

$redir = "cargar('#portada','/views/portada.php');";

if (isset($_GET['opcion'])) {
  switch ($_GET['opcion']) {
    case 1:
      $redir = "cargar('#portada','/views/vertrabajadores.php?id=$id_proveedor');";
      break;
    case 2:
      $redir = "cargar('#portada','/views/verproyectos.php?id=$id_proveedor');";
      break;
    case 3:
      $redir = "cargar('#portada','/views/vertrabajadores_proyecto.php');";
      break;
    default:
      $redir = "cargar('#portada','/views/portada.php');";
      break;
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="plantilla HTML" />
  <title>Aplicación Agrodoc</title>

  <link rel="icon" type="image/png" sizes="128x128" href="/assets/img/favicon.png">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- <link rel="stylesheet" type="text/css" href="/assets/css/estilos_app.css"> -->
  <link rel="stylesheet" type="text/css" href="/assets/css/estilos_app_usuarios.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/privacidad.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/formulario.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/faq.css">

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
  <!-- script para chat en vivo -->
  <script src="//code.tidio.co/qoe3mhwmfpdrgnjupiwlei0hkc9cy2ut.js" async></script>

</head>

<body>
  <div id="userMeta" data-id-prov="<?= $usuario['id_prov'] ?>"></div>
  <div id="principal">
    <!--Cabecera-->
    <header id="principal_header" role="banner">
      <div class="container_logotipo">
        <a href="javascript:cargar('#portada','/views/app_proveedor.php');" name="logotipo">
          <img src="/assets/img/logotipoAgrodoc.svg" alt="Logotipo Agrodoc" class="logotipo">
        </a>
      </div>

      <div class="container_menu_nombre">
        <!-- Menú de navegación -->
        <div id="menuHamburguesa" aria-label="Abrir menú">&#9776</div>
        <div class="usuario">
          <span id="nom"><?php echo $nombre; ?></span>
          <div class="loging">
            <a href="/app/logout">
              <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
              <p>Salir</p>
            </a>
          </div>
        </div>
      </div>
    </header>
    <div id="barra"></div>
    <div id="portada"></div>
  </div>

  <footer id="footer" role="contentinfo">
    <div class="footer-links">
      <a href="javascript:cargar('#portada','/views/contacto.php');" aria-label="Quiénes somos">Quiénes somos</a>
      <a href="javascript:cargar('#portada','/views/faq.php');" aria-label="FAQ">FAQ</a>
      <a href="javascript:cargar('#portada','/views/contacto.php');" aria-label="Contacto">Contacto</a>
      <a href="javascript:cargar('#portada','/views/politica_privacidad.php');" aria-label="Política de privacidad">Política de privacidad</a>
      <a href="mailto:agrodoc@agrodoc.com" aria-label="Enviar un correo a AgroDoc">Email: agrodoc@agrodoc.com</a>
    </div>
    <div class="contenedor_derecho_autor">
      © 2025 AGRODOC GLOBAL, S.A. &#45; Todos los derechos reservados.
    </div>
    <div class="container_rrss">
      <a href="https://www.instagram.com/">
        <img src="/assets/img/instagram.png" alt="Instagram" title="Instagram" class="rrss">
      </a>
      <a href="https://www.facebook.com/">
        <img src="/assets/img/facebook.png" alt="Facebook" title="Facebook" class="rrss">
      </a>
      <a href="https://www.twitter.com/">
        <img src="/assets/img/gorjeo.png" alt="Twitter" title="Twitter" class="rrss">
      </a>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/assets/js/cargar.js"></script>
  <script src="/assets/js/contacto.js"></script>
  <script src="/assets/js/preguntas.js"></script>
  <script src="/assets/js/menu.js"></script>
  <script src="/assets/js/menu2.js"></script>

  <script>
    cargar('#barra', '/views/barra_proveedor.php');
    <?php echo $redir; ?>
  </script>
</body>

</html>