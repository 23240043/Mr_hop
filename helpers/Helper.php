<?php
/**
 * Helpers generales: flash messages, escape de HTML, formato, etc.
 */
class Helper {
    
    /**
     * Setear mensaje flash que persiste solo en la siguiente petición
     */
    public static function setFlash($tipo, $mensaje) {
        $_SESSION['flash'][$tipo] = $mensaje;
    }

    /**
     * Obtener y eliminar mensaje flash
     */
    public static function getFlash($tipo) {
        if (isset($_SESSION['flash'][$tipo])) {
            $mensaje = $_SESSION['flash'][$tipo];
            unset($_SESSION['flash'][$tipo]);
            return $mensaje;
        }
        return null;
    }

    public static function hasFlash() {
        return !empty($_SESSION['flash']);
    }

    /**
     * Escape de HTML para prevenir XSS
     */
    public static function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Formatear precio en pesos mexicanos
     */
    public static function precio($monto) {
        return '$' . number_format((float)$monto, 2, '.', ',');
    }

    /**
     * Formatear fecha legible
     */
    public static function fecha($fecha, $formato = 'd/m/Y H:i') {
        if (!$fecha) return '';
        return date($formato, strtotime($fecha));
    }

    /**
     * Redireccionar a una URL relativa al BASE_URL
     */
    public static function redirect($url = '') {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit;
    }

    /**
     * Generar URL de un controlador y acción
     */
    public static function url($controller, $action = 'index', $params = []) {
        $url = BASE_URL . 'index.php?controller=' . $controller . '&action=' . $action;
        foreach ($params as $k => $v) {
            $url .= '&' . $k . '=' . urlencode($v);
        }
        return $url;
    }

    /**
     * Traducción amigable del estado de un pedido
     */
    public static function estadoPedido($estado) {
        $estados = [
            'pendiente' => ['texto' => 'Pendiente', 'color' => '#f59e0b', 'progreso' => 10],
            'en_preparacion' => ['texto' => 'En preparación', 'color' => '#3b82f6', 'progreso' => 40],
            'emplatado' => ['texto' => 'Emplatado', 'color' => '#8b5cf6', 'progreso' => 70],
            'listo' => ['texto' => 'Listo para servir', 'color' => '#10b981', 'progreso' => 90],
            'entregado' => ['texto' => 'Entregado', 'color' => '#059669', 'progreso' => 100],
            'cancelado' => ['texto' => 'Cancelado', 'color' => '#dc2626', 'progreso' => 0],
        ];
        return $estados[$estado] ?? ['texto' => $estado, 'color' => '#6b7280', 'progreso' => 0];
    }
}
