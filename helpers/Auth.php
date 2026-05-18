<?php
/**
 * Helper de autenticación
 * Maneja sesiones y verificación de roles
 */
class Auth {
    
    public static function login($usuario) {
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_nombre'] = $usuario['nombre'] . ' ' . ($usuario['apellidos'] ?? '');
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_rol_id'] = $usuario['rol_id'];
        $_SESSION['user_rol'] = $usuario['rol_nombre'] ?? '';
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (!self::isLoggedIn()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'nombre' => $_SESSION['user_nombre'],
            'email' => $_SESSION['user_email'],
            'rol_id' => $_SESSION['user_rol_id'],
            'rol' => $_SESSION['user_rol'],
        ];
    }

    public static function hasRole($rol) {
        if (!self::isLoggedIn()) return false;
        if (is_array($rol)) {
            return in_array($_SESSION['user_rol'], $rol);
        }
        return $_SESSION['user_rol'] === $rol;
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'index.php?controller=auth&action=login');
            exit;
        }
    }

    public static function requireRole($rol) {
        self::requireLogin();
        if (!self::hasRole($rol)) {
            header('Location: ' . BASE_URL . 'index.php?controller=home&action=index&error=acceso_denegado');
            exit;
        }
    }

    /**
     * Generar token CSRF para formularios
     */
    public static function csrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
