<?php
/**
 * Script para generar los hashes de las contraseñas de los usuarios de prueba.
 * Ejecutar UNA VEZ después de importar bar_restaurante.sql
 * 
 * USO: Abre en navegador -> http://localhost/mr_hop/database/seed_passwords.php
 *      O por consola: php database/seed_passwords.php
 */

require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance()->getConnection();

// Contraseña común para todos los usuarios de prueba
$password = '12345';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $sql = "UPDATE usuarios SET password = :password WHERE email IN ('admin@bar.com','cliente@bar.com','mesero@bar.com','barra@bar.com')";
    $stmt = $db->prepare($sql);
    $stmt->execute([':password' => $hash]);
    
    echo "<h2>✓ Contraseñas actualizadas correctamente</h2>";
    echo "<p>La contraseña para todos los usuarios de prueba es: <strong>12345</strong></p>";
    echo "<h3>Usuarios disponibles:</h3>";
    echo "<ul>";
    echo "<li><strong>Administrador:</strong> admin@bar.com / 12345</li>";
    echo "<li><strong>Cliente:</strong> cliente@bar.com / 12345</li>";
    echo "<li><strong>Mesero:</strong> mesero@bar.com / 12345</li>";
    echo "<li><strong>Barra:</strong> barra@bar.com / 12345</li>";
    echo "</ul>";
    echo "<p><a href='../index.php'>Ir al sistema</a></p>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
