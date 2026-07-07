CREATE DATABASE IF NOT EXISTS pulsaciones
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE pulsaciones;

-- Tabla: familias
CREATE TABLE IF NOT EXISTS familias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_familiar VARCHAR(4) NOT NULL UNIQUE,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasenia VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario' NOT NULL,
    familia_id INT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (familia_id) REFERENCES familias(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabla: registros_oximetro
CREATE TABLE IF NOT EXISTS registros_oximetro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    pulsaciones INT NOT NULL,
    oxigeno INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Insertar Administrador Principal Técnico inicial (pass: cabj)
INSERT INTO usuarios (nombre, apellido, correo, contrasenia, rol, familia_id) 
VALUES (
    'Admin', 
    'Tecno 3', 
    'tecno3@unm.edu.ar', 
    '$2y$10$7R9XkZ8eYJ9wMc6K7qO1e.A5g7f2H6v3K9z1x4v5b6n7m8l9k0j1i', 
    'admin', 
    NULL
);