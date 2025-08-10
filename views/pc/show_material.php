<?php
$title = "รายละเอียดวัสดุ";
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>รายละเอียดวัสดุ
                    </h4>
                    <div>
                        <a href="<?= BASE_URL ?>pc/editMaterial/<?= $material['mat_id'] ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>แก้ไข
                        </a>
                        <a href="<?= BASE_URL ?>pc/material" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>กลับไปหน้าหลัก
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">รหัสวัสดุ</label>
                                        <div class="form-control-plaintext">
                                            <span class="badge bg-primary fs-6"><?= htmlspecialchars($material['mat_id']) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">LR</label>
                                        <div class="form-control-plaintext">
                                            <span class="badge bg-info fs-6"><?= htmlspecialchars($material['LR']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">ชื่อวัสดุ</label>
                                <div class="form-control-plaintext fs-5 fw-bold">
                                    <?= htmlspecialchars($material['name']) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">จำนวนหน่วย</label>
                                        <div class="form-control-plaintext">
                                            <?php 
                                            $stock_class = '';
                                            if ($material['unit'] <= $material['min']) {
                                                $stock_class = 'text-danger fw-bold';
                                            } elseif ($material['unit'] <= $material['min'] * 1.5) {
                                                $stock_class = 'text-warning fw-bold';
                                            }
                                            ?>
                                            <span class="<?= $stock_class ?> fs-5">
                                                <?= number_format($material['unit'], 2) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">จำนวนขั้นต่ำ</label>
                                        <div class="form-control-plaintext">
                                            <?= number_format($material['min'], 0) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">แพคเกจ</label>
                                        <div class="form-control-plaintext">
                                            <span class="text-success fw-bold fs-5">
                                                <?= number_format($material['upk']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">ซัพพลายเออร์</label>
                                        <div class="form-control-plaintext">
                                            <?= htmlspecialchars($material['supplier']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">ตำแหน่งจัดเก็บ</label>
                                        <div class="form-control-plaintext">
                                            <?= htmlspecialchars($material['location']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($material['image'])): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">รูปภาพ</label>
                                    <div class="form-control-plaintext">
                                        <?= htmlspecialchars($material['image']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>ข้อมูลเพิ่มเติม</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">วันที่สร้าง</label>
                                        <div class="form-control-plaintext">
                                            <?= date('d/m/Y H:i', strtotime($material['date_create'])) ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">สถานะ Stock</label>
                                        <div class="form-control-plaintext">
                                            <?php 
                                            if ($material['unit'] <= $material['min']) {
                                                echo '<span class="badge bg-danger">Stock ต่ำ</span>';
                                            } elseif ($material['unit'] <= $material['min'] * 1.5) {
                                                echo '<span class="badge bg-warning">Stock เตือน</span>';
                                            } else {
                                                echo '<span class="badge bg-success">Stock ปกติ</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">มูลค่าสินค้าคงเหลือ</label>
                                        <div class="form-control-plaintext">
                                            <span class="text-success fw-bold">
                                                ฿<?= number_format($material['unit'] * $material['upk'], 2) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- แจ้งเตือน Stock ต่ำ -->
                            <?php if ($material['unit'] <= $material['min']): ?>
                                <div class="alert alert-danger mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>แจ้งเตือน:</strong> Stock ต่ำกว่าจำนวนขั้นต่ำ
                                </div>
                            <?php elseif ($material['unit'] <= $material['min'] * 1.5): ?>
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>แจ้งเตือน:</strong> Stock ใกล้ถึงจำนวนขั้นต่ำ
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ปุ่มดำเนินการ -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="<?= BASE_URL ?>pc/material" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>กลับไปหน้าหลัก
                                </a>
                            </div>
                            <div>
                                <a href="<?= BASE_URL ?>pc/editMaterial/<?= $material['mat_id'] ?>" class="btn btn-warning me-2">
                                    <i class="fas fa-edit me-1"></i>แก้ไขข้อมูล
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete('<?= $material['mat_id'] ?>')">
                                    <i class="fas fa-trash me-1"></i>ลบวัสดุ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ยืนยันการลบ -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ยืนยันการลบ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>คุณต้องการลบวัสดุ "<?= htmlspecialchars($material['name']) ?>" หรือไม่?</p>
                <p class="text-danger"><strong>การดำเนินการนี้ไม่สามารถยกเลิกได้</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <a href="<?= BASE_URL ?>pc/deleteMaterial/<?= $material['mat_id'] ?>" class="btn btn-danger">ลบ</a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?> 
<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?> 