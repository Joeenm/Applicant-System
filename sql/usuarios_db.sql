CREATE DATABASE usuarios_db;

USE usuarios_db;

CREATE TABLE candidatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nacionalidad VARCHAR(50) NOT NULL,
    dia_nacimiento TINYINT NOT NULL,
    mes_nacimiento TINYINT NOT NULL,
    ano_nacimiento SMALLINT NOT NULL,
    estado_civil ENUM('Soltero/a', 'Casado/a', 'Divorciado/a', 'Viudo/a', 'Unión Libre') NOT NULL,
    tipo_documento ENUM('Cédula', 'Pasaporte') NOT NULL,
    numero_documento VARCHAR(50) NOT NULL UNIQUE,
    genero ENUM('Femenino', 'Masculino') NOT NULL,
    movilidad_propia TINYINT(1) DEFAULT 0,
    tiene_licencia TINYINT(1) DEFAULT 0,
    tipos_licencia VARCHAR(255),
    telefono_celular VARCHAR(20) NOT NULL,
    otro_telefono VARCHAR(20),
    pais VARCHAR(50) DEFAULT 'Panamá',
    provincia VARCHAR(50) NOT NULL,
    distrito VARCHAR(50) NOT NULL,
    direccion TEXT NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;