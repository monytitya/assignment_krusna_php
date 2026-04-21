-- Create Database
CREATE DATABASE IF NOT EXISTS Student_ass;
USE Student_ass;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender CHAR(1) NOT NULL COMMENT 'M for Male, F for Female',
    dob DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO students (name, gender, dob, phone, address, photo) VALUES
('Chan Daro', 'M', '2006-04-08', '012222111', 'Phnom Penh', ''),
('Sok Thida', 'F', '2007-02-22', '010332233', 'Phnom Penh', ''),
('Dong Channa', 'F', '2004-09-10', '012556677', 'Kandal', '');
