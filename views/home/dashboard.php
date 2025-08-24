<?php require_once 'views/layouts/header.php'; ?>

<!-- Dashboard Header -->
<!-- <div class="dashboard-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="dashboard-title">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    หน้าหลักระบบ
                </h1>
                <p class="dashboard-subtitle">ยินดีต้อนรับสู่ระบบจัดการวัสดุและครุภัณฑ์</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle fa-2x"></i>
                    </div>
                    <div class="user-details">
                        <h6 class="mb-0"><?= htmlspecialchars($user['user_name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($user['user_id']) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="container-fluid mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="welcome-text">
                            สวัสดี, <span class="user-name"><?= htmlspecialchars($user['user_name']) ?></span> 👋
                        </h3>
                        <!-- <p class="welcome-description">
                            ยินดีต้อนรับสู่ระบบจัดการวัสดุและครุภัณฑ์ของเรา 
                            คุณสามารถจัดการข้อมูลวัสดุ ดูรายงาน และตั้งค่าระบบได้ที่นี่
                        </p> -->
                        <div class="user-roles">
                            <span class="role-label">บทบาท:</span>
                            <?php
                            // Ensure roles are an array; support comma-separated string or array
                            $roles = isset($user['roles']) ? $user['roles'] : [];
                            if (!is_array($roles)) {
                                $roles = array_filter(array_map('trim', explode(',', (string)$roles)));
                            }
                            ?>
                            <div class="role-badges">
                                <?php if (empty($roles)): ?>
                                    <span class="role-badge role-badge--none">ไม่มีบทบาท</span>
                                <?php else: ?>
                                    <?php foreach ($roles as $r):
                                        $clean = trim($r);
                                        $label = htmlspecialchars(ucfirst((string)$clean));
                                        $roleClass = 'role-' . preg_replace('/[^a-z0-9_-]/', '', strtolower($clean));
                                    ?>
                                        <span class="role-badge <?= $roleClass ?>" title="<?= $label ?>">
                                            <?php if (strtolower($clean) === 'admin'): ?>
                                                <i class="fas fa-user-shield me-1"></i>
                                            <?php elseif (strtolower($clean) === 'staff'): ?>
                                                <i class="fas fa-briefcase me-1"></i>
                                            <?php elseif (strtolower($clean) === 'viewer'): ?>
                                                <i class="fas fa-eye me-1"></i>
                                            <?php endif; ?>
                                            <?= $label ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="<?= BASE_URL ?>logout" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card--primary">
                <div class="stat-card__icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-card__content">
                    <h3 class="stat-card__number">1,234</h3>
                    <p class="stat-card__label">วัสดุทั้งหมด</p>
                    <div class="stat-card__trend stat-card__trend--up">
                        <i class="fas fa-arrow-up"></i>
                        <span>12%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card--success">
                <div class="stat-card__icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-card__content">
                    <h3 class="stat-card__number">567</h3>
                    <p class="stat-card__label">ธุรกรรมวันนี้</p>
                    <div class="stat-card__trend stat-card__trend--up">
                        <i class="fas fa-arrow-up"></i>
                        <span>8%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card--warning">
                <div class="stat-card__icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-card__content">
                    <h3 class="stat-card__number">23</h3>
                    <p class="stat-card__label">วัสดุใกล้หมด</p>
                    <div class="stat-card__trend stat-card__trend--down">
                        <i class="fas fa-arrow-down"></i>
                        <span>5%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card--info">
                <div class="stat-card__icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card__content">
                    <h3 class="stat-card__number">89</h3>
                    <p class="stat-card__label">วัสดุที่เกินจำนวน</p>
                    <div class="stat-card__trend stat-card__trend--up">
                        <i class="fas fa-arrow-up"></i>
                        <span>3%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="section-title">
                <i class="fas fa-bolt me-2"></i>การดำเนินการด่วน
            </h4>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="action-card action-card--primary">
                <div class="action-card__icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="action-card__content">
                    <h5>เพิ่มวัสดุใหม่</h5>
                    <p>เพิ่มวัสดุหรือครุภัณฑ์ใหม่เข้าสู่ระบบ</p>
                    <a href="<?= BASE_URL ?>materials/add" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>เพิ่มวัสดุ
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="action-card action-card--success">
                <div class="action-card__icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="action-card__content">
                    <h5>ทำธุรกรรม</h5>
                    <p>บันทึกการรับ-จ่ายวัสดุ</p>
                    <a href="<?= BASE_URL ?>material-transactions/add" class="btn btn-success btn-sm">
                        <i class="fas fa-exchange-alt me-1"></i>ทำธุรกรรม
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="action-card action-card--warning">
                <div class="action-card__icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="action-card__content">
                    <h5>ค้นหาวัสดุ</h5>
                    <p>ค้นหาและดูข้อมูลวัสดุ</p>
                    <a href="<?= BASE_URL ?>materials" class="btn btn-warning btn-sm">
                        <i class="fas fa-search me-1"></i>ค้นหา
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="action-card action-card--info">
                <div class="action-card__icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-card__content">
                    <h5>รายงาน</h5>
                    <p>ดูรายงานและสถิติต่างๆ</p>
                    <a href="<?= BASE_URL ?>reports" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-bar me-1"></i>ดูรายงาน
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-history me-2"></i>กิจกรรมล่าสุด
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon activity-icon--success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="activity-content">
                                <h6>เพิ่มวัสดุใหม่</h6>
                                <p>เพิ่มวัสดุ "MAT0002" จำนวน 100 ชิ้น</p>
                                <small class="text-muted">2 นาทีที่แล้ว</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon activity-icon--warning">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div class="activity-content">
                                <h6>ทำธุรกรรม</h6>
                                <p>จ่ายวัสดุ "MAT0001" จำนวน 50 ด้าม</p>
                                <small class="text-muted">15 นาทีที่แล้ว</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon activity-icon--info">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="activity-content">
                                <h6>ผู้ใช้ใหม่</h6>
                                <p>ผู้ใช้ "สมชาย ใจดี" เข้าร่วมระบบ</p>
                                <small class="text-muted">1 ชั่วโมงที่แล้ว</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bell me-2"></i>การแจ้งเตือน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notification-list">
                        <div class="notification-item notification-item--warning">
                            <div class="notification-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="notification-content">
                                <h6>วัสดุใกล้หมด</h6>
                                <p>MAT0001 เหลือน้อยกว่า 10%</p>
                            </div>
                        </div>
                        <div class="notification-item notification-item--info">
                            <div class="notification-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="notification-content">
                                <h6>อัปเดตระบบ</h6>
                                <p>ระบบจะอัปเดตในวันที่ 15 ธันวาคม</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Dashboard -->
<style>
.dashboard-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    position: relative;
    z-index: 1;
}

.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0.5rem 0 0 0;
    position: relative;
    z-index: 1;
}

