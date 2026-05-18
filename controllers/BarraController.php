<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Pedido.php';

class BarraController extends Controller {

    public function dashboard() {
        Auth::requireRole(['barra','administrador']);
        $pedidoModel = new Pedido();
        
        $datos = [
            'titulo' => 'Panel de barra/cocina',
            // Pedidos por preparar
            'pedidos_pendientes' => $pedidoModel->listarPorEstado('pendiente'),
            'pedidos_en_preparacion' => $pedidoModel->listarPorEstado(['en_preparacion','emplatado']),
        ];
        $this->view('barra/dashboard', $datos);
    }
}
