<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Pedido.php';

class MeseroController extends Controller {

    public function dashboard() {
        Auth::requireRole(['mesero','administrador']);
        $pedidoModel = new Pedido();
        
        $datos = [
            'titulo' => 'Panel del mesero',
            
            'pedidos_listos' => $pedidoModel->listarPorEstado('listo'),
            
            'pedidos_proceso' => $pedidoModel->listarPorEstado(['pendiente','en_preparacion','emplatado']),
        ];
        $this->view('mesero/dashboard', $datos);
    }
}
