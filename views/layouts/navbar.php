<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <i class="fas fa-cube me-2"></i>ระบบจัดการวัสดุ
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>dashboard">
                        <i class="fas fa-home me-1"></i>หน้าแรก
                    </a>
                </li>
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'pc' || $_SESSION['role'] == 'admin')): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'materials') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>materials/dashboard">
                        <i class="fas fa-boxes me-1"></i>ข้อมูลวัสดุ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'material-transactions') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>material-transactions">
                        <i class="fas fa-exchange-alt me-1"></i>ธุรกรรมวัสดุ
                    </a>
                </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>users">
                        <i class="fas fa-users me-1"></i>จัดการผู้ใช้
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>reports">
                        <i class="fas fa-chart-bar me-1"></i>รายงาน
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>settings">
                        <i class="fas fa-cog me-1"></i>ตั้งค่า
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (isset($current_user) && $current_user): ?>
                    <!-- Notifications -->
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                            <li><h6 class="dropdown-header">การแจ้งเตือน</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold">วัสดุใกล้หมด</div>
                                            <div class="small text-muted">กระดาษ A4 เหลือน้อยกว่า 10%</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-info"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold">อัปเดตระบบ</div>
                                            <div class="small text-muted">ระบบจะอัปเดตในวันที่ 15 ธันวาคม</div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">ดูทั้งหมด</a></li>
                        </ul>
                    </li>
                    
                    <!-- User Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar-nav me-2">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-info-nav d-none d-md-block">
                                <div class="user-name"><?= htmlspecialchars($current_user['user_name']) ?></div>
                                <div class="user-role"><?= htmlspecialchars($current_user['position'] ?? 'ผู้ใช้') ?></div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>profile">
                                    <i class="fas fa-user me-2"></i>โปรไฟล์
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>settings">
                                    <i class="fas fa-cog me-2"></i>ตั้งค่า
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>login">
                            <i class="fas fa-sign-in-alt me-1"></i>เข้าสู่ระบบ
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.user-avatar-nav {
    width: 35px;
    height: 35px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.user-info-nav .user-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    line-height: 1.2;
}

.user-info-nav .user-role {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.2;
}

.dropdown-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
}

.dropdown-item {
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #f8f9fa;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background: var(--light-color);
    color: var(--primary-color);
}

.dropdown-item.text-danger:hover {
    background: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin: 0.25rem 0;
    }
    
    .user-info-nav {
        display: none !important;
    }
}
</style> 