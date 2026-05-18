<?php
require_once __DIR__ . '/Model.php';

class Resena extends Model {

    public function listarAprobadas() {
        $sql = "SELECT r.*, u.nombre AS usuario_nombre, u.apellidos AS usuario_apellidos 
                FROM resenas r 
                INNER JOIN usuarios u ON r.usuario_id = u.id 
                WHERE r.aprobada = 1 
                ORDER BY r.fecha DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarTodas() {
        $sql = "SELECT r.*, u.nombre AS usuario_nombre, u.apellidos AS usuario_apellidos 
                FROM resenas r 
                INNER JOIN usuarios u ON r.usuario_id = u.id 
                ORDER BY r.fecha DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT * FROM resenas WHERE usuario_id = :usuario_id ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM resenas WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($datos) {
        $sql = "INSERT INTO resenas (usuario_id, calificacion, comentario) 
                VALUES (:usuario_id, :calificacion, :comentario)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':usuario_id' => $datos['usuario_id'],
            ':calificacion' => $datos['calificacion'],
            ':comentario' => $datos['comentario'],
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE resenas SET calificacion = :calificacion, comentario = :comentario WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':calificacion' => $datos['calificacion'],
            ':comentario' => $datos['comentario'],
        ]);
    }

    public function aprobar($id, $aprobada = 1) {
        $sql = "UPDATE resenas SET aprobada = :aprobada WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':aprobada' => $aprobada]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM resenas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function promedioCalificacion() {
        return $this->db->query("SELECT ROUND(AVG(calificacion),1) FROM resenas WHERE aprobada = 1")->fetchColumn() ?: 0;
    }
}
