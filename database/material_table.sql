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

-- ตัวอย่างการเพิ่มข้อมูล
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
(
    'MAT001', 
    'สกรูสแตนเลส 4x20mm', 
    'กล่อง 100 ชิ้น', 
    'LH', 
    'บริษัท ABC จำกัด', 
    50, 
    '2024-12-31', 
    'pc', 
    'A1-B2-C3', 
    'images/materials/screw_stainless.jpg', 
    1
),
(
    'OFF001', 
    'กระดาษ A4 80gsm', 
    'รีม 500 แผ่น', 
    NULL, 
    'บริษัท XYZ จำกัด', 
    20, 
    '2024-11-30', 
    'of_mat', 
    'OFF-A1', 
    'images/office/paper_a4.jpg', 
    1
),
(
    'PC001', 
    'ฝาครอบพลาสติก', 
    'ถุง 10 ชิ้น', 
    'RH', 
    'บริษัท DEF จำกัด', 
    30, 
    '2024-10-15', 
    'pc', 
    'B2-C3-D4', 
    'images/parts/plastic_cover.jpg', 
    1
);
