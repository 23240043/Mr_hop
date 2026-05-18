-- =====================================================
-- BASE DE DATOS: bar_restaurante
-- Sistema MVC para Bar Restaurante
-- =====================================================

CREATE DATABASE IF NOT EXISTS bar_restaurante CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bar_restaurante;

-- =====================================================
-- TABLA: roles
-- =====================================================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
);

INSERT INTO roles (nombre, descripcion) VALUES
('administrador', 'Acceso total al sistema'),
('cliente', 'Cliente del bar restaurante'),
('mesero', 'Personal de servicio en mesa'),
('barra', 'Personal de preparación en barra/cocina');

-- =====================================================
-- TABLA: usuarios
-- =====================================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    rol_id INT NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- =====================================================
-- TABLA: categorias (Bebida, Comida, Combo)
-- =====================================================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    tipo ENUM('bebida','comida','combo') NOT NULL,
    descripcion VARCHAR(255)
);

INSERT INTO categorias (nombre, tipo, descripcion) VALUES
('Cervezas', 'bebida', 'Cervezas nacionales e importadas'),
('Cocteles', 'bebida', 'Cocteles de la casa'),
('Vinos', 'bebida', 'Selección de vinos tintos y blancos'),
('Refrescos', 'bebida', 'Bebidas sin alcohol'),
('Entradas', 'comida', 'Aperitivos y entradas'),
('Platos Fuertes', 'comida', 'Platillos principales'),
('Postres', 'comida', 'Dulces y postres'),
('Combos Especiales', 'combo', 'Combos para compartir');

-- =====================================================
-- TABLA: productos (carta: bebidas, comidas, combos)
-- =====================================================
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'default.jpg',
    disponible TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

INSERT INTO productos (nombre, descripcion, precio, categoria_id, imagen) VALUES
('Cerveza Corona', 'Cerveza clara nacional 355ml', 45.00, 1, 'default.jpg'),
('Cerveza Indio', 'Cerveza oscura nacional 355ml', 45.00, 1, 'default.jpg'),
('Heineken', 'Cerveza importada 330ml', 60.00, 1, 'default.jpg'),
('Margarita Clásica', 'Tequila, triple sec, limón', 120.00, 2, 'default.jpg'),
('Mojito Cubano', 'Ron blanco, menta, limón, azúcar', 130.00, 2, 'default.jpg'),
('Piña Colada', 'Ron, piña, coco', 125.00, 2, 'default.jpg'),
('Vino Tinto Casa', 'Copa de vino tinto de la casa', 95.00, 3, 'default.jpg'),
('Coca-Cola', 'Refresco 600ml', 35.00, 4, 'default.jpg'),
('Agua Mineral', 'Agua mineral 600ml', 30.00, 4, 'default.jpg'),
('Nachos con Queso', 'Totopos con queso fundido y jalapeños', 110.00, 5, 'default.jpg'),
('Alitas BBQ', '8 piezas de alitas con salsa BBQ', 150.00, 5, 'default.jpg'),
('Guacamole de la Casa', 'Guacamole fresco con totopos', 95.00, 5, 'default.jpg'),
('Tacos al Pastor', '3 tacos al pastor con piña', 130.00, 6, 'default.jpg'),
('Hamburguesa Clásica', 'Carne 180g, queso, lechuga, tomate, papas', 180.00, 6, 'default.jpg'),
('Arrachera a la Plancha', 'Arrachera 250g con guarniciones', 280.00, 6, 'default.jpg'),
('Flan Napolitano', 'Flan tradicional con caramelo', 65.00, 7, 'default.jpg'),
('Pastel de Chocolate', 'Rebanada de pastel de chocolate', 75.00, 7, 'default.jpg'),
('Combo Botanero', '4 cervezas + nachos + alitas', 380.00, 8, 'default.jpg'),
('Combo Familiar', '2 hamburguesas + 2 refrescos + papas', 420.00, 8, 'default.jpg');

