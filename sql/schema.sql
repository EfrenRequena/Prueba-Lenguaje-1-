-- Configuración del charset para soporte internacional
CREATE DATABASE IF NOT EXISTS sistema_db 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE sistema_db;

-- Tabla preparada para la persistencia segura (PDO y BCRYPT)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB; 
-- ENGINE=InnoDB asegura la integridad transaccional (ACID) y llaves foráneas si las necesitas después.