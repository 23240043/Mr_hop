<?php


class Controller {
    
    protected function view($vista, $datos = [], $layout = 'main') {
        extract($datos);
        
        ob_start();
        $vistaPath = ROOT_PATH . '/views/' . $vista . '.php';
        if (file_exists($vistaPath)) {
            include $vistaPath;
        } else {
            echo "<h2>Vista no encontrada:</h2><code>$vistaPath</code>";
        }
        $contenido = ob_get_clean();
        
        $layoutPath = ROOT_PATH . '/views/layouts/' . $layout . '.php';
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $contenido;
        }
    }

    protected function partial($vista, $datos = []) {
        extract($datos);
        $vistaPath = ROOT_PATH . '/views/' . $vista . '.php';
        if (file_exists($vistaPath)) {
            include $vistaPath;
        }
    }
}
