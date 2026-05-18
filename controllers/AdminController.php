<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Usuario.php';
require_once ROOT_PATH . '/models/Pedido.php';
require_once ROOT_PATH . '/models/Producto.php';
require_once ROOT_PATH . '/models/Resena.php';

class AdminController extends Controller {

    public function dashboard() {
        Auth::requireRole('administrador');
        $pedidoModel = new Pedido();
        $usuarioModel = new Usuario();
        $resenaModel = new Resena();

        $datos = [
            'titulo' => 'Panel de administración',
            'stats' => $pedidoModel->estadisticas(),
            'total_usuarios' => count($usuarioModel->listarTodos()),
            'total_resenas' => count($resenaModel->listarTodas()),
            'pedidos_recientes' => array_slice($pedidoModel->listarTodos(), 0, 5),
        ];
        $this->view('admin/dashboard', $datos);
    }

    // ============ CRUD USUARIOS ============

    public function usuarios() {
        Auth::requireRole('administrador');
        $usuarioModel = new Usuario();
        $datos = [
            'titulo' => 'Gestionar usuarios',
            'usuarios' => $usuarioModel->listarTodos(),
        ];
        $this->view('admin/usuarios_listar', $datos);
    }

    public function crearUsuario() {
        Auth::requireRole('administrador');
        $usuarioModel = new Usuario();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'rol_id' => intval($_POST['rol_id'] ?? 2),
            ];

            $errores = [];
            if (empty($datos['nombre'])) $errores[] = 'Nombre obligatorio.';
            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Correo inválido.';
            if (strlen($datos['password']) < 5) $errores[] = 'Contraseña muy corta.';
            if (empty($errores) && $usuarioModel->emailExiste($datos['email'])) {
                $errores[] = 'El correo ya existe.';
            }

            if (empty($errores)) {
                if ($usuarioModel->crear($datos)) {
                    Helper::setFlash('success', 'Usuario creado correctamente.');
                    Helper::redirect('index.php?controller=admin&action=usuarios');
                } else {
                    Helper::setFlash('error', 'No se pudo crear el usuario.');
                }
            } else {
                Helper::setFlash('error', implode(' ', $errores));
            }
        }

        $datos = [
            'titulo' => 'Nuevo usuario',
            'roles' => $usuarioModel->listarRoles(),
            'usuario' => null,
        ];
        $this->view('admin/usuarios_form', $datos);
    }

    public function editarUsuario() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorId($id);

        if (!$usuario) {
            Helper::setFlash('error', 'Usuario no encontrado.');
            Helper::redirect('index.php?controller=admin&action=usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'rol_id' => intval($_POST['rol_id'] ?? 2),
                'activo' => isset($_POST['activo']) ? 1 : 0,
            ];

            if ($usuarioModel->emailExiste($datos['email'], $id)) {
                Helper::setFlash('error', 'El correo ya está en uso por otro usuario.');
            } else {
                if ($usuarioModel->actualizar($id, $datos)) {
                    // Si llega contraseña nueva, actualizar
                    if (!empty($_POST['password'])) {
                        $usuarioModel->actualizarPassword($id, $_POST['password']);
                    }
                    Helper::setFlash('success', 'Usuario actualizado.');
                    Helper::redirect('index.php?controller=admin&action=usuarios');
                } else {
                    Helper::setFlash('error', 'No se pudo actualizar.');
                }
            }
        }

        $datos = [
            'titulo' => 'Editar usuario',
            'roles' => $usuarioModel->listarRoles(),
            'usuario' => $usuario,
        ];
        $this->view('admin/usuarios_form', $datos);
    }

    public function eliminarUsuario() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);

        if ($id == Auth::user()['id']) {
            Helper::setFlash('error', 'No puedes eliminar tu propia cuenta.');
            Helper::redirect('index.php?controller=admin&action=usuarios');
        }

        $usuarioModel = new Usuario();
        if ($usuarioModel->eliminar($id)) {
            Helper::setFlash('success', 'Usuario desactivado.');
        } else {
            Helper::setFlash('error', 'No se pudo desactivar.');
        }
        Helper::redirect('index.php?controller=admin&action=usuarios');
    }
}
