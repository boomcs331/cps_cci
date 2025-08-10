<?php
$title = "แก้ไขข้อมูลวัสดุ";
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>แก้ไขข้อมูลวัสดุ
                    </h4>
                    <a href="<?= BASE_URL ?>pc/material" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>กลับไปหน้าหลัก
                    </a>
                </div>
                <div class="card-body">
                    <!-- แสดงข้อผิดพลาด -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>pc/editMaterial/<?= $material['mat_id'] ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mat_id" class="form-label">
                                        รหัสวัสดุ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="mat_id" name="mat_id" 
                                           value="<?= htmlspecialchars($material['mat_id']) ?>" 
                                           placeholder="เช่น MAT001" readonly>
                                    <div class="form-text">ไม่สามารถแก้ไขรหัสวัสดุได้</div>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        ชื่อวัสดุ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars(isset($data['name']) ? $data['name'] : $material['name']) ?>" 
                                           placeholder="ชื่อวัสดุ" required maxlength="50">
                                </div>

                                <div class="mb-3">
                                    <label for="LR" class="form-label">
                                        LR <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="LR" name="LR" 
                                           value="<?= htmlspecialchars(isset($data['LR']) ? $data['LR'] : $material['LR']) ?>" 
                                           placeholder="เช่น L01" required maxlength="3">
                                </div>

                                <div class="mb-3">
                                    <label for="unit" class="form-label">
                                        จำนวนหน่วย <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="unit" name="unit" 
                                           value="<?= htmlspecialchars(isset($data['unit']) ? $data['unit'] : $material['unit']) ?>" 
                                           step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min" class="form-label">
                                        จำนวนขั้นต่ำ <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="min" name="min" 
                                           value="<?= htmlspecialchars(isset($data['min']) ? $data['min'] : $material['min']) ?>" 
                                           min="0" required>
                                    <div class="form-text">จำนวนขั้นต่ำที่จะแจ้งเตือนเมื่อ stock ต่ำ</div>
                                </div>

                                <div class="mb-3">
                                    <label for="upk" class="form-label">
                                        ราคา (บาท) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="upk" name="upk" 
                                           value="<?= htmlspecialchars(isset($data['upk']) ? $data['upk'] : $material['upk']) ?>" 
                                           step="0.01" min="0" required>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">รูปภาพ</label>
                                    <input type="text" class="form-control" id="image" name="image" 
                                           value="<?= htmlspecialchars(isset($data['image']) ? $data['image'] : $material['image']) ?>" 
                                           placeholder="ชื่อไฟล์รูปภาพ" maxlength="100">
                                </div>

                                <div class="mb-3">
                                    <label for="supplier" class="form-label">ซัพพลายเออร์ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" 
                                           value="<?= htmlspecialchars(isset($data['supplier']) ? $data['supplier'] : $material['supplier']) ?>" 
                                           placeholder="ชื่อซัพพลายเออร์" required maxlength="30">
                                </div>

                                <div class="mb-3" style="display: none;">
                                    <label for="location" class="form-label">ตำแหน่งจัดเก็บ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="PC" 
                                           placeholder="เช่น A01" required maxlength="5">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= BASE_URL ?>pc/material" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-1"></i>อัปเดตข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validate form before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const unit = parseFloat(document.getElementById('unit').value);
    const min = parseFloat(document.getElementById('min').value);
    
    if (unit < 0) {
        alert('จำนวนหน่วยต้องไม่น้อยกว่า 0');
        e.preventDefault();
        return;
    }
    
    if (min < 0) {
        alert('จำนวนขั้นต่ำต้องไม่น้อยกว่า 0');
        e.preventDefault();
        return;
    }
});
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?> 