<?php
$title = "ผลิตสินค้า";
require_once VIEWS_PATH . 'layouts/header.php';
?>

<style>
    /* Modern UI Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --warning-gradient: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        --info-gradient: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        --dark-gradient: linear-gradient(135deg, #343a40 0%, #495057 100%);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .main-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        margin: 20px;
        overflow: hidden;
    }

    .hero-section {
        background: var(--primary-gradient);
        color: white;
        padding: 3rem 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .scan-container {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        margin: 2rem 0;
        border: 2px solid transparent;
        background-clip: padding-box;
        position: relative;
    }

    .scan-container::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: var(--primary-gradient);
        border-radius: 20px;
        z-index: -1;
    }

    .barcode-input {
        border: none;
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 1.2rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 500;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .barcode-input:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25), inset 0 2px 4px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .scan-button {
        background: var(--primary-gradient);
        border: none;
        border-radius: 15px;
        padding: 1.2rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .scan-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    }

    .product-info-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 2rem 0;
        border: none;
    }

    .product-header {
        background: var(--success-gradient);
        color: white;
        padding: 1.5rem 2rem;
        position: relative;
    }

    .product-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #28a745, #20c997, #28a745);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .info-badge {
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 2px solid transparent;
        background-clip: padding-box;
        position: relative;
    }

    .info-badge::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: var(--primary-gradient);
        border-radius: 25px;
        z-index: -1;
        opacity: 0.1;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .confirm-btn {
        background: var(--success-gradient);
        border: none;
        border-radius: 25px;
        padding: 1rem 2.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }

    .confirm-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
    }

    .cancel-btn {
        background: var(--dark-gradient);
        border: none;
        border-radius: 25px;
        padding: 1rem 2.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(52, 58, 64, 0.3);
    }

    .cancel-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(52, 58, 64, 0.4);
    }

    .guide-card {
        background: linear-gradient(145deg, #e3f2fd, #f3e5f5);
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .guide-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .guide-icon {
        font-size: 3.5rem;
        color: #667eea;
        opacity: 0.8;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .stats-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .stats-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .stats-label {
        font-size: 0.9rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .alert-custom {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-container {
            margin: 10px;
        }

        .hero-section {
            padding: 2rem 0;
        }

        .action-buttons {
            flex-direction: column;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
    }
</style>

<div class="main-container animate-fade-in">
    <!-- Hero Section -->
    <!-- <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="fas fa-industry me-3"></i>ระบบผลิตสินค้า
                        </h1>
                        <p class="lead mb-0 opacity-90">
                            สแกนบาร์โค้ดเพื่อเริ่มต้นกระบวนการผลิตอย่างมีประสิทธิภาพ
                        </p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <i class="fas fa-barcode fa-5x pulse-animation" style="opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="container-fluid p-4">
        <!-- แสดงข้อความสำเร็จ/ข้อผิดพลาด -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'produced'): ?>
            <div class="alert alert-success alert-dismissible fade show alert-custom animate-slide-up" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>ยืนยันการผลิตสำเร็จ!</strong>
                <?php if (isset($_GET['message'])): ?>
                    <br><small><?= nl2br(htmlspecialchars(urldecode($_GET['message']))) ?></small>
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'production_failed'): ?>
            <div class="alert alert-danger alert-dismissible fade show alert-custom animate-slide-up" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>เกิดข้อผิดพลาดในการผลิต!</strong>
                <?php if (isset($_GET['message'])): ?>
                    <br><small><?= htmlspecialchars(urldecode($_GET['message'])) ?></small>
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show alert-custom animate-slide-up" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show alert-custom animate-slide-up" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- ฟอร์มสแกนบาร์โค้ด -->
        <div class="scan-container animate-slide-up">
            <form method="POST" action="<?= BASE_URL ?>pc/produce_check">
                <div class="row g-3 align-items-end">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="add_produce_barcode" class="form-label fw-bold text-primary mb-2">
                                <i class="fas fa-barcode me-2"></i>สแกนบาร์โค้ด
                            </label>
                            <input type="text" name="add_produce_barcode" id="add_produce_barcode"
                                class="form-control barcode-input" placeholder="กรุณายิงบาร์โค้ดของสินค้าที่ต้องการผลิต"
                                value="<?= htmlspecialchars(isset($barcode) ? $barcode : '') ?>" autocomplete="off"
                                autofocus>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn scan-button w-100">
                            <i class="fas fa-search me-2"></i>ตรวจสอบ
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (isset($data['bom_data']) && $data['bom_data']): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="text-primary mb-3 fw-bold">
                        <i class="fas fa-list-alt me-2"></i>ข้อมูล BOM (Bill of Materials)
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>ลำดับ</th>
                                    <th><i class="fas fa-box me-2"></i>Material ID</th>
                                    <th><i class="fas fa-tag me-2"></i>ชื่อวัสดุ</th>
                                    <th><i class="fas fa-calculator me-2"></i>จำนวนที่ใช้</th>
                                    <th><i class="fas fa-warehouse me-2"></i>คงเหลือ</th>
                                    <th><i class="fas fa-shopping-cart me-2"></i>ต้องการ</th>
                                    <th><i class="fas fa-calculator me-2"></i>ผลต่าง</th>
                                    <th><i class="fas fa-clock me-2"></i>FIFO</th>
                                    <th><i class="fas fa-check-circle me-2"></i>สถานะ</th>
                                    <th><i class="fas fa-info-circle me-2"></i>รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // ตรวจสอบว่า bom_data เป็น array หรือไม่
                                $bom_items = is_array($data['bom_data']) ? $data['bom_data'] : array($data['bom_data']);
                                $counter = 1;
                                foreach ($bom_items as $bom_item):
                                    ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><?= $counter ?></span></td>
                                        <td><strong><?= htmlspecialchars(isset($bom_item['mat_id']) ? $bom_item['mat_id'] : 'N/A') ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars(isset($bom_item['name']) ? $bom_item['name'] : 'N/A') ?></td>
                                        <td><span
                                                class="badge bg-info"><?= htmlspecialchars(isset($bom_item['unit']) ? $bom_item['unit'] : 'N/A') ?></span>
                                        </td>
                                        <td><span
                                                class="badge bg-warning"><?= htmlspecialchars(isset($bom_item['balance_material']) ? $bom_item['balance_material'] : 'N/A') ?></span>
                                        </td>
                                        <td><span
                                                class="badge bg-info"><?= htmlspecialchars(isset($bom_item['product_request']) ? $bom_item['product_request'] : 'N/A') ?></span>
                                        </td>
                                                                                            <td>
                                                        <?php if (isset($bom_item['diff'])): ?>
                                                            <?php if ($bom_item['diff'] < 0): ?>
                                                                <span class="badge bg-danger">
                                                                    <i class="fas fa-minus me-1"></i><?= htmlspecialchars($bom_item['diff']) ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-plus me-1"></i><?= htmlspecialchars($bom_item['diff']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">N/A</span>
                                                                                                                    <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($bom_item['fifo_details']) && !empty($bom_item['fifo_details'])): ?>
                                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#fifoModal<?= $counter ?>">
                                                                    <i class="fas fa-clock me-1"></i>ดู FIFO
                                                                </button>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">ไม่มีข้อมูล</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($bom_item['stock_status']) && $bom_item['stock_status'] == 'no stock'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>No Stock
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Available
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#bomDetailModal<?= $counter ?>">
                                                <i class="fas fa-eye me-1"></i>ดูรายละเอียด
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                    $counter++;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- แสดงข้อมูลการผลิต -->
        <?php if (isset($data) && $data && isset($data['product_id'])): ?>
            <div class="product-info-card animate-slide-up">
                <div class="product-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>ข้อมูลการผลิต
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3 fw-bold">
                                <i class="fas fa-barcode me-2"></i>ข้อมูลบาร์โค้ด
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Product ID:</strong></td>
                                    <td><span
                                            class="badge info-badge bg-primary"><?= htmlspecialchars($data['product_id']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Unit:</strong></td>
                                    <td><span class="badge info-badge bg-info"><?= htmlspecialchars($data['unit']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>KB ID:</strong></td>
                                    <td><span
                                            class="badge info-badge bg-secondary"><?= htmlspecialchars($data['kb_id']) ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3 fw-bold">
                                <i class="fas fa-cogs me-2"></i>สถานะการผลิต
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Workflow:</strong></td>
                                    <td><span class="badge info-badge bg-success">พร้อมผลิต</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Kanban Status:</strong></td>
                                    <td><span class="badge info-badge bg-warning">รอผลิต</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Position:</strong></td>
                                    <td><span class="badge info-badge bg-info">PC</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- แสดงข้อมูล BOM -->


                    <!-- ปุ่มยืนยันการผลิต -->
                    <div class="action-buttons mt-4">
                        <form method="POST" action="<?= BASE_URL ?>pc/confirm_produce" class="d-inline">
                            <input type="hidden" name="barcode" value="<?= htmlspecialchars($barcode) ?>">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($data['product_id']) ?>">
                            <input type="hidden" name="kb_id" value="<?= htmlspecialchars($data['kb_id']) ?>">
                            <button type="submit" class="btn confirm-btn">
                                <i class="fas fa-check me-2"></i>ยืนยันการผลิต
                            </button>
                        </form>
                        <a href="<?= BASE_URL ?>pc/produce" class="btn cancel-btn">
                            <i class="fas fa-times me-2"></i>ยกเลิก
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- คำแนะนำการใช้งาน -->
        <?php if (!isset($data)): ?>
            <div class="guide-card animate-slide-up">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <i class="fas fa-lightbulb guide-icon"></i>
                        </div>
                        <div class="col-md-10">
                            <h6 class="mb-3 fw-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>คำแนะนำการใช้งาน
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li class="mb-2">ยิงบาร์โค้ดของสินค้าที่ต้องการผลิต</li>
                                        <li class="mb-2">ระบบจะตรวจสอบข้อมูลและแสดงผลลัพธ์</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0">
                                        <li class="mb-2">กดปุ่ม "ยืนยันการผลิต" เพื่อบันทึกการผลิต</li>
                                        <li class="mb-2">รูปแบบบาร์โค้ด: <code
                                                class="bg-light px-2 py-1 rounded">ProductID/Unit/KB_ID</code></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- สถิติการผลิต -->
        <!-- <div class="stats-grid">
            <div class="stats-card bg-primary text-white">
                <i class="fas fa-industry stats-icon"></i>
                <div class="stats-number">0</div>
                <div class="stats-label">วันนี้</div>
                <small>รายการ</small>
            </div>
            <div class="stats-card bg-success text-white">
                <i class="fas fa-check-circle stats-icon"></i>
                <div class="stats-number">0</div>
                <div class="stats-label">เสร็จสิ้น</div>
                <small>รายการ</small>
            </div>
            <div class="stats-card bg-warning text-white">
                <i class="fas fa-clock stats-icon"></i>
                <div class="stats-number">0</div>
                <div class="stats-label">รอดำเนินการ</div>
                <small>รายการ</small>
            </div>
            <div class="stats-card bg-info text-white">
                <i class="fas fa-chart-line stats-icon"></i>
                <div class="stats-number">0%</div>
                <div class="stats-label">ประสิทธิภาพ</div>
                <small>เฉลี่ย</small>
            </div>
        </div> -->
    </div>
</div>

<!-- Modal สำหรับแสดงรายละเอียด BOM -->
<?php if (isset($data['bom_data']) && $data['bom_data']): ?>
    <?php
    // ตรวจสอบว่า bom_data เป็น array หรือไม่
    $bom_items = is_array($data['bom_data']) ? $data['bom_data'] : array($data['bom_data']);
    $counter = 1;
    foreach ($bom_items as $bom_item):
        ?>
        <div class="modal fade" id="bomDetailModal<?= $counter ?>" tabindex="-1"
            aria-labelledby="bomDetailModalLabel<?= $counter ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="bomDetailModalLabel<?= $counter ?>">
                            <i class="fas fa-list-alt me-2"></i>รายละเอียด BOM - รายการที่ <?= $counter ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>ข้อมูลวัสดุ
                                </h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Material ID:</strong></td>
                                        <td><?= htmlspecialchars(isset($bom_item['mat_id']) ? $bom_item['mat_id'] : 'N/A') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>ชื่อวัสดุ:</strong></td>
                                        <td><?= htmlspecialchars(isset($bom_item['name']) ? $bom_item['name'] : 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>จำนวนที่ใช้:</strong></td>
                                        <td><span
                                                class="badge bg-info"><?= htmlspecialchars(isset($bom_item['unit']) ? $bom_item['unit'] : 'N/A') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>คงเหลือ:</strong></td>
                                        <td><span
                                                class="badge bg-warning"><?= htmlspecialchars(isset($bom_item['balance_material']) ? $bom_item['balance_material'] : 'N/A') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>ต้องการ:</strong></td>
                                        <td><span
                                                class="badge bg-info"><?= htmlspecialchars(isset($bom_item['product_request']) ? $bom_item['product_request'] : 'N/A') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>ผลต่าง:</strong></td>
                                        <td>
                                            <?php if (isset($bom_item['diff'])): ?>
                                                <?php if ($bom_item['diff'] < 0): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-minus me-1"></i><?= htmlspecialchars($bom_item['diff']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-plus me-1"></i><?= htmlspecialchars($bom_item['diff']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if (isset($bom_item['fifo_details']) && !empty($bom_item['fifo_details'])): ?>
                                    <tr>
                                        <td><strong>รายละเอียด FIFO:</strong></td>
                                        <td>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>วันที่เข้า</th>
                                                            <th>คงเหลือเดิม</th>
                                                            <th>จะตัดออก</th>
                                                            <th>คงเหลือหลังตัด</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($bom_item['fifo_details'] as $fifo_detail): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($fifo_detail['date_in']) ?></td>
                                                            <td><span class="badge bg-warning"><?= htmlspecialchars($fifo_detail['available_balance']) ?></span></td>
                                                            <td><span class="badge bg-danger"><?= htmlspecialchars($fifo_detail['will_deduct']) ?></span></td>
                                                            <td><span class="badge bg-success"><?= htmlspecialchars($fifo_detail['remaining_after_deduct']) ?></span></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td><strong>สถานะ:</strong></td>
                                        <td>
                                            <?php if (isset($bom_item['stock_status']) && $bom_item['stock_status'] == 'no stock'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>No Stock
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Available
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success fw-bold mb-3">
                                    <i class="fas fa-cogs me-2"></i>ข้อมูลการผลิต
                                </h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Product ID:</strong></td>
                                        <td><?= htmlspecialchars(isset($data['product_id']) ? $data['product_id'] : 'N/A') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>จำนวนผลิต:</strong></td>
                                        <td><span
                                                class="badge bg-warning"><?= htmlspecialchars(isset($data['unit']) ? $data['unit'] : 'N/A') ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>KB ID:</strong></td>
                                        <td><?= htmlspecialchars(isset($data['kb_id']) ? $data['kb_id'] : 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>สถานะ:</strong></td>
                                        <td><span class="badge bg-success">พร้อมผลิต</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>ปิด
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $counter++;
    endforeach;
?>
<?php endif; ?>

<!-- Modal สำหรับแสดงรายละเอียด FIFO -->
<?php if (isset($data['bom_data']) && $data['bom_data']): ?>
    <?php
    // ตรวจสอบว่า bom_data เป็น array หรือไม่
    $bom_items = is_array($data['bom_data']) ? $data['bom_data'] : array($data['bom_data']);
    $counter = 1;
    foreach ($bom_items as $bom_item):
        if (isset($bom_item['fifo_details']) && !empty($bom_item['fifo_details'])):
        ?>
        <div class="modal fade" id="fifoModal<?= $counter ?>" tabindex="-1"
            aria-labelledby="fifoModalLabel<?= $counter ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="fifoModalLabel<?= $counter ?>">
                            <i class="fas fa-clock me-2"></i>รายละเอียด FIFO - Material ID: <?= htmlspecialchars($bom_item['mat_id']) ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-info fw-bold mb-3">
                                    <i class="fas fa-list-alt me-2"></i>การตัดสต็อกแบบ FIFO (First In First Out)
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-info">
                                            <tr>
                                                <th><i class="fas fa-calendar me-2"></i>วันที่เข้า</th>
                                                <th><i class="fas fa-box me-2"></i>คงเหลือเดิม</th>
                                                <th><i class="fas fa-minus-circle me-2"></i>จะตัดออก</th>
                                                <th><i class="fas fa-plus-circle me-2"></i>คงเหลือหลังตัด</th>
                                                <th><i class="fas fa-percentage me-2"></i>% ที่ใช้</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bom_item['fifo_details'] as $fifo_detail): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($fifo_detail['date_in']) ?></strong></td>
                                                <td><span class="badge bg-warning"><?= htmlspecialchars($fifo_detail['available_balance']) ?></span></td>
                                                <td><span class="badge bg-danger"><?= htmlspecialchars($fifo_detail['will_deduct']) ?></span></td>
                                                <td><span class="badge bg-success"><?= htmlspecialchars($fifo_detail['remaining_after_deduct']) ?></span></td>
                                                <td>
                                                    <?php 
                                                    $usage_percent = $fifo_detail['available_balance'] > 0 ? 
                                                        round(($fifo_detail['will_deduct'] / $fifo_detail['available_balance']) * 100, 1) : 0;
                                                    ?>
                                                    <span class="badge bg-info"><?= $usage_percent ?>%</span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>หมายเหตุ:</strong> ระบบจะตัดสต็อกตามลำดับวันที่เข้า (FIFO) โดยเริ่มจากรายการที่เก่าที่สุดก่อน
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>ปิด
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        endif;
        $counter++;
    endforeach;
    ?>
<?php endif; ?>

<script>
    // Auto focus เมื่อโหลดหน้า
    document.addEventListener('DOMContentLoaded', function () {
        const barcodeInput = document.getElementById('add_produce_barcode');
        if (barcodeInput) {
            barcodeInput.focus();
        }
    });

    // Auto submit เมื่อกด Enter
    document.getElementById('add_produce_barcode').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });

    // เพิ่มเอฟเฟกต์เมื่อพิมพ์
    document.getElementById('add_produce_barcode').addEventListener('input', function () {
        if (this.value.length > 0) {
            this.classList.add('border-success');
            this.classList.remove('border-primary');
            this.style.transform = 'scale(1.02)';
        } else {
            this.classList.remove('border-success');
            this.classList.add('border-primary');
            this.style.transform = 'scale(1)';
        }
    });

    // เพิ่มเอฟเฟกต์ hover สำหรับปุ่ม
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-2px)';
        });

        button.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });
    });

    // เพิ่มเอฟเฟกต์ ripple สำหรับปุ่ม
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function (e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
</script>

<style>
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/main.php';
?>