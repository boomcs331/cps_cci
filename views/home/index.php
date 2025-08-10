<?php
// เริ่มต้น output buffering
ob_start();

// แสดงข้อผิดพลาดถ้ามี
if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>คุณไม่มีสิทธิ์เข้าถึงหน้านั้น กรุณาติดต่อผู้ดูแลระบบ
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-rocket me-3"></i><?= $message ?>
                </h1>
                <p class="lead mb-4">
                    เฟรมเวิร์ค PHP MVC ที่พัฒนาขึ้นเพื่อการเรียนรู้และใช้งานจริง
                    พร้อมระบบ Router ที่ใช้งานง่าย และโครงสร้างที่เข้าใจง่าย
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= BASE_URL ?>users" class="btn btn-light btn-lg">
                        <i class="fas fa-users me-2"></i>ดูผู้ใช้
                    </a>
                    <a href="<?= BASE_URL ?>about" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i>เรียนรู้เพิ่มเติม
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-code" style="font-size: 200px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold mb-3">คุณสมบัติหลัก</h2>
                <p class="text-muted">เฟรมเวิร์คที่ออกแบบมาเพื่อความง่ายในการใช้งาน</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-route text-primary mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Router System</h5>
                        <p class="card-text">ระบบ Router ที่ใช้งานง่าย พร้อมการจัดการ URL ที่ยืดหยุ่น</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-database text-primary mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Database ORM</h5>
                        <p class="card-text">ระบบจัดการฐานข้อมูลที่ใช้งานง่าย พร้อม CRUD operations</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-paint-brush text-primary mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Modern UI</h5>
                        <p class="card-text">อินเทอร์เฟซที่ทันสมัย ใช้งานง่าย พร้อม Bootstrap 5</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Start Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-4">เริ่มต้นใช้งาน</h2>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">โครงสร้างไฟล์</h5>
                        <pre class="text-start bg-dark text-light p-3 rounded"><code>php-mvc/
├── config/
│   └── config.php
├── core/
│   ├── Router.php
│   ├── Controller.php
│   └── Model.php
├── controllers/
│   ├── HomeController.php
│   └── UserController.php
├── models/
│   └── UserModel.php
├── views/
│   ├── layouts/
│   │   └── main.php
│   ├── home/
│   │   ├── index.php
│   │   ├── about.php
│   │   └── contact.php
│   └── users/
│       ├── index.php
│       ├── create.php
│       ├── show.php
│       └── edit.php
├── index.php
├── .htaccess
└── setup.php</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/main.php';
?>