<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Pedido.php';
require_once ROOT_PATH . '/models/Producto.php';

class PedidoController extends Controller {


    public function nuevo() {
        Auth::requireLogin();
        $productoModel = new Producto();
        $pedidoModel = new Pedido();

        $datos = [
            'titulo' => 'Nuevo pedido',
            'bebidas' => $productoModel->listarPorTipo('bebida'),
            'comidas' => $productoModel->listarPorTipo('comida'),
            'combos' => $productoModel->listarPorTipo('combo'),
            'mesas' => $pedidoModel->listarMesas(),
        ];
        $this->view('pedidos/nuevo', $datos);
    }

    public function guardar() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helper::redirect('index.php?controller=pedido&action=nuevo');
        }

        $tipo = $_POST['tipo'] ?? 'mesa';
        $mesa_id = !empty($_POST['mesa_id']) ? intval($_POST['mesa_id']) : null;
        $direccion = trim($_POST['direccion_entrega'] ?? '');
        $notas = trim($_POST['notas'] ?? '');
        $productos = $_POST['producto_id'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];

        if ($tipo === 'mesa' && !$mesa_id) {
            Helper::setFlash('error', 'Selecciona una mesa.');
            Helper::redirect('index.php?controller=pedido&action=nuevo');
        }
        if ($tipo === 'domicilio' && empty($direccion)) {
            Helper::setFlash('error', 'Ingresa la dirección de entrega.');
            Helper::redirect('index.php?controller=pedido&action=nuevo');
        }

        $productoModel = new Producto();
        $items = [];
        foreach ($productos as $i => $prod_id) {
            $cant = intval($cantidades[$i] ?? 0);
            if ($cant > 0) {
                $prod = $productoModel->buscarPorId($prod_id);
                if ($prod && $prod['disponible']) {
                    $items[] = [
                        'producto_id' => $prod['id'],
                        'cantidad' => $cant,
                        'precio_unitario' => $prod['precio'],
                    ];
                }
            }
        }

        if (empty($items)) {
            Helper::setFlash('error', 'Selecciona al menos un producto.');
            Helper::redirect('index.php?controller=pedido&action=nuevo');
        }

        $pedidoModel = new Pedido();
        try {
            $pedido_id = $pedidoModel->crearConDetalle([
                'usuario_id' => Auth::user()['id'],
                'mesa_id' => $tipo === 'mesa' ? $mesa_id : null,
                'tipo' => $tipo,
                'direccion_entrega' => $tipo === 'domicilio' ? $direccion : null,
                'notas' => $notas,
            ], $items);

            Helper::setFlash('success', 'Pedido #' . $pedido_id . ' creado correctamente.');
            Helper::redirect('index.php?controller=pedido&action=ver&id=' . $pedido_id);
        } catch (Exception $e) {
            Helper::setFlash('error', 'Error al crear pedido: ' . $e->getMessage());
            Helper::redirect('index.php?controller=pedido&action=nuevo');
        }
    }

    public function ver() {
        Auth::requireLogin();
        $id = intval($_GET['id'] ?? 0);
        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->buscarPorId($id);

        if (!$pedido) {
            Helper::setFlash('error', 'Pedido no encontrado.');
            Helper::redirect('');
        }

        $usuario = Auth::user();
        if ($pedido['usuario_id'] != $usuario['id'] && !Auth::hasRole(['administrador','mesero','barra'])) {
            Helper::setFlash('error', 'No tienes permiso para ver este pedido.');
            Helper::redirect('');
        }

        $datos = [
            'titulo' => 'Pedido #' . $id,
            'pedido' => $pedido,
            'detalle' => $pedidoModel->obtenerDetalle($id),
        ];
        $this->view('pedidos/ver', $datos);
    }

    public function misPedidos() {
        Auth::requireLogin();
        $pedidoModel = new Pedido();
        $datos = [
            'titulo' => 'Mis pedidos',
            'pedidos' => $pedidoModel->listarPorUsuario(Auth::user()['id']),
        ];
        $this->view('pedidos/mis_pedidos', $datos);
    }

    public function listar() {
        Auth::requireRole(['administrador','mesero']);
        $pedidoModel = new Pedido();
        $datos = [
            'titulo' => 'Todos los pedidos',
            'pedidos' => $pedidoModel->listarTodos(),
        ];
        $this->view('pedidos/listar', $datos);
    }

    public function actualizarEstado() {
        Auth::requireRole(['administrador','mesero','barra']);
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        $estado = $_POST['estado'] ?? $_GET['estado'] ?? '';
        $estadosValidos = ['pendiente','en_preparacion','emplatado','listo','entregado','cancelado'];

        if (!in_array($estado, $estadosValidos)) {
            Helper::setFlash('error', 'Estado inválido.');
            Helper::redirect('index.php?controller=pedido&action=listar');
        }

        $pedidoModel = new Pedido();
        if ($pedidoModel->actualizarEstado($id, $estado)) {
            Helper::setFlash('success', 'Estado actualizado a: ' . Helper::estadoPedido($estado)['texto']);
        } else {
            Helper::setFlash('error', 'No se pudo actualizar el estado.');
        }

        $rol = Auth::user()['rol'];
        if ($rol === 'mesero') Helper::redirect('index.php?controller=mesero&action=dashboard');
        elseif ($rol === 'barra') Helper::redirect('index.php?controller=barra&action=dashboard');
        else Helper::redirect('index.php?controller=pedido&action=listar');
    }

    public function eliminar() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);
        $pedidoModel = new Pedido();
        if ($pedidoModel->eliminar($id)) {
            Helper::setFlash('success', 'Pedido eliminado.');
        } else {
            Helper::setFlash('error', 'No se pudo eliminar el pedido.');
        }
        Helper::redirect('index.php?controller=pedido&action=listar');
    }
}
