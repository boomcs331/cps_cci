-- แก้ไข collation ที่ระดับ column
USE cps_cci;

-- แก้ไข material_transactions
ALTER TABLE material_transactions 
MODIFY COLUMN product_id varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- แก้ไข material_stock
ALTER TABLE material_stock 
MODIFY COLUMN product_id varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- แก้ไข material_transactions_detail
ALTER TABLE material_transactions_detail 
MODIFY COLUMN product_id varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

-- ตรวจสอบ collation ของ columns
SELECT TABLE_NAME, COLUMN_NAME, COLLATION_NAME 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'cps_cci' 
AND COLUMN_NAME = 'product_id';