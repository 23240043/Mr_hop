<?php
require_once __DIR__ . '/Model.php';

class Usuario extends Model {

    public function buscarPorEmail($email) {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = :email AND u.activo = 1 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function buscarPorId($id) {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function listarTodos() {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                ORDER BY u.fecha_registro DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function listarPorRol($rol_id) {
        $sql = "SELECT * FROM usuarios WHERE rol_id = :rol_id AND activo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':rol_id' => $rol_id]);
        return $stmt->fetchAll();
    }

    public function crear($datos) {
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, telefono, direccion, rol_id) 
                VALUES (:nombre, :apellidos, :email, :password, :telefono, :direccion, :rol_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre' => $datos['nombre'],
            ':apellidos' => $datos['apellidos'] ?? '',
            ':email' => $datos['email'],
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            ':telefono' => $datos['telefono'] ?? '',
            ':direccion' => $datos['direccion'] ?? '',
            ':rol_id' => $datos['rol_id'] ?? 2, // 2 = cliente por defecto
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, apellidos = :apellidos, email = :email, 
                    telefono = :telefono, direccion = :direccion, rol_id = :rol_id, activo = :activo 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos['nombre'],
            ':apellidos' => $datos['apellidos'] ?? '',
            ':email' => $datos['email'],
            ':telefono' => $datos['telefono'] ?? '',
            ':direccion' => $datos['direccion'] ?? '',
            ':rol_id' => $datos['rol_id'],
            ':activo' => $datos['activo'] ?? 1,
        ]);
    }

    public function actualizarPassword($id, $nuevaPassword) {
        $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':password' => password_hash($nuevaPassword, PASSWORD_DEFAULT),
        ]);
    }

    public function eliminar($id) {
        // Borrado lógico: marcamos como inactivo
        $sql = "UPDATE usuarios SET activo = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function emailExiste($email, $excluirId = null) {
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $params = [':email' => $email];
        if ($excluirId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excluirId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }

    public function listarRoles() {
        return $this->db->query("SELECT * FROM roles ORDER BY id")->fetchAll();
    }

    public function verificarPassword($passwordPlano, $passwordHash) {
        return password_verify($passwordPlano, $passwordHash);
    }
}
