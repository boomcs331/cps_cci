# การใช้งาน QR Code ที่ถูกต้องตามมาตรฐาน ISO 18004

## ภาพรวม
ระบบได้ติดตั้ง lib phpqrcode ที่แท้จริงและถูกต้องตามมาตรฐาน ISO 18004 สำหรับการสร้าง QR Code ที่มีคุณภาพสูงและสามารถอ่านได้ด้วยเครื่องสแกน QR Code ทั่วไป

## มาตรฐาน ISO 18004
- **ISO 18004:2015** - มาตรฐานสากลสำหรับ QR Code
- **Encoding modes**: NUM, AN, 8-bit, Kanji
- **Error correction levels**: L (7%), M (15%), Q (25%), H (30%)
- **Version support**: 1-40 (ขนาด 21x21 ถึง 177x177 modules)
- **Data capacity**: สูงสุด 2,953 bytes (version 40, level L)

## ไฟล์ที่เกี่ยวข้อง

### 1. qr_generator.php
ไฟล์หลักสำหรับสร้าง QR Code ที่ถูกต้องตามมาตรฐาน ISO
- ใช้ lib phpqrcode ที่แท้จริงจาก SourceForge
- รองรับพารามิเตอร์ตามมาตรฐาน: `data`, `size`, `margin`, `level`
- สร้าง QR Code ที่สามารถอ่านได้ด้วยเครื่องสแกนทั่วไป

### 2. lib/phpqrcode_real/
ไลบรารี phpqrcode ที่แท้จริงและถูกต้องตามมาตรฐาน
- **qrlib.php** - ไฟล์หลักที่รวมทุกฟังก์ชัน
- **qrencode.php** - ฟังก์ชันการเข้ารหัส QR Code
- **qrimage.php** - การสร้างรูปภาพ PNG
- **qrconst.php** - ค่าคงที่ตามมาตรฐาน

### 3. views/material_transactions/index.php
หน้าแสดงรายการธุรกรรมวัสดุ
- แสดงรูป QR Code ที่ถูกต้องตามมาตรฐาน ISO
- รูป QR Code มีขนาด 60px และแสดงรหัสด้านล่าง

## การใช้งาน

### การแสดง QR Code ในตาราง
```php
<img src="<?= BASE_URL ?>qr_generator.php?data=<?= urlencode($transaction['qr_code']) ?>" 
     alt="QR Code: <?= htmlspecialchars($transaction['qr_code']) ?>" 
     class="img-fluid" 
     style="max-width: 60px; height: auto;"
     title="<?= htmlspecialchars($transaction['qr_code']) ?>">
```

### พารามิเตอร์ของ QR Code (ตามมาตรฐาน ISO)
- `data` - ข้อมูลที่ต้องการเข้ารหัส (จำเป็น)
- `size` - ขนาด QR Code (1-10, ค่าเริ่มต้น: 4)
- `margin` - ขอบรอบ QR Code (0-10, ค่าเริ่มต้น: 4)
- `level` - ระดับการแก้ไขข้อผิดพลาด (L, M, Q, H, ค่าเริ่มต้น: L)

### ตัวอย่าง URL
```
qr_generator.php?data=MAT001&size=6&margin=4&level=H
```

## คุณสมบัติตามมาตรฐาน ISO

### 1. Error Correction Levels
- **L (Low)**: 7% - เหมาะสำหรับข้อมูลที่ไม่ต้องการความแม่นยำสูง
- **M (Medium)**: 15% - มาตรฐานทั่วไป
- **Q (Quartile)**: 25% - สำหรับข้อมูลที่ต้องการความแม่นยำสูง
- **H (High)**: 30% - สำหรับข้อมูลที่ต้องการความแม่นยำสูงสุด

### 2. Encoding Modes
- **Numeric**: ตัวเลข 0-9 (10 bits per 3 digits)
- **Alphanumeric**: ตัวเลขและตัวอักษร A-Z (11 bits per 2 characters)
- **8-bit**: ข้อมูลไบนารีทั่วไป (8 bits per character)
- **Kanji**: ตัวอักษรญี่ปุ่น (13 bits per character)

### 3. Version Support
- **Version 1**: 21x21 modules
- **Version 40**: 177x177 modules
- **Auto-versioning**: เลือกเวอร์ชันที่เหมาะสมอัตโนมัติ

## การทดสอบ

### ไฟล์ตัวอย่าง
- `qr_example.php` - หน้าแสดงตัวอย่างการใช้งาน QR Code
- แสดง QR Code หลายรูปแบบและขนาด
- มีฟีเจอร์ดาวน์โหลด QR Code

### การทดสอบในระบบ
1. เข้าหน้า material-transactions
2. ดูคอลัมน์ "รหัส QR" ในตาราง
3. ควรเห็นรูป QR Code ที่ถูกต้องตามมาตรฐาน ISO

### การทดสอบด้วยเครื่องสแกน
1. ใช้แอปสแกน QR Code บนมือถือ
2. สแกน QR Code ที่สร้างขึ้น
3. ควรอ่านข้อมูลได้ถูกต้อง

## หมายเหตุสำคัญ

1. **มาตรฐาน ISO**: QR Code ที่สร้างขึ้นถูกต้องตามมาตรฐาน ISO 18004:2015
2. **การเข้ารหัส**: ใช้ lib phpqrcode ที่พัฒนาจาก libqrencode C library
3. **การแคช**: QR Code มีการแคช 24 ชั่วโมงเพื่อประสิทธิภาพ
4. **ความปลอดภัย**: ข้อมูลที่ส่งผ่าน URL จะถูก encode เพื่อความปลอดภัย
5. **คุณภาพ**: QR Code มีคุณภาพสูงและสามารถอ่านได้ด้วยเครื่องสแกนทั่วไป

## การปรับแต่งเพิ่มเติม

### เปลี่ยนขนาด QR Code
แก้ไขใน `views/material_transactions/index.php`:
```php
style="max-width: 80px; height: auto;"  // เปลี่ยนจาก 60px เป็น 80px
```

### เพิ่มฟีเจอร์ดาวน์โหลด
เพิ่มปุ่มดาวน์โหลดในตาราง:
```php
<button class="btn btn-sm btn-primary" onclick="downloadQR('<?= $transaction['qr_code'] ?>')">
    <i class="fas fa-download"></i> ดาวน์โหลด
</button>
```

### เปลี่ยนระดับการแก้ไขข้อผิดพลาด
ปรับใน `qr_generator.php`:
```php
$level = isset($_GET['level']) ? $_GET['level'] : 'H';  // เปลี่ยนจาก 'L' เป็น 'H'
```

## การเปรียบเทียบกับเวอร์ชันเก่า

| คุณสมบัติ | เวอร์ชันเก่า | เวอร์ชันใหม่ (ISO) |
|-----------|-------------|-------------------|
| มาตรฐาน | ไม่เป็นมาตรฐาน | ISO 18004:2015 |
| คุณภาพ | ต่ำ | สูง |
| การอ่าน | อาจอ่านไม่ได้ | อ่านได้ด้วยเครื่องทั่วไป |
| Error Correction | ไม่มี | L, M, Q, H |
| Encoding Modes | จำกัด | NUM, AN, 8-bit, Kanji |
| Version Support | จำกัด | 1-40 |
| Data Capacity | จำกัด | สูงสุด 2,953 bytes |
