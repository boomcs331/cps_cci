<?php
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="fw-bold mb-4">
                <i class="fas fa-info-circle me-2"></i>เกี่ยวกับเรา
            </h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">PHP MVC Framework</h5>
                    <p class="card-text">
                        เฟรมเวิร์ค PHP MVC ที่พัฒนาขึ้นเพื่อการเรียนรู้และใช้งานจริง 
                        ออกแบบมาให้เข้าใจง่าย มีโครงสร้างที่ชัดเจน และใช้งานได้จริง
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-cogs text-primary me-2"></i>คุณสมบัติ
                            </h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Router System</li>
                                <li><i class="fas fa-check text-success me-2"></i>Database ORM</li>
                                <li><i class="fas fa-check text-success me-2"></i>Modern UI</li>
                                <li><i class="fas fa-check text-success me-2"></i>CRUD Operations</li>
                                <li><i class="fas fa-check text-success me-2"></i>Form Validation</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-code text-primary me-2"></i>เทคโนโลยี
                            </h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>PHP 7.4+</li>
                                <li><i class="fas fa-check text-success me-2"></i>MySQL/MariaDB</li>
                                <li><i class="fas fa-check text-success me-2"></i>Bootstrap 5</li>
                                <li><i class="fas fa-check text-success me-2"></i>Font Awesome</li>
                                <li><i class="fas fa-check text-success me-2"></i>jQuery</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">เริ่มต้นใช้งาน</h5>
                    <p class="card-text">
                        เฟรมเวิร์คนี้เหมาะสำหรับผู้ที่ต้องการเรียนรู้ PHP MVC Pattern 
                        และต้องการโครงสร้างที่เข้าใจง่ายสำหรับการพัฒนาเว็บแอปพลิเคชัน
                    </p>
                    <a href="<?= BASE_URL ?>users" class="btn btn-primary">
                        <i class="fas fa-play me-2"></i>เริ่มต้นใช้งาน
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/main.php';
?> 