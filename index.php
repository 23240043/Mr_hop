<?php
/**
 * ==============================================
 * Punto de entrada del sistema (Front Controller)
 * ==============================================
 * Recibe todas las peticiones y enruta hacia el
 * controlador y acción correspondiente.
 *
 * URL: index.php?controller=home&action=index
 */

// 1. Cargar configuración base
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// 2. Cargar helpers
require_once __DIR__ . '/helpers/Auth.php';
require_once __DIR__ . '/helpers/Helper.php';

// 3. Determinar controlador y acción
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Sanitizar (solo letras, números y guion bajo)
$controller = preg_replace('/[^a-zA-Z0-9_]/', '', $controller);
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

// 4. Construir nombre de la clase del controlador
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

// 5. Cargar y ejecutar
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
