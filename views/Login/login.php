<?php require_once VIEWS_PATH . 'layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>lib/css/login.css">

<div class="login-container">
    <!-- Rotating gear background -->
    <div class="gear-background gear-1"></div>
    <div class="gear-background gear-2"></div>
    <div class="gear-background gear-3"></div>
    <div class="gear-background gear-4"></div>
    <div class="gear-background gear-5"></div>
    <div class="gear-background gear-6"></div>
    <div class="gear-background gear-7"></div>
    <div class="gear-background gear-8"></div>
    <div class="gear-background gear-9"></div>
    <div class="gear-background gear-10"></div>
    
    <!-- Animated background elements -->
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    <div class="floating-particle"></div>
    
    <div class="geometric-shape"></div>
    <div class="geometric-shape"></div>
    <div class="geometric-shape"></div>
    
    <div class="wave-container">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
    </div>
    
    <div class="login-card">
            <div class="login-header">
                <!-- <span class="inventory-icon">
                    <i class="fas fa-boxes"></i>
                </span> -->
                <h2>CPS</h2>
                <div class="subtitle">ระบบจัดการสินค้า | Inventory Management System</div>
            </div>
            <div class="login-body">
                <div style="text-align:center; margin-bottom:18px; color:#2563eb; font-size:18px; font-weight:500;">
                    <i class="fas fa-door-open"></i> ยินดีต้อนรับเข้าสู่ระบบ
                </div>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i> <?= $success ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_URL ?>login_check" style="animation: fadeInUp 1s;">
                    <div class="form-group">
                        <label for="user_id" class="form-label">
                            <i class="fas fa-user"></i> รหัสผู้ใช้
                        </label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#60a5fa; font-size:18px;">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="user_id" 
                                name="user_id" 
                                placeholder="กรุณากรอกรหัสผู้ใช้"
                                required
                                autocomplete="off"
                                style="padding-left:40px;"
                            >
                        </div>
                    </div>
                    <button type="submit" class="btn btn-login" style="margin-top:10px;">
                        <i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ
                    </button>
                </form>
                <div class="register-link">
                    <p>ยังไม่มีบัญชี? <a href="<?= BASE_URL ?>register"><i class="fas fa-user-plus"></i> สมัครสมาชิก</a></p>
                </div>
            </div>
        </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