-- =====================================================
-- TABLA: mesas
-- =====================================================
CREATE TABLE mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE,
    capacidad INT NOT NULL DEFAULT 4,
    ubicacion VARCHAR(100),
    qr_codigo VARCHAR(255),
    estado ENUM('libre','ocupada','reservada') DEFAULT 'libre'
);

INSERT INTO mesas (numero, capacidad, ubicacion) VALUES
(1, 4, 'Terraza'),
(2, 4, 'Terraza'),
(3, 6, 'Salón principal'),
(4, 2, 'Barra'),
(5, 6, 'Salón principal'),
(6, 8, 'Área VIP');

-- =====================================================
-- TABLA: pedidos
-- =====================================================
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mesa_id INT,
    tipo ENUM('mesa','domicilio') NOT NULL DEFAULT 'mesa',
    direccion_entrega VARCHAR(255),
    estado ENUM('pendiente','en_preparacion','emplatado','listo','entregado','cancelado') DEFAULT 'pendiente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    notas TEXT,
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

-- =====================================================
-- TABLA: detalle_pedidos
-- =====================================================
CREATE TABLE detalle_pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- =====================================================
-- TABLA: resenas (reseñas de clientes)
-- =====================================================
CREATE TABLE resenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    aprobada TINYINT(1) DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- =====================================================
-- TABLA: ambiente (galería de imágenes del lugar)
-- =====================================================
CREATE TABLE ambiente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255) DEFAULT 'default.jpg',
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1
);

INSERT INTO ambiente (titulo, descripcion, imagen, orden) VALUES
('Salón Principal', 'Nuestro acogedor salón principal con iluminación cálida y música en vivo los fines de semana.', 'default.jpg', 1),
('Terraza al Aire Libre', 'Disfruta de nuestra terraza con vista al jardín, perfecta para tardes relajadas.', 'default.jpg', 2),
('Barra', 'Una barra completa con los mejores destilados nacionales e internacionales.', 'default.jpg', 3),
('Área VIP', 'Espacio reservado para eventos privados y celebraciones especiales.', 'default.jpg', 4);

-- =====================================================
-- TABLA: tokens_recuperacion (para recuperación de contraseña - futuro)
-- =====================================================
CREATE TABLE tokens_recuperacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expira_en DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- =====================================================
-- USUARIOS DE PRUEBA (password en texto plano para básico: "12345")
-- En producción se usará password_hash() de PHP
-- =====================================================
-- Contraseña para todos: 12345 (hash de password_hash con PASSWORD_DEFAULT)
INSERT INTO usuarios (nombre, apellidos, email, password, telefono, rol_id) VALUES
('Admin', 'Principal', 'admin@bar.com', '$2y$10$YourHashHere1234567890abcdefghijklmnopqrstuvwxyz', '5555555555', 1),
('Juan', 'Pérez García', 'cliente@bar.com', '$2y$10$YourHashHere1234567890abcdefghijklmnopqrstuvwxyz', '5544332211', 2),
('Carlos', 'Mesero López', 'mesero@bar.com', '$2y$10$YourHashHere1234567890abcdefghijklmnopqrstuvwxyz', '5544556677', 3),
('Pedro', 'Barra Ruiz', 'barra@bar.com', '$2y$10$YourHashHere1234567890abcdefghijklmnopqrstuvwxyz', '5566778899', 4);

-- NOTA: Después de instalar, ejecuta el archivo /database/seed_passwords.php
-- para generar los hashes correctos de las contraseñas de prueba.

-- =====================================================
-- RESEÑAS DE EJEMPLO
-- =====================================================
INSERT INTO resenas (usuario_id, calificacion, comentario) VALUES
(2, 5, '¡Excelente lugar! La comida estuvo deliciosa y el servicio fue muy atento. Volveré pronto.'),
(2, 4, 'Muy buen ambiente, los cócteles son los mejores de la zona. Recomendado.');
