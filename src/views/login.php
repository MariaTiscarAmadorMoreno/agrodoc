<?php
session_start();
include_once(__DIR__ . '/../controllers/AuthController.php');


if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['intentos'] >= 3) {
        echo "<script>
            alert('Has alcanzado el máximo de 3 intentos. Por favor, contacta con la plataforma en soporte@agrodoc.com.');
        </script>";        
    } else {
        $usuario = $_POST['usu'];
        $clave = $_POST['pas'];

        $auth = new AuthController();
        $datosdeusuario = $auth->login($usuario, $clave);

        if (!empty($datosdeusuario)) {
            $_SESSION['intentos'] = 0;
            $_SESSION['usuario'] = serialize($datosdeusuario);
            session_write_close();
            header("Location: /app/");
            exit;
        } else {
            $_SESSION['intentos']++;
            echo "<script>
                alert('Usuario o contraseña incorrectos. Intento {$_SESSION['intentos']} de 3.');
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/form_login.css" media="screen" />
    <title>Formulario de Acceso</title>
</head>

<body>
    <div class="login-container">
        <form action="/app/login" method="POST" aria-label="Formulario de acceso">
            <img src="/assets/img/logotipoAgrodoc.svg" class="logo" alt="Logotipo Agrodoc" />
            <h1>Área personal</h1>
            <div class="label-input-container">
                <label for="usu">Usuario:</label>
                <input type="text" name="usu" id="usu" placeholder="Usuario" aria-label="Nombre de usuario" />
            </div>

            <div class="label-input-container">
                <label for="pas">Contraseña:</label>
                <input type="password" name="pas" id="pas" placeholder="Clave" aria-label="Contraseña" />
                <span id="togglePassword" class="toggle-password" aria-label="Mostrar u ocultar contraseña"><i class="fa-regular fa-eye"></i></span>
            </div>
            <input type="submit" name="enviar" value="Entrar" class="btn_entrar" aria-label="Botón de entrar" />
            <div class="container-contacto">
                <a href="/paginas/contacto.html" id="link_olvido_pas">¿Olvidaste tu contraseña?</a>
                <p>
                    ¿Tienes problemas para acceder? Escribe a <a href="mailto:soporte@agrodoc.com">soporte@agrodoc.com</a>
                </p>
            </div>
        </form>
    </div>
    <script src="/assets/js/toggle_pas.js"></script>
    <script src="/assets/js/validacion_login.js"></script>
</body>

</html>