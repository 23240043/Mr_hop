<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__ . '/helpers/Auth.php';
require_once __DIR__ . '/helpers/Helper.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$controller = preg_replace('/[^a-zA-Z0-9_]/', '', $controller);
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $obj = new $controllerName();
        if (method_exists($obj, $action)) {
            $obj->$action();
        } else {
            http_response_code(404);
            echo "<h1>404</h1><p>Acción no encontrada: <code>$action</code> en <code>$controllerName</code></p>";
            echo '<p><a href="' . BASE_URL . '">Volver al inicio</a></p>';
        }
    } else {
        http_response_code(500);
        echo "<h1>500</h1><p>Clase no encontrada: <code>$controllerName</code></p>";
    }
} else {
    http_response_code(404);
    echo "<h1>404</h1><p>Controlador no encontrado: <code>$controller</code></p>";
    echo '<p><a href="' . BASE_URL . '">Volver al inicio</a></p>';
}
