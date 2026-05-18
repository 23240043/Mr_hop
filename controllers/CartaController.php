<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Producto.php';

class CartaController extends Controller {

    public function index() {
        $productoModel = new Producto();
        $datos = [
            'titulo' => 'Carta',
            'bebidas' => $productoModel->listarPorTipo('bebida'),
            'comidas' => $productoModel->listarPorTipo('comida'),
            'combos' => $productoModel->listarPorTipo('combo'),
        ];
        $this->view('carta/index', $datos);
    }

    // ============ CRUD ADMIN ============

    public function gestionar() {
        Auth::requireRole('administrador');
        $productoModel = new Producto();
        $datos = [
            'titulo' => 'Gestionar Carta',
            'productos' => $productoModel->listarTodos(),
        ];
        $this->view('admin/productos_listar', $datos);
    }

    public function crear() {
        Auth::requireRole('administrador');
        $productoModel = new Producto();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'precio' => floatval($_POST['precio'] ?? 0),
                'categoria_id' => intval($_POST['categoria_id'] ?? 0),
                'imagen' => 'default.jpg',
                'disponible' => isset($_POST['disponible']) ? 1 : 0,
            ];

            if (empty($datos['nombre']) || $datos['precio'] <= 0 || $datos['categoria_id'] === 0) {
                Helper::setFlash('error', 'Completa todos los campos obligatorios.');
            } else {
                if ($productoModel->crear($datos)) {
                    Helper::setFlash('success', 'Producto creado correctamente.');
                    Helper::redirect('index.php?controller=carta&action=gestionar');
                } else {
                    Helper::setFlash('error', 'No se pudo crear el producto.');
                }
            }
        }

        $datos = [
            'titulo' => 'Nuevo producto',
            'categorias' => $productoModel->listarCategorias(),
            'producto' => null,
        ];
        $this->view('admin/productos_form', $datos);
    }

    public function editar() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);
        $productoModel = new Producto();
        $producto = $productoModel->buscarPorId($id);

        if (!$producto) {
            Helper::setFlash('error', 'Producto no encontrado.');
            Helper::redirect('index.php?controller=carta&action=gestionar');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'precio' => floatval($_POST['precio'] ?? 0),
                'categoria_id' => intval($_POST['categoria_id'] ?? 0),
                'imagen' => $producto['imagen'],
                'disponible' => isset($_POST['disponible']) ? 1 : 0,
            ];

            if ($productoModel->actualizar($id, $datos)) {
                Helper::setFlash('success', 'Producto actualizado.');
                Helper::redirect('index.php?controller=carta&action=gestionar');
            } else {
                Helper::setFlash('error', 'No se pudo actualizar el producto.');
            }
        }

        $datos = [
            'titulo' => 'Editar producto',
            'categorias' => $productoModel->listarCategorias(),
            'producto' => $producto,
        ];
        $this->view('admin/productos_form', $datos);
    }

    public function eliminar() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);
        $productoModel = new Producto();

        if ($productoModel->eliminar($id)) {
            Helper::setFlash('success', 'Producto eliminado.');
        } else {
            Helper::setFlash('error', 'No se pudo eliminar (puede tener pedidos asociados).');
        }
        Helper::redirect('index.php?controller=carta&action=gestionar');
    }
}
