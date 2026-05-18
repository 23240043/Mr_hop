<?php
require_once ROOT_PATH . '/controllers/Controller.php';
require_once ROOT_PATH . '/models/Resena.php';

class ResenaController extends Controller {

    public function index() {
        $resenaModel = new Resena();
        $datos = [
            'titulo' => 'Reseñas',
            'resenas' => $resenaModel->listarAprobadas(),
            'promedio' => $resenaModel->promedioCalificacion(),
        ];
        $this->view('resenas/index', $datos);
    }

    public function nueva() {
        Auth::requireLogin();
        $resenaModel = new Resena();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'usuario_id' => Auth::user()['id'],
                'calificacion' => intval($_POST['calificacion'] ?? 0),
                'comentario' => trim($_POST['comentario'] ?? ''),
            ];

            if ($datos['calificacion'] < 1 || $datos['calificacion'] > 5) {
                Helper::setFlash('error', 'Selecciona una calificación de 1 a 5 estrellas.');
            } elseif (empty($datos['comentario'])) {
                Helper::setFlash('error', 'Escribe un comentario.');
            } else {
                if ($resenaModel->crear($datos)) {
                    Helper::setFlash('success', '¡Gracias por tu reseña!');
                    Helper::redirect('index.php?controller=resena&action=index');
                } else {
                    Helper::setFlash('error', 'No se pudo publicar la reseña.');
                }
            }
        }

        $this->view('resenas/nueva', ['titulo' => 'Escribir reseña']);
    }

    public function gestionar() {
        Auth::requireRole('administrador');
        $resenaModel = new Resena();
        $datos = [
            'titulo' => 'Gestionar reseñas',
            'resenas' => $resenaModel->listarTodas(),
        ];
        $this->view('admin/resenas_listar', $datos);
    }

    public function aprobar() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);
        $aprobada = intval($_GET['aprobada'] ?? 1);
        $resenaModel = new Resena();
        $resenaModel->aprobar($id, $aprobada);
        Helper::setFlash('success', $aprobada ? 'Reseña aprobada.' : 'Reseña ocultada.');
        Helper::redirect('index.php?controller=resena&action=gestionar');
    }

    public function eliminar() {
        Auth::requireRole('administrador');
        $id = intval($_GET['id'] ?? 0);
        $resenaModel = new Resena();
        $resenaModel->eliminar($id);
        Helper::setFlash('success', 'Reseña eliminada.');
        Helper::redirect('index.php?controller=resena&action=gestionar');
    }
}
