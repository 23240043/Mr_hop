<?php
require_once __DIR__ . '/Model.php';

class Producto extends Model {

    public function listarTodos() {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, c.tipo AS categoria_tipo 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY c.tipo, p.nombre";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarDisponibles() {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, c.tipo AS categoria_tipo 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.disponible = 1 
                ORDER BY c.tipo, p.nombre";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarPorTipo($tipo) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, c.tipo AS categoria_tipo 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                WHERE c.tipo = :tipo AND p.disponible = 1 
                ORDER BY p.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tipo' => $tipo]);
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre, c.tipo AS categoria_tipo 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function crear($datos) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, imagen, disponible) 
                VALUES (:nombre, :descripcion, :precio, :categoria_id, :imagen, :disponible)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? '',
            ':precio' => $datos['precio'],
            ':categoria_id' => $datos['categoria_id'],
            ':imagen' => $datos['imagen'] ?? 'default.jpg',
            ':disponible' => $datos['disponible'] ?? 1,
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE productos 
                SET nombre = :nombre, descripcion = :descripcion, precio = :precio, 
                    categoria_id = :categoria_id, imagen = :imagen, disponible = :disponible 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':descripcion' => $datos['descripcion'] ?? '',
            ':precio' => $datos['precio'],
            ':categoria_id' => $datos['categoria_id'],
            ':imagen' => $datos['imagen'] ?? 'default.jpg',
            ':disponible' => $datos['disponible'] ?? 1,
        ]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function listarCategorias() {
        return $this->db->query("SELECT * FROM categorias ORDER BY tipo, nombre")->fetchAll();
    }
}
