CREATE DATABASE admin_db;

USE admin_db;

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME,
    activo TINYINT(1) DEFAULT 1
);

CREATE TABLE logs_acceso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    administrador_id INT NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    accion VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    FOREIGN KEY (administrador_id) REFERENCES administradores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;