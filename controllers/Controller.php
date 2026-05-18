<?php
/**
 * Controlador base
 * Provee el método view() para cargar vistas con layout
 */
class Controller {
    
    /**
     * Renderizar una vista dentro del layout principal
     * @param string $vista Ruta relativa dentro de /views (ej. 'home/index')
     * @param array $datos Variables a pasar a la vista
     * @param string $layout Layout a usar (por defecto 'main')
     */
    protected function view($vista, $datos = [], $layout = 'main') {
        extract($datos);
        
        // Capturar contenido de la vista
        ob_start();
        $vistaPath = ROOT_PATH . '/views/' . $vista . '.php';
        if (file_exists($vistaPath)) {
            include $vistaPath;
        } else {
            echo "<h2>Vista no encontrada:</h2><code>$vistaPath</code>";
        }
        $contenido = ob_get_clean();
        
        // Cargar layout que envuelve a $contenido
        $layoutPath = ROOT_PATH . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $contenido;
        }
    }

    /**
     * Renderizar una vista sin layout (útil para AJAX, partials)
     */
    protected function partial($vista, $datos = []) {
        extract($datos);
        $vistaPath = ROOT_PATH . '/views/' . $vista . '.php';
        if (file_exists($vistaPath)) {
            include $vistaPath;
        }
    }
}
