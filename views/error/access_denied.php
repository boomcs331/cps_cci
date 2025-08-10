<?php
$title = "ไม่มีสิทธิ์เข้าถึง";
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h3 class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>ไม่มีสิทธิ์เข้าถึง
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-3x text-danger mb-3"></i>
                        <h4>ขออภัย คุณไม่มีสิทธิ์เข้าถึงหน้านี้</h4>
                        <p class="text-muted">
                            หน้านี้ต้องการสิทธิ์พิเศษในการเข้าถึง<br>
                            กรุณาติดต่อผู้ดูแลระบบหากคุณคิดว่านี่เป็นข้อผิดพลาด
                        </p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>logout" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>กลับไปหน้าหลัก
                        </a>
                        <a href="<?= BASE_URL ?>logout" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-1"></i>ดูโปรไฟล์
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?> 