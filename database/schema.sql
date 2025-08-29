-- =========================================================
-- Users / Roles / User_Role schema (MySQL / InnoDB / utf8mb4)
-- =========================================================
-- Safety first
SET
    NAMES utf8mb4;

SET
    FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user_role;

DROP TABLE IF EXISTS roles;

DROP TABLE IF EXISTS users;

SET
    FOREIGN_KEY_CHECKS = 1;

-- -----------------------
-- Table: users
-- -----------------------
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id VARCHAR(50) NOT NULL COMMENT 'รหัสพนักงาน/รหัสผู้ใช้ (ใช้ล็อกอิน/อ้างอิง)',
    name VARCHAR(100) NOT NULL COMMENT 'ชื่อ-สกุล',
    active TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=ใช้งาน,0=ปิดการใช้งาน',
    create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_user_id (user_id),
    KEY ix_users_active (active)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------
-- Table: roles
-- -----------------------
CREATE TABLE roles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL COMMENT 'ชื่อสิทธิ์ เช่น admin, user, pc',
    description VARCHAR(255) NULL,
    create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_roles_role_name (role_name)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------
-- Table: user_role  (junction)
-- -----------------------
CREATE TABLE user_roles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL COMMENT 'FK -> users.id',
    role_id INT UNSIGNED NOT NULL COMMENT 'FK -> roles.id',
    assigned_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_user_role_userid_roleid (user_id, role_id),
    -- กันซ้ำ
    KEY ix_user_role_user_id (user_id),
    KEY ix_user_role_role_id (role_id),
    CONSTRAINT fk_user_role_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_user_role_role FOREIGN KEY (role_id) REFERENCES roles(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- =========================================================
-- EXTRAS: ตัวอย่างใช้งาน (เอาออกได้หากไม่ต้องการ)
-- =========================================================
-- ตัวอย่างเพิ่มข้อมูล
-- INSERT INTO users (user_id, name, active) VALUES ('EMP001', 'สมชาย ใจดี', 1);
-- INSERT INTO roles (role_name, description) VALUES ('admin','ผู้ดูแลระบบ'), ('user','ผู้ใช้งานทั่วไป');
-- ผูกสิทธิ์
-- INSERT INTO user_role (user_id, role_id) VALUES (1, 1), (1, 2);
-- ตัวอย่างดึงข้อมูล User พร้อมรายชื่อ Role
-- SELECT u.id, u.user_id, u.name, u.active, GROUP_CONCAT(r.role_name ORDER BY r.role_name) AS roles
-- FROM users u
-- LEFT JOIN user_role ur ON ur.user_id = u.id
-- LEFT JOIN roles r ON r.id = ur.role_id
-- GROUP BY u.id;

-- สคริปต์สำหรับสร้างตาราง material
-- Material Management Database Table

CREATE TABLE material (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสหลัก (Primary Key)',
    product_id VARCHAR(50) UNIQUE NOT NULL COMMENT 'รหัสสินค้า',
    product_name VARCHAR(255) NOT NULL COMMENT 'ชื่อสินค้า/วัตถุดิบ',
    packing INT(100) COMMENT 'บรรจุภัณฑ์',
    lr ENUM('LH', 'RH') COMMENT 'ทิศทาง: LH=Left Hand, RH=Right Hand',
    supplier VARCHAR(255) COMMENT 'ผู้จัดหา',
    min_stock INT DEFAULT 0 COMMENT 'สต็อกขั้นต่ำ',
    due INT(100) COMMENT 'วันที่ครบกำหนด',
    type ENUM('pc', 'of', 'of_mat') NOT NULL COMMENT 'ประเภท: pc=Piece, of=Office, of_mat=Office Material',
    location_plan VARCHAR(255) COMMENT 'แผนที่ตำแหน่งในคลัง',
    img VARCHAR(500) COMMENT 'รูปภาพ (file path หรือ URL)',
    active TINYINT(1) DEFAULT 1 COMMENT 'สถานะใช้งาน: 1=ใช้งาน, 0=ไม่ใช้งาน',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันที่สร้าง',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่แก้ไขล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางจัดการวัตถุดิบและสินค้า';

-- สร้าง Index เพื่อเพิ่มประสิทธิภาพ
CREATE INDEX idx_product_id ON material(product_id);
CREATE INDEX idx_supplier ON material(supplier);
CREATE INDEX idx_type ON material(type);
CREATE INDEX idx_active ON material(active);
CREATE INDEX idx_min_stock ON material(min_stock);

-- ตัวอย่างการเพิ่มข้อมูล Mock Data 25 Records
INSERT INTO material (
    product_id, 
    product_name, 
    packing, 
    lr, 
    supplier, 
    min_stock, 
    due, 
    type, 
    location_plan, 
    img, 
    active
) VALUES 
-- วัตถุดิบชิ้นส่วน (pc)
('MAT001', 'สกรูสแตนเลส 4x20mm', 100, 'LH', 'บริษัท ABC จำกัด', 50, 2, 'pc', 'A1-B2-C3', 'images/materials/screw_stainless.jpg', 1),
('MAT002', 'น็อตหกเหลี่ยม M8', 200, 'RH', 'บริษัท FastTech จำกัด', 75, 3, 'pc', 'A1-B3-C1', NULL, 1),
('MAT003', 'แหวนรองสแตนเลส', 500, NULL, 'บริษัท Metal Pro จำกัด', 100, 5, 'pc', 'A2-B1-C2', NULL, 1),
('MAT004', 'สปริงดันแรงสูง', 50, 'LH', 'บริษัท Spring Tech จำกัด', 25, 4, 'pc', 'A2-B2-C3', 'images/materials/spring.jpg', 1),
('MAT005', 'เฟืองทองเหลือง 20T', 25, 'RH', 'บริษัท Gear Works จำกัด', 15, 6, 'pc', 'A3-B1-C1', NULL, 1),
('MAT006', 'แผ่นพลาสติก ABS 5mm', 100, NULL, 'บริษัท Plastic Master จำกัด', 30, 3, 'pc', 'B1-C2-D1', 'images/materials/abs_sheet.jpg', 1),
('MAT007', 'มอเตอร์ไฟฟ้า 12V', 20, 'LH', 'บริษัท Motor Tech จำกัด', 10, 8, 'pc', 'B2-C1-D2', NULL, 1),
('MAT008', 'สายไฟ 2.5mm แดง', 1000, NULL, 'บริษัท Wire Plus จำกัด', 200, 2, 'pc', 'B3-C3-D1', NULL, 1),
('MAT009', 'หลอดฟิวส์ 5A', 200, NULL, 'บริษัท Electric Safe จำกัด', 50, 4, 'pc', 'C1-D1-E1', 'images/materials/fuse.jpg', 1),
('MAT010', 'ตัวต้านทาน 100Ω', 500, NULL, 'บริษัท Electronic Pro จำกัด', 100, 3, 'pc', 'C2-D2-E2', NULL, 1),

-- วัสดุสำนักงาน (of_mat)
('OFF001', 'กระดาษ A4 80gsm', 500, NULL, 'บริษัท Paper Plus จำกัด', 20, 1, 'of_mat', 'OFF-A1', 'images/office/paper_a4.jpg', 1),
('OFF002', 'ปากกาลูกลื่น สีน้ำเงิน', 50, NULL, 'บริษัท Pen Master จำกัด', 25, 2, 'of_mat', 'OFF-A2', NULL, 1),
('OFF003', 'แฟ้มเอกสาร A4', 20, NULL, 'บริษัท File Pro จำกัด', 15, 3, 'of_mat', 'OFF-B1', NULL, 1),
('OFF004', 'ตัวเย็บกระดาษ เบอร์ 10', 1000, NULL, 'บริษัท Staple Tech จำกัด', 200, 5, 'of_mat', 'OFF-B2', 'images/office/staples.jpg', 1),
('OFF005', 'กาวติดกระดาษ 40g', 100, NULL, 'บริษัท Glue Works จำกัด', 30, 2, 'of_mat', 'OFF-C1', NULL, 1),
('OFF006', 'ซองจดหมาย ขาว DL', 500, NULL, 'บริษัท Envelope Plus จำกัด', 100, 4, 'of_mat', 'OFF-C2', NULL, 1),
('OFF007', 'คลิปหนีบกระดาษ ขนาดกลาง', 200, NULL, 'บริษัท Clip Master จำกัด', 50, 3, 'of_mat', 'OFF-D1', 'images/office/clips.jpg', 1),
('OFF008', 'ยางลบดินสอ', 50, NULL, 'บริษัท Eraser Pro จำกัด', 20, 2, 'of_mat', 'OFF-D2', NULL, 1),

-- อุปกรณ์สำนักงาน (of)
('EQ001', 'เครื่องถ่อเอกสาร A4', 1, NULL, 'บริษัท Office Equipment จำกัด', 1, 12, 'of', 'EQ-ROOM-A', 'images/office/copier.jpg', 1),
('EQ002', 'เครื่องพิมพ์เลเซอร์', 1, NULL, 'บริษัท Print Tech จำกัด', 1, 15, 'of', 'EQ-ROOM-B', 'images/office/printer.jpg', 1),
('EQ003', 'เครื่องสำรองไฟ 1500VA', 1, NULL, 'บริษัท Power Guard จำกัด', 1, 10, 'of', 'EQ-ROOM-C', NULL, 1),
('EQ004', 'โต๊ะทำงาน 120x60cm', 1, NULL, 'บริษัท Furniture Pro จำกัด', 2, 24, 'of', 'STORE-01', NULL, 1),
('EQ005', 'เก้าอี้สำนักงาน', 1, NULL, 'บริษัท Chair Master จำกัด', 3, 18, 'of', 'STORE-02', 'images/office/chair.jpg', 1),
('EQ006', 'ตู้เก็บเอกสาร 4 ลิ้นชัก', 1, NULL, 'บริษัท Cabinet Plus จำกัด', 1, 36, 'of', 'STORE-03', NULL, 1),
('EQ007', 'เครื่องปรับอากาศ 18000 BTU', 1, NULL, 'บริษัท Cool Air จำกัด', 1, 60, 'of', 'MAINT-01', 'images/office/aircon.jpg', 1);


-- สินค้า (ใช้ของเดิมได้)
-- material (...)

-- 1) Header เอกสาร IN/OUT
DROP TABLE IF EXISTS material_transactions;
CREATE TABLE material_transactions (
  id               INT NOT NULL AUTO_INCREMENT,
  transaction_no   VARCHAR(50) NOT NULL,
  transaction_type ENUM('IN','OUT') NOT NULL,
  date_transaction DATE NOT NULL,
  remark           TEXT NULL,
  created_by       VARCHAR(100) NULL,
  create_date      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_date     TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_transactions_no (transaction_no),
  KEY ix_transactions_date (date_transaction),
  KEY ix_transactions_type (transaction_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) รายการบรรทัดในเอกสาร (เก็บหน่วยเป็นชิ้น)
DROP TABLE IF EXISTS material_transaction_details;
CREATE TABLE material_transaction_details (
  id               INT NOT NULL AUTO_INCREMENT,
  transaction_no   VARCHAR(50) NOT NULL,
  product_id       VARCHAR(50) NOT NULL,
  qty_pieces       INT NOT NULL,             -- ปริมาณหน่วยชิ้นที่รับ/จ่าย (IN เป็นบวก, OUT เป็นบวกที่ฝั่งเอกสาร แต่จะหักตอนตัดสต็อก)
  packing_per_pack INT NOT NULL,             -- ค่าบรรจุ (packing) ณ เวลาทำรายการ
  packs_derived    INT NOT NULL,             -- FLOOR(qty_pieces / packing_per_pack)
  remainder_pieces INT NOT NULL,             -- MOD(qty_pieces, packing_per_pack)
  lot_id           INT NULL,                 -- จะถูกเติมหลังสร้าง lot (สำหรับ IN)
  qr_code          VARCHAR(255) NULL,        -- ถ้าต้องการอ้างอิง QR ต่อบรรทัด
  PRIMARY KEY (id),
  KEY ix_detail_txn (transaction_no),
  KEY ix_detail_product (product_id),
  CONSTRAINT fk_detail_header FOREIGN KEY (transaction_no)
    REFERENCES material_transactions (transaction_no) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) Lot ที่เกิดจากการรับเข้า (IN) แต่ละ lot มีคงเหลือของตัวเอง
DROP TABLE IF EXISTS material_lots;
CREATE TABLE material_lots (
  id                 INT NOT NULL AUTO_INCREMENT,
  product_id         VARCHAR(50) NOT NULL,
  transaction_no     VARCHAR(50) NOT NULL,   -- เอกสารที่สร้าง lot
  detail_id          INT NOT NULL,           -- อ้างอิงบรรทัดที่สร้าง lot
  packing_per_pack   INT NOT NULL,
  received_pieces    INT NOT NULL,           -- จำนวนชิ้นที่รับเข้าใน lot นี้
  received_packs     INT NOT NULL,           -- packs_derived ตอนรับเข้า
  received_remainder INT NOT NULL,           -- remainder_pieces ตอนรับเข้า
  balance_pieces     INT NOT NULL,           -- คงเหลือ ณ ตอนปัจจุบัน (ชิ้น)
  status             ENUM('OPEN','CLOSED') NOT NULL DEFAULT 'OPEN',
  create_date        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  update_date        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY ix_lots_product (product_id),
  KEY ix_lots_status (status),
  CONSTRAINT fk_lots_detail FOREIGN KEY (detail_id)
    REFERENCES material_transaction_details (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) เก็บเฉพาะ lot ที่ยังคงเหลือ (แยกไว้ตามข้อกำหนด)
DROP TABLE IF EXISTS material_open_lots;
CREATE TABLE material_open_lots (
  lot_id         INT NOT NULL,
  product_id     VARCHAR(50) NOT NULL,
  balance_pieces INT NOT NULL,
  PRIMARY KEY (lot_id),
  KEY ix_openlots_product (product_id),
  CONSTRAINT fk_openlots_lot FOREIGN KEY (lot_id)
    REFERENCES material_lots (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5) สรุปคงคลังต่อสินค้า (มีเฉพาะเมื่อคงเหลือ > 0)
DROP TABLE IF EXISTS material_stock;
CREATE TABLE material_stock (
  product_id     VARCHAR(50) NOT NULL,
  balance_pieces INT NOT NULL,
  PRIMARY KEY (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6) สมุดรายวันสต็อก (Ledger) สำหรับ audit และคำนวณยอดวิ่ง
DROP TABLE IF EXISTS material_ledger;
CREATE TABLE material_ledger (
  id                 INT NOT NULL AUTO_INCREMENT,
  product_id         VARCHAR(50) NOT NULL,
  lot_id             INT NULL,               -- OUT อาจถูกตัดหลาย lot -> สร้างหลายบรรทัด
  transaction_no     VARCHAR(50) NOT NULL,
  date_transaction   DATE NOT NULL,
  qty_change_pieces  INT NOT NULL,           -- IN: +, OUT: - (ต่อ lot)
  balance_after_pcs  INT NOT NULL,           -- ยอดรวมหลังเคลื่อนไหว (ต่อ product)
  create_date        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY ix_ledger_product_date (product_id, date_transaction),
  KEY ix_ledger_txn (transaction_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================================
-- Foreign Keys to ensure referential integrity
-- ========================================

ALTER TABLE material_transaction_details
  ADD CONSTRAINT fk_detail_product FOREIGN KEY (product_id)
    REFERENCES material (product_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  ADD CONSTRAINT fk_detail_header_no FOREIGN KEY (transaction_no)
    REFERENCES material_transactions (transaction_no) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE material_lots
  ADD CONSTRAINT fk_lots_product FOREIGN KEY (product_id)
    REFERENCES material (product_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  ADD CONSTRAINT fk_lots_header_no FOREIGN KEY (transaction_no)
    REFERENCES material_transactions (transaction_no) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE material_open_lots
  ADD CONSTRAINT fk_openlots_product FOREIGN KEY (product_id)
    REFERENCES material (product_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE material_stock
  ADD CONSTRAINT fk_stock_product FOREIGN KEY (product_id)
    REFERENCES material (product_id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE material_ledger
  ADD CONSTRAINT fk_ledger_product FOREIGN KEY (product_id)
    REFERENCES material (product_id) ON UPDATE CASCADE ON DELETE RESTRICT,
  ADD CONSTRAINT fk_ledger_lot FOREIGN KEY (lot_id)
    REFERENCES material_lots (id) ON UPDATE CASCADE ON DELETE SET NULL,
  ADD CONSTRAINT fk_ledger_header_no FOREIGN KEY (transaction_no)
    REFERENCES material_transactions (transaction_no) ON UPDATE CASCADE ON DELETE CASCADE;

-- ========================================
-- Stored Procedure: FIFO issue by detail_id
-- ========================================

DELIMITER $$
DROP PROCEDURE IF EXISTS sp_material_issue_fifo $$
CREATE PROCEDURE sp_material_issue_fifo(IN p_detail_id INT)
BEGIN
  DECLARE v_product_id VARCHAR(50);
  DECLARE v_qty_needed INT;
  DECLARE v_txn_no VARCHAR(50);
  DECLARE v_txn_date DATE;

  SELECT d.product_id, d.qty_pieces, d.transaction_no, t.date_transaction
    INTO v_product_id, v_qty_needed, v_txn_no, v_txn_date
  FROM material_transaction_details d
  JOIN material_transactions t ON t.transaction_no = d.transaction_no
  WHERE d.id = p_detail_id;

  WHILE v_qty_needed > 0 DO
    DECLARE v_lot_id INT;
    DECLARE v_lot_balance INT;

    SELECT ol.lot_id, ol.balance_pieces
      INTO v_lot_id, v_lot_balance
    FROM material_open_lots ol
    WHERE ol.product_id = v_product_id
    ORDER BY ol.lot_id
    LIMIT 1;

    IF v_lot_id IS NULL THEN
      SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Insufficient stock for FIFO issue';
    END IF;

    IF v_qty_needed >= v_lot_balance THEN
      -- consume entire lot
      SET v_qty_needed = v_qty_needed - v_lot_balance;

      UPDATE material_lots SET balance_pieces = 0, status = 'CLOSED' WHERE id = v_lot_id;
      DELETE FROM material_open_lots WHERE lot_id = v_lot_id;

      UPDATE material_stock SET balance_pieces = balance_pieces - v_lot_balance WHERE product_id = v_product_id;
      DELETE FROM material_stock WHERE product_id = v_product_id AND balance_pieces = 0;

      INSERT INTO material_ledger (product_id, lot_id, transaction_no, date_transaction, qty_change_pieces, balance_after_pcs)
      SELECT v_product_id, v_lot_id, v_txn_no, v_txn_date, -v_lot_balance,
             COALESCE((SELECT balance_pieces FROM material_stock WHERE product_id = v_product_id), 0);
    ELSE
      -- consume partially
      UPDATE material_lots SET balance_pieces = balance_pieces - v_qty_needed WHERE id = v_lot_id;
      UPDATE material_open_lots SET balance_pieces = balance_pieces - v_qty_needed WHERE lot_id = v_lot_id;
      UPDATE material_stock SET balance_pieces = balance_pieces - v_qty_needed WHERE product_id = v_product_id;

      INSERT INTO material_ledger (product_id, lot_id, transaction_no, date_transaction, qty_change_pieces, balance_after_pcs)
      SELECT v_product_id, v_lot_id, v_txn_no, v_txn_date, -v_qty_needed,
             COALESCE((SELECT balance_pieces FROM material_stock WHERE product_id = v_product_id), 0);

      SET v_qty_needed = 0;
    END IF;
  END WHILE;
END $$
DELIMITER ;

-- ========================================
-- Triggers to automate packing and stock movements
-- ========================================

DELIMITER $$
DROP TRIGGER IF EXISTS bi_mtd_compute_pack $$
CREATE TRIGGER bi_mtd_compute_pack
BEFORE INSERT ON material_transaction_details
FOR EACH ROW
BEGIN
  IF NEW.packing_per_pack IS NULL OR NEW.packing_per_pack <= 0 THEN
    SET NEW.packing_per_pack = COALESCE((SELECT m.packing FROM material m WHERE m.product_id = NEW.product_id), 1);
  END IF;
  SET NEW.packs_derived = FLOOR(NEW.qty_pieces / NEW.packing_per_pack);
  SET NEW.remainder_pieces = MOD(NEW.qty_pieces, NEW.packing_per_pack);
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS ai_mtd_handle_inout $$
CREATE TRIGGER ai_mtd_handle_inout
AFTER INSERT ON material_transaction_details
FOR EACH ROW
BEGIN
  DECLARE v_type ENUM('IN','OUT');
  DECLARE v_now_stock INT;
  DECLARE v_lot_id INT;
  DECLARE v_txn_date DATE;

  SELECT t.transaction_type, t.date_transaction INTO v_type, v_txn_date
  FROM material_transactions t WHERE t.transaction_no = NEW.transaction_no;

  IF v_type = 'IN' THEN
    -- create lot
    INSERT INTO material_lots (product_id, transaction_no, detail_id, packing_per_pack, received_pieces, received_packs, received_remainder, balance_pieces)
    VALUES (NEW.product_id, NEW.transaction_no, NEW.id, NEW.packing_per_pack, NEW.qty_pieces, NEW.packs_derived, NEW.remainder_pieces, NEW.qty_pieces);
    SET v_lot_id = LAST_INSERT_ID();

    -- link back lot_id (optional)
    UPDATE material_transaction_details SET lot_id = v_lot_id WHERE id = NEW.id;

    -- open lot entry
    INSERT INTO material_open_lots (lot_id, product_id, balance_pieces)
    VALUES (v_lot_id, NEW.product_id, NEW.qty_pieces);

    -- upsert stock
    INSERT INTO material_stock (product_id, balance_pieces)
    VALUES (NEW.product_id, NEW.qty_pieces)
    ON DUPLICATE KEY UPDATE balance_pieces = balance_pieces + VALUES(balance_pieces);

    -- ledger (+)
    SET v_now_stock = (SELECT balance_pieces FROM material_stock WHERE product_id = NEW.product_id);
    INSERT INTO material_ledger (product_id, lot_id, transaction_no, date_transaction, qty_change_pieces, balance_after_pcs)
    VALUES (NEW.product_id, v_lot_id, NEW.transaction_no, v_txn_date, NEW.qty_pieces, v_now_stock);

  ELSEIF v_type = 'OUT' THEN
    -- consume FIFO by detail id
    CALL sp_material_issue_fifo(NEW.id);
  END IF;
END $$
DELIMITER ;