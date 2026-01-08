CREATE DATABASE IF NOT EXISTS db_interacto;
USE db_interacto;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('OWNER', 'ADMINISTRADOR', 'COLABORADOR', 'MONITOR') NOT NULL,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de log de eventos
CREATE TABLE IF NOT EXISTS event_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    role VARCHAR(50),
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Usuario inicial (Password: root1234)
INSERT INTO users (name, email, password, role) 
VALUES ('Super User', 'admin@interacto.com', '$2y$10$7Z8q2v6V6G2Y/r6f/4Cq8.v7L0Tq1Rj9R/tYvA6Pq9Y6f8p4U5G6e', 'OWNER')
ON DUPLICATE KEY UPDATE id=id;

-- Tabla de servicios (Master)
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    server_id INT,
    name VARCHAR(200) NOT NULL,
    type ENUM('Página Web', 'Aplicación', 'API', 'Base de Datos', 'Otro') DEFAULT 'Página Web',
    path VARCHAR(500),
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (server_id) REFERENCES servers(id) ON DELETE SET NULL,
    INDEX idx_client (client_id),
    INDEX idx_server (server_id)
);

-- Tabla de detalles técnicos del servicio
CREATE TABLE IF NOT EXISTS service_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    role_name VARCHAR(100) NOT NULL,
    url VARCHAR(500),
    username VARCHAR(200),
    password VARCHAR(200),
    observations TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_service (service_id)
);
