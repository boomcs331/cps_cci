<?php
$title = "โปรไฟล์";
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>โปรไฟล์
                    </h4>
                    <a href="<?= BASE_URL ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>กลับไปหน้าหลัก
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">User ID:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($user['user_id']) ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">ชื่อผู้ใช้:</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($user['user_name']) ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">ตำแหน่ง:</label>
                                <p class="form-control-plaintext">
                                    <?php if (!empty($user['position'])): ?>
                                        <span class="badge bg-primary"><?= htmlspecialchars($user['position']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">ไม่ระบุ</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">เวลาเข้าสู่ระบบ:</label>
                                <p class="form-control-plaintext">
                                    <?= $user['login_time'] ? date('d/m/Y H:i:s', $user['login_time']) : 'ไม่ระบุ' ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>ข้อมูลเพิ่มเติม
                                    </h6>
                                    <p class="card-text">
                                        คุณได้เข้าสู่ระบบในฐานะผู้ใช้ที่มีสิทธิ์ในการจัดการวัสดุ PC
                                    </p>
                                    <div class="d-grid gap-2">
                                        <a href="<?= BASE_URL ?>pc/material" class="btn btn-primary">
                                            <i class="fas fa-boxes me-1"></i>จัดการวัสดุ
                                        </a>
                                        <a href="<?= BASE_URL ?>users" class="btn btn-info">
                                            <i class="fas fa-users me-1"></i>จัดการผู้ใช้
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?> 