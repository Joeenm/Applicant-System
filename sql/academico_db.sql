CREATE DATABASE academico_db;

USE academico_db;

CREATE TABLE documentos_academicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato_id INT NOT NULL COMMENT 'ID desde usuarios_db.candidatos',
    tipo_documento ENUM(
        'doctorado', 
        'maestria', 
        'postgrado', 
        'licenciatura', 
        'tecnico', 
        'certificado', 
        'diplomado', 
        'seminario', 
        'curso'
    ) NOT NULL,
    nombre_documento VARCHAR(255) NOT NULL COMMENT 'Título o nombre del documento',
    nombre_archivo VARCHAR(255) NOT NULL COMMENT 'Nombre original del archivo subido',
    ruta_archivo VARCHAR(512) NOT NULL COMMENT 'Ruta relativa en el servidor donde se almacena',
    tamano_archivo INT NOT NULL COMMENT 'Tamaño en bytes',
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_candidato_id (candidato_id),
    INDEX idx_tipo_documento (tipo_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;