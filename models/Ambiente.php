<?php
require_once __DIR__ . '/Model.php';

class Ambiente extends Model {

    public function listarActivos() {
        $sql = "SELECT * FROM ambiente WHERE activo = 1 ORDER BY orden ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM ambiente ORDER BY orden ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM ambiente WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($datos) {
        $sql = "INSERT INTO ambiente (titulo, descripcion, imagen, orden, activo) 
                VALUES (:titulo, :descripcion, :imagen, :orden, :activo)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $datos['titulo'],
            ':descripcion' => $datos['descripcion'] ?? '',
            ':imagen' => $datos['imagen'] ?? 'default.jpg',
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1,
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE ambiente 
                SET titulo = :titulo, descripcion = :descripcion, imagen = :imagen, 
                    orden = :orden, activo = :activo 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titulo' => $datos['titulo'],
            ':descripcion' => $datos['descripcion'] ?? '',
            ':imagen' => $datos['imagen'] ?? 'default.jpg',
            ':orden' => $datos['orden'] ?? 0,
            ':activo' => $datos['activo'] ?? 1,
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM ambiente WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
