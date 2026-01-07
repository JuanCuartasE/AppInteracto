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
