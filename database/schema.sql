-- Database schema for CPS (Computer Production System)
-- Created: 2025

-- Users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Roles table
CREATE TABLE roles (
    role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- User roles relationship table
CREATE TABLE user_roles (
    id BIGINT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (id, role_id),
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert default roles
INSERT INTO roles (name) VALUES 
('admin'),
('pc'),
('user');

-- Insert sample users (password: 123456)
INSERT INTO users (user_id, full_name, email) VALUES 
('admin001', 'System Administrator', 'admin@example.com'),
('pc001', 'Production Controller', 'pc@example.com'),
('user001', 'Regular User', 'user@example.com');

-- Assign roles to users
INSERT INTO user_roles (id, role_id) VALUES 
(1, 1), -- admin001 -> admin
(2, 2), -- pc001 -> pc  
(3, 3); -- user001 -> user

-- Materials table
CREATE TABLE materials (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mat_id VARCHAR(50) NOT NULL UNIQUE,
    mat_name VARCHAR(255) NOT NULL,
    lr VARCHAR(50),
    min_qty INT DEFAULT 0,
    packing VARCHAR(100),
    supplier VARCHAR(255),
    location VARCHAR(255),
    img VARCHAR(255),
    active TINYINT DEFAULT 1,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert sample materials
INSERT INTO materials (mat_id, mat_name, lr, min_qty, packing, supplier, location, img, active) VALUES 
('MAT001', 'CPU Intel Core i7', 'LR001', 10, '500', 'Intel Corporation', 'Warehouse A - Shelf 1', 'cpu_intel_i7.jpg', 1),
('MAT002', 'RAM DDR4 16GB', 'LR002', 20, '100', 'Kingston Technology', 'Warehouse A - Shelf 2', 'ram_ddr4_16gb.jpg', 1),
('MAT003', 'SSD 500GB', 'LR003', 15, '200', 'Samsung Electronics', 'Warehouse B - Shelf 1', 'ssd_500gb.jpg', 1),
('MAT004', 'Motherboard ATX', 'LR004', 8, '50', 'ASUS', 'Warehouse B - Shelf 2', 'motherboard_atx.jpg', 1),
('MAT005', 'Power Supply 650W', 'LR005', 12, '100', 'Corsair', 'Warehouse C - Shelf 1', 'psu_650w.jpg', 1);

-- Material Stock table (for tracking current stock)
CREATE TABLE material_stock (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    material_id BIGINT UNSIGNED NOT NULL,
    current_qty INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Material Transactions table (for tracking all in/out movements)
CREATE TABLE material_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    material_id BIGINT UNSIGNED NOT NULL,
    transaction_type ENUM('IN', 'OUT') NOT NULL,
    quantity INT NOT NULL,
    reference_no VARCHAR(100),
    description TEXT,
    unit_price DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) DEFAULT 0.00,
    supplier VARCHAR(255),
    recipient VARCHAR(255),
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT UNSIGNED,
    notes TEXT,
    qr_code VARCHAR(100),
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Material QR Codes table (for tracking individual packing units)
CREATE TABLE material_qr_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    qr_code VARCHAR(100) NOT NULL UNIQUE,
    material_id BIGINT UNSIGNED NOT NULL,
    transaction_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    packing_unit_number INT NOT NULL,
    total_packing_units INT NOT NULL,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ACTIVE', 'USED', 'EXPIRED') DEFAULT 'ACTIVE',
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    FOREIGN KEY (transaction_id) REFERENCES material_transactions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert initial stock for sample materials
INSERT INTO material_stock (material_id, current_qty) VALUES 
(1, 50),  -- CPU Intel Core i7: 50 units
(2, 100), -- RAM DDR4 16GB: 100 units
(3, 75),  -- SSD 500GB: 75 units
(4, 30),  -- Motherboard ATX: 30 units
(5, 40);  -- Power Supply 650W: 40 units

-- Insert sample transactions
INSERT INTO material_transactions (material_id, transaction_type, quantity, reference_no, description, unit_price, total_amount, supplier, recipient, created_by, notes) VALUES 
(1, 'IN', 50, 'PO-2025-001', 'Initial stock purchase', 250.00, 12500.00, 'Intel Corporation', NULL, 2, 'Initial stock setup'),
(2, 'IN', 100, 'PO-2025-002', 'Initial stock purchase', 80.00, 8000.00, 'Kingston Technology', NULL, 2, 'Initial stock setup'),
(3, 'IN', 75, 'PO-2025-003', 'Initial stock purchase', 120.00, 9000.00, 'Samsung Electronics', NULL, 2, 'Initial stock setup'),
(4, 'IN', 30, 'PO-2025-004', 'Initial stock purchase', 150.00, 4500.00, 'ASUS', NULL, 2, 'Initial stock setup'),
(5, 'IN', 40, 'PO-2025-005', 'Initial stock purchase', 90.00, 3600.00, 'Corsair', NULL, 2, 'Initial stock setup'),
(1, 'OUT', 5, 'REQ-2025-001', 'Production line A', 0.00, 0.00, NULL, 'Production Line A', 2, 'Production requirement'),
(2, 'OUT', 10, 'REQ-2025-002', 'Production line B', 0.00, 0.00, NULL, 'Production Line B', 2, 'Production requirement'),
(3, 'OUT', 8, 'REQ-2025-003', 'Maintenance department', 0.00, 0.00, NULL, 'Maintenance Dept', 2, 'Maintenance replacement'); 