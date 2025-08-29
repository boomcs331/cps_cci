-- ปรับปรุงตาราง material_transactions
ALTER TABLE `material_transactions` 
ADD COLUMN `unit_received` int NOT NULL COMMENT 'จำนวนที่รับเข้าจริง (หน่วยเต็ม)' AFTER `unit`,
ADD COLUMN `calculated_pieces` int NOT NULL COMMENT 'จำนวนชิ้นที่คำนวณได้ (unit_received / packing)' AFTER `unit_received`;

-- ปรับปรุงตาราง material_stock ให้เก็บเฉพาะที่มีคงเหลือ
ALTER TABLE `material_stock` 
ADD COLUMN `last_updated` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดทล่าสุด',
ADD CONSTRAINT `chk_positive_balance` CHECK (`balance` > 0);

-- เพิ่ม index สำหรับประสิทธิภาพ
ALTER TABLE `material_transactions` 
ADD INDEX `idx_transaction_type` (`transaction_type`),
ADD INDEX `idx_date_transaction` (`date_transaction`),
ADD INDEX `idx_product_transaction` (`product_id`, `transaction_type`);

-- สร้าง View สำหรับแสดงข้อมูลรายละเอียดและยอดคงเหลือ
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

-- สร้าง View สำหรับแสดงประวัติการเคลื่อนไหว
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

-- สร้าง Stored Procedure สำหรับการรับเข้าสินค้า
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
    
    -- ดึงข้อมูล packing
    SELECT packing INTO v_packing 
    FROM material 
    WHERE product_id = p_product_id AND active = 1;
    
    -- คำนวณจำนวนชิ้น
    SET v_calculated_pieces = FLOOR(p_unit_received / v_packing);
    
    -- บันทึกรายการรับเข้า
    INSERT INTO material_transactions (
        transaction_no, product_id, unit_received, calculated_pieces, 
        unit, transaction_type, date_transaction, remark, created_by
    ) VALUES (
        p_transaction_no, p_product_id, p_unit_received, v_calculated_pieces,
        v_calculated_pieces, 'IN', CURDATE(), p_remark, p_created_by
    );
    
    -- อัพเดทยอดคงเหลือ
    SELECT COALESCE(balance, 0) INTO v_current_balance 
    FROM material_stock 
    WHERE product_id = p_product_id;
    
    IF v_current_balance > 0 THEN
        UPDATE material_stock 
        SET balance = balance + v_calculated_pieces 
        WHERE product_id = p_product_id;
    ELSE
        INSERT INTO material_stock (product_id, balance) 
        VALUES (p_product_id, v_calculated_pieces)
        ON DUPLICATE KEY UPDATE balance = balance + v_calculated_pieces;
    END IF;
    
    COMMIT;
END$$

-- สร้าง Stored Procedure สำหรับการจ่ายออก
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
    
    -- ตรวจสอบยอดคงเหลือ
    SELECT COALESCE(balance, 0) INTO v_current_balance 
    FROM material_stock 
    WHERE product_id = p_product_id;
    
    IF v_current_balance < p_unit_issue THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ยอดคงเหลือไม่เพียงพอ';
    END IF;
    
    -- บันทึกรายการจ่ายออก
    INSERT INTO material_transactions (
        transaction_no, product_id, unit_received, calculated_pieces,
        unit, transaction_type, date_transaction, remark, created_by
    ) VALUES (
        p_transaction_no, p_product_id, 0, 0,
        p_unit_issue, 'OUT', CURDATE(), p_remark, p_created_by
    );
    
    -- อัพเดทยอดคงเหลือ
    UPDATE material_stock 
    SET balance = balance - p_unit_issue 
    WHERE product_id = p_product_id;
    
    -- ลบรายการที่ยอดคงเหลือเป็น 0
    DELETE FROM material_stock 
    WHERE product_id = p_product_id AND balance <= 0;
    
    COMMIT;
END$$

DELIMITER ;

-- สร้าง Trigger สำหรับ audit log
CREATE TRIGGER `tr_material_stock_audit` 
AFTER UPDATE ON `material_stock`
FOR EACH ROW
BEGIN
    IF OLD.balance != NEW.balance THEN
        INSERT INTO material_transactions (
            transaction_no, product_id, unit_received, calculated_pieces,
            unit, transaction_type, date_transaction, remark, created_by
        ) VALUES (
            CONCAT('ADJ-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s')), 
            NEW.product_id, 0, 0, 
            (NEW.balance - OLD.balance), 
            IF(NEW.balance > OLD.balance, 'IN', 'OUT'),
            CURDATE(), 'System Adjustment', 'SYSTEM'
        );
    END IF;
END;