<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Usuario.php';

class AuthController extends Controller {

    public function login() {
        if (Auth::isLoggedIn()) {
            Helper::redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                Helper::setFlash('error', 'Por favor ingresa correo y contraseña.');
                $this->view('auth/login', ['titulo' => 'Iniciar sesión']);
                return;
            }

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->buscarPorEmail($email);

            if ($usuario && $usuarioModel->verificarPassword($password, $usuario['password'])) {
                Auth::login($usuario);
                Helper::setFlash('success', '¡Bienvenido, ' . $usuario['nombre'] . '!');
                
                // Redirección según rol
                switch ($usuario['rol_nombre']) {
                    case 'administrador':
                        Helper::redirect('index.php?controller=admin&action=dashboard');
                        break;
                    case 'mesero':
                        Helper::redirect('index.php?controller=mesero&action=dashboard');
                        break;
                    case 'barra':
                        Helper::redirect('index.php?controller=barra&action=dashboard');
                        break;
                    default:
                        Helper::redirect('');
                }
            } else {
                Helper::setFlash('error', 'Correo o contraseña incorrectos.');
            }
        }

        $this->view('auth/login', ['titulo' => 'Iniciar sesión']);
    }

    public function registro() {
        if (Auth::isLoggedIn()) {
            Helper::redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'rol_id' => 2, // Cliente por defecto
            ];
            $confirmar = $_POST['confirmar_password'] ?? '';

            // Validaciones básicas
            $errores = [];
            if (empty($datos['nombre'])) $errores[] = 'El nombre es obligatorio.';
            if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'Ingresa un correo válido.';
            }
            if (strlen($datos['password']) < 5) $errores[] = 'La contraseña debe tener al menos 5 caracteres.';
            if ($datos['password'] !== $confirmar) $errores[] = 'Las contraseñas no coinciden.';

            $usuarioModel = new Usuario();
            if (empty($errores) && $usuarioModel->emailExiste($datos['email'])) {
                $errores[] = 'Este correo ya está registrado.';
            }

            if (empty($errores)) {
                if ($usuarioModel->crear($datos)) {
                    Helper::setFlash('success', 'Cuenta creada con éxito. Ya puedes iniciar sesión.');
                    Helper::redirect('index.php?controller=auth&action=login');
                } else {
                    Helper::setFlash('error', 'No se pudo crear la cuenta. Intenta de nuevo.');
                }
            } else {
                Helper::setFlash('error', implode(' ', $errores));
            }
        }

        $this->view('auth/registro', ['titulo' => 'Crear cuenta']);
    }

    public function logout() {
        Auth::logout();
        Helper::setFlash('success', 'Has cerrado sesión correctamente.');
        Helper::redirect('index.php?controller=auth&action=login');
    }

    /**
     * Placeholder para recuperación de contraseña
     * La lógica completa (envío de email + tokens) se implementará después
     */
    public function recuperar() {
        $this->view('auth/recuperar', ['titulo' => 'Recuperar contraseña']);
    }
}
