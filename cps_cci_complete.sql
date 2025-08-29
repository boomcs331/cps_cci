/*
 Complete Database Schema for CPS CCI Material Management System
 Date: 29/08/2025 20:47:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Create Database
CREATE DATABASE IF NOT EXISTS `cps_cci` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cps_cci`;

-- ----------------------------
-- Table structure for material
-- ----------------------------
DROP TABLE IF EXISTS `material`;
CREATE TABLE `material`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'รหัสอัตโนมัติ (Primary Key)',
  `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสผลิตภัณฑ์',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อผลิตภัณฑ์/วัตถุดิบ',
  `packing` int NULL DEFAULT 1 COMMENT 'จำนวนบรรจุภัณฑ์',
  `lr` enum('LH','RH') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ซ้าย-ขวา: LH=Left Hand, RH=Right Hand',
  `supplier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ผู้จัดจำหน่าย',
  `min_stock` int NULL DEFAULT 0 COMMENT 'จำนวนสต็อกขั้นต่ำ',
  `due` int NULL DEFAULT NULL COMMENT 'จำนวนสต็อกขั้นต่ำ',
  `type` enum('pc','of','of_mat') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภท: pc=Piece, of=Office, of_mat=Office Material',
  `location_plan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'แผนที่จัดเก็บ',
  `img` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'รูปภาพ (file path หรือ URL)',
  `active` tinyint(1) NULL DEFAULT 1 COMMENT 'สถานะใช้งาน: 1=ใช้งาน, 0=ไม่ใช้งาน',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp COMMENT 'วันที่สร้าง',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไขล่าสุด',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_id`(`product_id` ASC) USING BTREE,
  INDEX `idx_product_id`(`product_id` ASC) USING BTREE,
  INDEX `idx_supplier`(`supplier` ASC) USING BTREE,
  INDEX `idx_type`(`type` ASC) USING BTREE,
  INDEX `idx_active`(`active` ASC) USING BTREE,
  INDEX `idx_min_stock`(`min_stock` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'ตารางข้อมูลวัตถุดิบ/ผลิตภัณฑ์' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for material_stock
-- ----------------------------
DROP TABLE IF EXISTS `material_stock`;
CREATE TABLE `material_stock`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` int NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดทล่าสุด',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `chk_positive_balance` CHECK (`balance` > 0)
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_transactions
-- ----------------------------
DROP TABLE IF EXISTS `material_transactions`;
CREATE TABLE `material_transactions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'เลขที่เอกสาร',
  `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_received` int NULL DEFAULT 0 COMMENT 'จำนวนที่รับเข้าจริง (หน่วยเต็ม)',
  `calculated_pieces` int NULL DEFAULT 0 COMMENT 'จำนวนชิ้นที่คำนวณได้ (unit_received / packing)',
  `unit` int NOT NULL COMMENT 'จำนวนสุดท้าย',
  `transaction_type` enum('IN','OUT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ประเภทรายการ: IN=รับเข้า, OUT=จ่ายออก',
  `date_transaction` date NOT NULL COMMENT 'วันที่ทำรายการ',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT current_timestamp,
  `updated_date` timestamp NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_transaction_type`(`transaction_type` ASC) USING BTREE,
  INDEX `idx_date_transaction`(`date_transaction` ASC) USING BTREE,
  INDEX `idx_product_transaction`(`product_id` ASC, `transaction_type` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material_transactions_detail
-- ----------------------------
DROP TABLE IF EXISTS `material_transactions_detail`;
CREATE TABLE `material_transactions_detail`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` int NOT NULL,
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อสิทธิ์ เช่น admin, user, pc',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_roles_role_name`(`role_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'รหัสพนักงาน/รหัสผู้ใช้ (ใช้ล็อกอิน/อ้างอิง)',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ชื่อ-สกุล',
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=ใช้งาน,0=ปิดการใช้งาน',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_users_user_id`(`user_id` ASC) USING BTREE,
  INDEX `ix_users_active`(`active` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for user_roles
-- ----------------------------
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL COMMENT 'FK -> users.id',
  `role_id` int UNSIGNED NOT NULL COMMENT 'FK -> roles.id',
  `assigned_date` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_user_role_userid_roleid`(`user_id` ASC, `role_id` ASC) USING BTREE,
  INDEX `ix_user_role_user_id`(`user_id` ASC) USING BTREE,
  INDEX `ix_user_role_role_id`(`role_id` ASC) USING BTREE,
  CONSTRAINT `fk_user_role_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_role_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Views
-- ----------------------------
CREATE OR REPLACE VIEW `v_material_summary` AS
SELECT 
    m.id,
    m.product_id,
    m.product_name,
    m.packing,
    m.supplier,
    m.min_stock,
    m.type,
    COALESCE(ms.balance, 0) as current_balance,
    CASE 
        WHEN COALESCE(ms.balance, 0) <= m.min_stock THEN 'LOW_STOCK'
        WHEN COALESCE(ms.balance, 0) = 0 THEN 'OUT_OF_STOCK'
        ELSE 'NORMAL'
    END as stock_status,
    ms.last_updated as balance_last_updated
FROM material m
LEFT JOIN material_stock ms ON m.product_id = ms.product_id
WHERE m.active = 1;

CREATE OR REPLACE VIEW `v_material_movements` AS
SELECT 
    mt.id,
    mt.transaction_no,
    mt.product_id,
    m.product_name,
    m.packing,
    mt.unit_received,
    mt.calculated_pieces,
    mt.unit as final_unit,
    mt.transaction_type,
    mt.date_transaction,
    mt.remark,
    mt.created_by,
    mt.create_date
FROM material_transactions mt
JOIN material m ON mt.product_id = m.product_id
ORDER BY mt.date_transaction DESC, mt.create_date DESC;

-- ----------------------------
-- Stored Procedures
-- ----------------------------
DELIMITER $$

CREATE PROCEDURE `sp_material_receive`(
    IN p_transaction_no VARCHAR(255),
    IN p_product_id VARCHAR(255),
    IN p_unit_received INT,
    IN p_remark TEXT,
    IN p_created_by VARCHAR(255)
)
BEGIN
    DECLARE v_packing INT DEFAULT 1;
    DECLARE v_calculated_pieces INT;
    DECLARE v_current_balance INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    SELECT packing INTO v_packing 
    FROM material 
    WHERE product_id = p_product_id AND active = 1;
    
    SET v_calculated_pieces = FLOOR(p_unit_received / v_packing);
    
    INSERT INTO material_transactions (
        transaction_no, product_id, unit_received, calculated_pieces, 
        unit, transaction_type, date_transaction, remark, created_by
    ) VALUES (
        p_transaction_no, p_product_id, p_unit_received, v_calculated_pieces,
        v_calculated_pieces, 'IN', CURDATE(), p_remark, p_created_by
    );
    
    INSERT INTO material_stock (product_id, balance) 
    VALUES (p_product_id, v_calculated_pieces)
    ON DUPLICATE KEY UPDATE balance = balance + v_calculated_pieces;
    
    COMMIT;
END$$

CREATE PROCEDURE `sp_material_issue`(
    IN p_transaction_no VARCHAR(255),
    IN p_product_id VARCHAR(255),
    IN p_unit_issue INT,
    IN p_remark TEXT,
    IN p_created_by VARCHAR(255)
)
BEGIN
    DECLARE v_current_balance INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    SELECT COALESCE(balance, 0) INTO v_current_balance 
    FROM material_stock 
    WHERE product_id = p_product_id;
    
    IF v_current_balance < p_unit_issue THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ยอดคงเหลือไม่เพียงพอ';
    END IF;
    
    INSERT INTO material_transactions (
        transaction_no, product_id, unit_received, calculated_pieces,
        unit, transaction_type, date_transaction, remark, created_by
    ) VALUES (
        p_transaction_no, p_product_id, 0, 0,
        p_unit_issue, 'OUT', CURDATE(), p_remark, p_created_by
    );
    
    UPDATE material_stock 
    SET balance = balance - p_unit_issue 
    WHERE product_id = p_product_id;
    
    DELETE FROM material_stock 
    WHERE product_id = p_product_id AND balance <= 0;
    
    COMMIT;
END$$

DELIMITER ;

-- ----------------------------
-- Sample Data
-- ----------------------------
INSERT INTO `roles` (`role_name`, `description`) VALUES
('admin', 'ผู้ดูแลระบบ'),
('user', 'ผู้ใช้งานทั่วไป'),
('pc', 'ผู้ใช้งาน PC');

INSERT INTO `users` (`user_id`, `name`) VALUES
('admin', 'ผู้ดูแลระบบ'),
('user001', 'พนักงาน 001');

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2);

INSERT INTO `material` (`product_id`, `product_name`, `packing`, `type`, `supplier`, `min_stock`) VALUES
('P001', 'ชิ้นส่วน A', 10, 'pc', 'Supplier A', 50),
('P002', 'ชิ้นส่วน B', 20, 'pc', 'Supplier B', 30),
('OF001', 'กระดาษ A4', 500, 'of', 'Office Supply', 10);

-- Add Foreign Key Constraints after all tables are created
ALTER TABLE `material_stock` ADD CONSTRAINT `fk_stock_material` FOREIGN KEY (`product_id`) REFERENCES `material` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `material_transactions` ADD CONSTRAINT `fk_trans_material` FOREIGN KEY (`product_id`) REFERENCES `material` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `material_transactions_detail` ADD CONSTRAINT `fk_trans_detail_material` FOREIGN KEY (`product_id`) REFERENCES `material` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;