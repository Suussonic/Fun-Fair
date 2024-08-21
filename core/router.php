<?php
require 'config/routes.php';

$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$filePath = __DIR__ . '/../public' . $uriPath;

if (file_exists($filePath) && is_file($filePath)) {
    return false;
}

function urlToController($uriPath, $routes)
{
    if (array_key_exists($uriPath, $routes)) :
        require $routes[$uriPath];
    else:
        abort();
    endif;
}

function abort($code = 404)
{
    http_response_code($code);
    require __DIR__ . "/views/$code.php";
    exit();
}

urlToController($uriPath, $routes);
