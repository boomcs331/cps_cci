# การอัปเดตหน้า Stock - จำนวนขั้นต่ำ = min_qty × due

## การเปลี่ยนแปลงที่ทำ

### 1. เพิ่มฟิลด์ `due` ในฐานข้อมูล
- เพิ่มฟิลด์ `due` ในตาราง `materials` หลังฟิลด์ `min_qty`
- ค่าเริ่มต้นของ `due` = 2
- อัปเดตข้อมูลตัวอย่างให้มี `due = 2`

### 2. อัปเดตหน้า Stock
- **คอลัมน์ "จำนวนขั้นต่ำ"**: แสดง `min_qty × due` แทน `min_qty` เดิม
- **แสดงการคำนวณ**: แสดง `(min_qty × due)` ใต้จำนวนขั้นต่ำ
- **อัปเดต Stock Ratio**: คำนวณจาก `current_qty / (min_qty × due)` แทน `current_qty / min_qty`

### 3. อัปเดตตาราง Low Stock Details
- แสดงจำนวนขั้นต่ำเป็น `min_qty × due`
- คำนวณจำนวนที่ขาดจาก `(min_qty × due) - current_qty`
- อัปเดต Stock Ratio ให้ใช้ `min_qty × due`

### 4. อัปเดตตาราง Excess Stock Details
- แสดงจำนวนขั้นต่ำเป็น `min_qty × due`
- คำนวณจำนวนที่เกินจาก `current_qty - (min_qty × due)`
- อัปเดต Stock Ratio ให้ใช้ `min_qty × due`

### 5. อัปเดต Model
- เพิ่มฟิลด์ `due` ใน `getAllCurrentStockFromTransactionsPaginated()`
- เพิ่มฟิลด์ `due` ใน `getLowStockMaterials()`
- เพิ่มฟิลด์ `due` ใน `getExcessStockMaterials()`

## ไฟล์ที่เปลี่ยนแปลง

### ฐานข้อมูล
- `database/schema.sql` - เพิ่มฟิลด์ `due` ในตาราง `materials`
- `database/update_materials_due.sql` - สคริปต์สำหรับอัปเดตฐานข้อมูลที่มีอยู่

### View
- `views/material_transactions/stock.php` - อัปเดตการแสดงผลจำนวนขั้นต่ำ

### Model
- `models/MaterialStockModel.php` - เพิ่มฟิลด์ `due` ใน queries

## วิธีการใช้งาน

### สำหรับฐานข้อมูลใหม่
1. รันไฟล์ `database/schema.sql` เพื่อสร้างฐานข้อมูลใหม่

### สำหรับฐานข้อมูลที่มีอยู่
1. รันไฟล์ `database/update_materials_due.sql` เพื่อเพิ่มฟิลด์ `due`
2. อัปเดตค่า `due` ในตาราง `materials` ตามต้องการ (ค่าเริ่มต้น = 2)

## ตัวอย่างการแสดงผล

### ก่อนการเปลี่ยนแปลง
```
จำนวนขั้นต่ำ: 10
```

### หลังการเปลี่ยนแปลง
```
จำนวนขั้นต่ำ: 20
(10 × 2)
```

## หมายเหตุ
- ฟิลด์ `due` มีค่าเริ่มต้นเป็น 2
- หากไม่มีฟิลด์ `due` ในฐานข้อมูล ระบบจะใช้ค่า 2 เป็นค่าเริ่มต้น
- การคำนวณ Stock Ratio และการจัดหมวดหมู่สต็อกจะใช้ `min_qty × due` แทน `min_qty` เดิม
