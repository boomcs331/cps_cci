<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <i class="fas fa-code me-2"></i>CPS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>">
                        <i class="fas fa-home me-1"></i>หน้าแรก
                    </a>
                </li>
                <?php if ($_SESSION['role'] == 'pc' || $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>materials">
                        <i class="fas fa-boxes me-1"></i>ข้อมูลวัสดุ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>material-transactions">
                        <i class="fas fa-boxes me-1"></i>ธุรกรรมวัสดุ
                    </a>
                </li>
                <?php endif; ?>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>about">
                        <i class="fas fa-info-circle me-1"></i>เกี่ยวกับเรา
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>contact">
                        <i class="fas fa-envelope me-1"></i>ติดต่อเรา
                    </a>
                </li> -->
            </ul>
            
            <ul class="navbar-nav">
                <?php if (isset($current_user) && $current_user): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($current_user['user_name']) ?>
                            <small class="text-muted ms-1">(<?= htmlspecialchars($current_user['user_id']) ?>)</small>
                            <?php if (!empty($current_user['position'])): ?>
                                <span class="badge bg-primary ms-1"><?= htmlspecialchars($current_user['position']) ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profile">โปรไฟล์</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>logout">ออกจากระบบ</a></li>
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