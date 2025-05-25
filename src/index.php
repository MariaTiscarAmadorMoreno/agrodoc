<?php

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);


switch ($path) {
    case '/app':
    case '/app/':
        include __DIR__ . '/routes/dashboard.php';
        break;

    case '/app/login':
        include __DIR__ . '/views/login.php';
        break;

    case '/app/logout':
        include __DIR__ . '/views/logout.php';
        break;

    case '/app/index.php':
        include __DIR__ . '/routes/dashboard.php';
        break;
  
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
