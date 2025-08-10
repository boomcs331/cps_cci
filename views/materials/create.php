<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-plus me-2"></i>เพิ่มวัสดุใหม่</h3>
                </div>
                <div class="card-body">
                    <!-- Error Messages -->
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>materials/store">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mat_id" class="form-label">รหัสวัสดุ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mat_id" name="mat_id" 
                                           value="<?= isset($_POST['mat_id']) ? htmlspecialchars($_POST['mat_id']) : '' ?>" 
                                           required>
                                    <div class="form-text">รหัสวัสดุต้องไม่ซ้ำกับที่มีอยู่ในระบบ</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mat_name" class="form-label">ชื่อวัสดุ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mat_name" name="mat_name" 
                                           value="<?= isset($_POST['mat_name']) ? htmlspecialchars($_POST['mat_name']) : '' ?>" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lr" class="form-label">LR</label>
                                    <input type="text" class="form-control" id="lr" name="lr" 
                                           value="<?= isset($_POST['lr']) ? htmlspecialchars($_POST['lr']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_qty" class="form-label">จำนวนขั้นต่ำ</label>
                                    <input type="number" class="form-control" id="min_qty" name="min_qty" 
                                           value="<?= isset($_POST['min_qty']) ? htmlspecialchars($_POST['min_qty']) : '0' ?>" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="packing" class="form-label">บรรจุภัณฑ์</label>
                                    <input type="text" class="form-control" id="packing" name="packing" 
                                           value="<?= isset($_POST['packing']) ? htmlspecialchars($_POST['packing']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier" class="form-label">ซัพพลายเออร์</label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" 
                                           value="<?= isset($_POST['supplier']) ? htmlspecialchars($_POST['supplier']) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">ตำแหน่งที่เก็บ</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="<?= isset($_POST['location']) ? htmlspecialchars($_POST['location']) : '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="img" class="form-label">รูปภาพ</label>
                                    <input type="text" class="form-control" id="img" name="img" 
                                           value="<?= isset($_POST['img']) ? htmlspecialchars($_POST['img']) : '' ?>">
                                    <div class="form-text">ชื่อไฟล์รูปภาพ (เช่น: material.jpg)</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>materials" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 