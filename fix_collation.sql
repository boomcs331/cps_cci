-- แก้ไข collation ให้ตรงกันทุกตาราง
USE cps_cci;

-- แก้ไข material_transactions
ALTER TABLE material_transactions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- แก้ไข material_stock  
ALTER TABLE material_stock CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- แก้ไข material_transactions_detail
ALTER TABLE material_transactions_detail CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ตรวจสอบ collation ของแต่ละตาราง
SELECT TABLE_NAME, TABLE_COLLATION 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'cps_cci' 
AND TABLE_NAME IN ('material', 'material_stock', 'material_transactions', 'material_transactions_detail');