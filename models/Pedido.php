<?php
require_once __DIR__ . '/Model.php';

class Pedido extends Model {

    public function listarTodos() {
        $sql = "SELECT p.*, u.nombre AS cliente_nombre, u.apellidos AS cliente_apellidos, 
                       m.numero AS mesa_numero 
                FROM pedidos p 
                INNER JOIN usuarios u ON p.usuario_id = u.id 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                ORDER BY p.fecha_pedido DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT p.*, m.numero AS mesa_numero 
                FROM pedidos p 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                WHERE p.usuario_id = :usuario_id 
                ORDER BY p.fecha_pedido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        return $stmt->fetchAll();
    }

    public function listarPorEstado($estados) {
        if (!is_array($estados)) $estados = [$estados];
        $placeholders = implode(',', array_fill(0, count($estados), '?'));
        $sql = "SELECT p.*, u.nombre AS cliente_nombre, u.apellidos AS cliente_apellidos, 
                       m.numero AS mesa_numero 
                FROM pedidos p 
                INNER JOIN usuarios u ON p.usuario_id = u.id 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                WHERE p.estado IN ($placeholders) 
                ORDER BY p.fecha_pedido ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($estados);
        return $stmt->fetchAll();
    }

    public function buscarPorId($id) {
        $sql = "SELECT p.*, u.nombre AS cliente_nombre, u.apellidos AS cliente_apellidos, 
                       u.telefono AS cliente_telefono, m.numero AS mesa_numero 
                FROM pedidos p 
                INNER JOIN usuarios u ON p.usuario_id = u.id 
                LEFT JOIN mesas m ON p.mesa_id = m.id 
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function obtenerDetalle($pedido_id) {
        $sql = "SELECT d.*, p.nombre AS producto_nombre, p.imagen AS producto_imagen 
                FROM detalle_pedidos d 
                INNER JOIN productos p ON d.producto_id = p.id 
                WHERE d.pedido_id = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pedido_id' => $pedido_id]);
        return $stmt->fetchAll();
    }

    /**
     * Crear pedido con sus detalles dentro de una transacción
     */
    public function crearConDetalle($datosPedido, $items) {
        try {
            $this->db->beginTransaction();

            // 1. Calcular total
            $total = 0;
            foreach ($items as $item) {
                $total += $item['precio_unitario'] * $item['cantidad'];
            }

            // 2. Insertar pedido
            $sql = "INSERT INTO pedidos (usuario_id, mesa_id, tipo, direccion_entrega, estado, total, notas) 
                    VALUES (:usuario_id, :mesa_id, :tipo, :direccion_entrega, 'pendiente', :total, :notas)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuario_id' => $datosPedido['usuario_id'],
                ':mesa_id' => $datosPedido['mesa_id'] ?? null,
                ':tipo' => $datosPedido['tipo'],
                ':direccion_entrega' => $datosPedido['direccion_entrega'] ?? null,
                ':total' => $total,
                ':notas' => $datosPedido['notas'] ?? '',
            ]);
            $pedido_id = $this->db->lastInsertId();

            // 3. Insertar detalle
            $sqlDet = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, subtotal) 
                       VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
            $stmtDet = $this->db->prepare($sqlDet);
            foreach ($items as $item) {
                $stmtDet->execute([
                    ':pedido_id' => $pedido_id,
                    ':producto_id' => $item['producto_id'],
                    ':cantidad' => $item['cantidad'],
                    ':precio_unitario' => $item['precio_unitario'],
                    ':subtotal' => $item['precio_unitario'] * $item['cantidad'],
                ]);
            }

            $this->db->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE pedidos SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':estado' => $estado]);
    }

    public function eliminar($id) {
        $sql = "DELETE FROM pedidos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function listarMesas() {
        return $this->db->query("SELECT * FROM mesas ORDER BY numero")->fetchAll();
    }

    /**
     * Estadísticas para dashboard
     */
    public function estadisticas() {
        $stats = [];
        $stats['total_pedidos'] = $this->db->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
        $stats['pendientes'] = $this->db->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'")->fetchColumn();
        $stats['en_preparacion'] = $this->db->query("SELECT COUNT(*) FROM pedidos WHERE estado IN ('en_preparacion','emplatado')")->fetchColumn();
        $stats['entregados_hoy'] = $this->db->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'entregado' AND DATE(fecha_pedido) = CURDATE()")->fetchColumn();
        $stats['ingresos_hoy'] = $this->db->query("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE estado = 'entregado' AND DATE(fecha_pedido) = CURDATE()")->fetchColumn();
        return $stats;
    }
}