.user-info {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

.user-avatar {
    color: rgba(255, 255, 255, 0.9);
}

.user-details h6 {
    color: white;
    margin: 0;
}

.user-details small {
    color: rgba(255, 255, 255, 0.8);
}

.welcome-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    position: relative;
    overflow: hidden;
}

.welcome-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.welcome-text {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.user-name {
    color: var(--primary-color);
    font-weight: 700;
}

.welcome-description {
    color: #6c757d;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.user-roles {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.role-label {
    font-weight: 600;
    color: #495057;
}

.role-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.role-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 500;
    color: white;
    background: #6c757d;
    transition: all 0.3s ease;
    cursor: pointer;
}

.role-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.role-badge.role-admin {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.role-badge.role-staff {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.role-badge.role-viewer {
    background: linear-gradient(135deg, #27ae60, #229954);
}

.role-badge--none {
    background: #6c757d;
    color: #dee2e6;
}

.btn-logout {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-logout:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
}

.section-title {
    color: var(--dark-color);
    font-weight: 600;
    margin-bottom: 1.5rem;
    position: relative;
    padding-left: 1rem;
}

.section-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 25px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-card--primary::before {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.stat-card--success::before {
    background: linear-gradient(135deg, var(--success-color), #20c997);
}

.stat-card--warning::before {
    background: linear-gradient(135deg, var(--warning-color), #fd7e14);
}

.stat-card--info::before {
    background: linear-gradient(135deg, var(--info-color), #6f42c1);
}

.stat-card__icon {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 2.5rem;
    opacity: 0.1;
    transition: all 0.3s ease;
}

.stat-card:hover .stat-card__icon {
    opacity: 0.2;
    transform: scale(1.1);
}

.stat-card--primary .stat-card__icon {
    color: var(--primary-color);
}

.stat-card--success .stat-card__icon {
    color: var(--success-color);
}

.stat-card--warning .stat-card__icon {
    color: var(--warning-color);
}

.stat-card--info .stat-card__icon {
    color: var(--info-color);
}

.stat-card__number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0 0 0.5rem 0;
    line-height: 1;
}

.stat-card__label {
    color: #6c757d;
    margin: 0 0 1rem 0;
    font-weight: 500;
    font-size: 0.95rem;
}

.stat-card__trend {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.stat-card__trend--up {
    background: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.stat-card__trend--down {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger-color);
}

.action-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.action-card--primary::before {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.action-card--success::before {
    background: linear-gradient(135deg, var(--success-color), #20c997);
}

.action-card--warning::before {
    background: linear-gradient(135deg, var(--warning-color), #fd7e14);
}

.action-card--info::before {
    background: linear-gradient(135deg, var(--info-color), #6f42c1);
}

.action-card__icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.action-card:hover .action-card__icon {
    transform: scale(1.1);
}

.action-card--primary .action-card__icon {
    color: var(--primary-color);
}

.action-card--success .action-card__icon {
    color: var(--success-color);
}

.action-card--warning .action-card__icon {
    color: var(--warning-color);
}

.action-card--info .action-card__icon {
    color: var(--info-color);
}

.action-card__content h5 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.action-card__content p {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 15px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.activity-item:hover {
    background: white;
    transform: translateX(8px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color);
}

.activity-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
    font-size: 1.1rem;
}

.activity-icon--success {
    background: linear-gradient(135deg, var(--success-color), #20c997);
}

.activity-icon--warning {
    background: linear-gradient(135deg, var(--warning-color), #fd7e14);
}

.activity-icon--info {
    background: linear-gradient(135deg, var(--info-color), #6f42c1);
}

.activity-content h6 {
    color: var(--dark-color);
    margin: 0 0 0.25rem 0;
    font-weight: 600;
}

.activity-content p {
    color: #6c757d;
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

.activity-content small {
    color: #adb5bd;
    font-size: 0.8rem;
}

.notification-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 15px;
    background: #f8f9fa;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.notification-item--warning {
    border-left-color: var(--warning-color);
    background: rgba(255, 193, 7, 0.1);
}

.notification-item--info {
    border-left-color: var(--info-color);
    background: rgba(23, 162, 184, 0.1);
}

.notification-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
    font-size: 1.1rem;
}

.notification-item--warning .notification-icon {
    background: linear-gradient(135deg, var(--warning-color), #fd7e14);
}

.notification-item--info .notification-icon {
    background: linear-gradient(135deg, var(--info-color), #6f42c1);
}

.notification-content h6 {
    color: var(--dark-color);
    margin: 0 0 0.25rem 0;
    font-weight: 600;
}

.notification-content p {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Animation classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-slide-in-left {
    animation: slideInLeft 0.6s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.6s ease-out;
}

/* Responsive design */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 2rem;
    }
    
    .user-info {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .welcome-card {
        padding: 1.5rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .action-card {
        margin-bottom: 1rem;
    }
    
    .section-title {
        font-size: 1.25rem;
    }
    
    .stat-card__number {
        font-size: 2rem;
    }
    
    .action-card__icon {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .dashboard-header {
        padding: 1.5rem 0;
    }
    
    .dashboard-title {
        font-size: 1.75rem;
    }
    
    .welcome-card {
        padding: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .action-card {
        padding: 1rem;
    }
    
    .activity-item,
    .notification-item {
        padding: 1rem;
    }
    
    .activity-icon,
    .notification-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}

/* Print styles */
@media print {
    .dashboard-header,
    .btn,
    .action-card__content .btn {
        display: none !important;
    }
    
    .welcome-card,
    .stat-card,
    .action-card,
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>   