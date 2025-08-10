<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-minus me-2"></i>จ่ายออกวัสดุ</h3>
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

                    <form method="POST" action="<?= BASE_URL ?>material-transactions/store-out">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_no" class="form-label">รหัสอ้างอิง <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="reference_no" name="reference_no" 
                                           value="<?= htmlspecialchars($reference_no) ?>" required readonly>
                                    <div class="form-text">รหัสอ้างอิงจะถูกสร้างอัตโนมัติ</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="material_id" class="form-label">วัสดุ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="material_id" name="material_id" required>
                                        <option value="">เลือกวัสดุ</option>
                                        <?php foreach ($materials as $material): ?>
                                            <option value="<?= $material['id'] ?>" <?= isset($_POST['material_id']) && $_POST['material_id'] == $material['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($material['mat_id']) ?> - <?= htmlspecialchars($material['mat_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">จำนวน <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" 
                                           value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '' ?>" 
                                           min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="recipient" class="form-label">ผู้รับ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="recipient" name="recipient" 
                                           value="<?= isset($_POST['recipient']) ? htmlspecialchars($_POST['recipient']) : '' ?>" 
                                           required>
                                    <div class="form-text">เช่น Production Line A, Maintenance Dept</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">รายละเอียด <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"><?= isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '' ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>material-transactions" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับ
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>บันทึกการจ่ายออก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 