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
            <h2>CPS</h2>
            <div class="subtitle">ระบบจัดการสินค้า | Inventory Management System</div>
        </div>
        
        <div class="login-body">
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
            
            <form method="POST" action="<?= BASE_URL ?>login_check">
                <div class="form-group">
                    <label for="user_id" class="form-label">
                        <i class="fas fa-user"></i> รหัสผู้ใช้
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="user_id" 
                        name="user_id" 
                        placeholder="กรุณากรอกรหัสผู้ใช้"
                        required
                        autocomplete="off"
                    >
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ
                </button>
            </form>
            
            <div class="register-link">
                <p>ยังไม่มีบัญชี? <a href="<?= BASE_URL ?>register">สมัครสมาชิก</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEWS_PATH . 'layouts/footer.php'; ?>
