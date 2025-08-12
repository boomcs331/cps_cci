<?php 
require_once 'views/layouts/main.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-eye me-2"></i>รายละเอียดธุรกรรมวัสดุ</h3>
                    <div>
                        <a href="<?= BASE_URL ?>material-transactions" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>กลับ
                        </a>
                        <?php if ($transaction['transaction_type'] === 'IN'): ?>
                            <a href="<?= BASE_URL ?>material-transactions/create-in" class="btn btn-success me-2">
                                <i class="fas fa-plus me-2"></i>รับเข้าใหม่
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>material-transactions/create-out" class="btn btn-warning me-2">
                                <i class="fas fa-minus me-2"></i>จ่ายออกใหม่
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($transaction): ?>
                        <div class="row">
                            <!-- Transaction Details -->
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>ข้อมูลธุรกรรม
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">รหัสอ้างอิง:</label>
                                                <p class="form-control-plaintext"><?= htmlspecialchars($transaction['reference_no']) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">ประเภทธุรกรรม:</label>
                                                <p class="form-control-plaintext">
                                                    <?php if ($transaction['transaction_type'] === 'IN'): ?>
                                                        <span class="badge bg-success">รับเข้า</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">จ่ายออก</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">วัสดุ:</label>
                                                <p class="form-control-plaintext">
                                                    <?= htmlspecialchars($transaction['mat_id']) ?> - <?= htmlspecialchars($transaction['mat_name']) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">จำนวน:</label>
                                                <p class="form-control-plaintext">
                                                    <?= number_format($transaction['quantity']) ?> หน่วย
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">ราคาต่อหน่วย:</label>
                                                <p class="form-control-plaintext">
                                                    ฿<?= number_format($transaction['unit_price'], 2) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">ราคารวม:</label>
                                                <p class="form-control-plaintext">
                                                    ฿<?= number_format($transaction['total_amount'], 2) ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">วันที่ธุรกรรม:</label>
                                                <p class="form-control-plaintext">
                                                    <?= date('d/m/Y H:i', strtotime($transaction['transaction_date'])) ?>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">ผู้ดำเนินการ:</label>
                                                <p class="form-control-plaintext">
                                                    <?= htmlspecialchars($transaction['created_by_user'] ?? 'ไม่ระบุ') ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <?php if ($transaction['transaction_type'] === 'IN'): ?>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">ผู้จัดจำหน่าย:</label>
                                                    <p class="form-control-plaintext">
                                                        <?= htmlspecialchars($transaction['supplier'] ?? 'ไม่ระบุ') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">ผู้รับ:</label>
                                                    <p class="form-control-plaintext">
                                                        <?= htmlspecialchars($transaction['recipient'] ?? 'ไม่ระบุ') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold">รายละเอียด:</label>
                                                <p class="form-control-plaintext">
                                                    <?= htmlspecialchars($transaction['description']) ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($transaction['notes'])): ?>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">หมายเหตุ:</label>
                                                    <p class="form-control-plaintext">
                                                        <?= htmlspecialchars($transaction['notes']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- QR Code and Additional Info -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-qrcode me-2"></i>QR Code
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <?php if (!empty($transaction['qr_code'])): ?>
                                            <div class="mb-3">
                                                <img src="<?= BASE_URL ?>qr_generator.php?data=<?= urlencode($transaction['qr_code']) ?>" 
                                                     alt="QR Code: <?= htmlspecialchars($transaction['qr_code']) ?>" 
                                                     class="img-fluid" 
                                                     style="max-width: 200px; height: auto;"
                                                     title="<?= htmlspecialchars($transaction['qr_code']) ?>">
                                            </div>
                                            <p class="text-muted small">รหัสอ้างอิง: <?= htmlspecialchars($transaction['qr_code']) ?></p>
                                        <?php else: ?>
                                            <div class="text-muted">
                                                <i class="fas fa-qrcode fa-3x mb-2"></i>
                                                <p>ไม่มี QR Code</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-clock me-2"></i>ข้อมูลเวลา
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">สร้างเมื่อ:</small><br>
                                            <span><?= date('d/m/Y H:i:s', strtotime($transaction['transaction_date'])) ?></span>
                                        </div>
                                        <?php if (!empty($transaction['updated_date']) && $transaction['updated_date'] !== $transaction['transaction_date']): ?>
                                            <div>
                                                <small class="text-muted">อัปเดตล่าสุด:</small><br>
                                                <span><?= date('d/m/Y H:i:s', strtotime($transaction['updated_date'])) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <a href="<?= BASE_URL ?>material-transactions" class="btn btn-secondary me-2">
                                    <i class="fas fa-arrow-left me-2"></i>กลับไปรายการ
                                </a>
                                <a href="<?= BASE_URL ?>material-transactions/stock" class="btn btn-info me-2">
                                    <i class="fas fa-boxes me-2"></i>ดูสต็อก
                                </a>
                                <a href="<?= BASE_URL ?>material-transactions/report" class="btn btn-primary">
                                    <i class="fas fa-chart-bar me-2"></i>รายงาน
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ไม่พบข้อมูลธุรกรรมที่ต้องการ
                        </div>
                        <div class="text-center">
                            <a href="<?= BASE_URL ?>material-transactions" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับไปรายการ
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
