<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Ambiente.php';
require_once ROOT_PATH . '/models/Resena.php';

class HomeController extends Controller {

    public function index() {
        $resenaModel = new Resena();
        $ambienteModel = new Ambiente();
        
        $datos = [
            'titulo' => 'Inicio',
            'resenas_destacadas' => array_slice($resenaModel->listarAprobadas(), 0, 3),
            'promedio' => $resenaModel->promedioCalificacion(),
            'ambientes' => array_slice($ambienteModel->listarActivos(), 0, 3),
        ];
        $this->view('home/index', $datos);
    }

    public function historia() {
        $datos = ['titulo' => 'Nuestra Historia'];
        $this->view('home/historia', $datos);
    }

    public function ambiente() {
        $ambienteModel = new Ambiente();
        $datos = [
            'titulo' => 'Ambiente',
            'ambientes' => $ambienteModel->listarActivos(),
        ];
        $this->view('home/ambiente', $datos);
    }

    public function contacto() {
        $datos = ['titulo' => 'Contacto'];
        $this->view('home/contacto', $datos);
    }
}
