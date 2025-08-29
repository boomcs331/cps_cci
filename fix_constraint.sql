-- ลบ constraint ที่ป้องกันไม่ให้ balance เป็น 0
USE cps_cci;
ALTER TABLE material_stock DROP CONSTRAINT chk_positive_balance;