<?php
/**
 * Modelo base - Todos los modelos heredan de este
 * Proporciona acceso a la conexión PDO
 */
class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}
