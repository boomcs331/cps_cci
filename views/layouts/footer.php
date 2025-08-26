<!-- Footer -->
<!-- <footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-cube me-2"></i>ระบบจัดการวัสดุและครุภัณฑ์
                </h5>
                <p class="text-light opacity-75">
                    ระบบจัดการวัสดุและครุภัณฑ์ที่พัฒนาขึ้นเพื่อการจัดการที่มีประสิทธิภาพ 
                    และการติดตามที่แม่นยำ
                </p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">เมนูหลัก</h6>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>">หน้าแรก</a></li>
                    <li><a href="<?= BASE_URL ?>materials">ข้อมูลวัสดุ</a></li>
                    <li><a href="<?= BASE_URL ?>material-transactions">ธุรกรรมวัสดุ</a></li>
                    <li><a href="<?= BASE_URL ?>reports">รายงาน</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="text-white mb-3">การสนับสนุน</h6>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>help">คู่มือการใช้งาน</a></li>
                    <li><a href="<?= BASE_URL ?>faq">คำถามที่พบบ่อย</a></li>
                    <li><a href="<?= BASE_URL ?>contact">ติดต่อเรา</a></li>
                    <li><a href="<?= BASE_URL ?>feedback">ข้อเสนอแนะ</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="text-white mb-3">ข้อมูลระบบ</h6>
                <div class="system-info">
                    <div class="info-item">
                        <i class="fas fa-server text-info me-2"></i>
                        <span>สถานะ: <span class="text-success">ออนไลน์</span></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock text-warning me-2"></i>
                        <span>เวลาทำการ: 8:00 - 17:00</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone text-primary me-2"></i>
                        <span>โทร: 02-123-4567</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope text-danger me-2"></i>
                        <span>อีเมล: support@system.com</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-light mb-0">
                    &copy; <?= date('Y') ?> ระบบจัดการวัสดุและครุภัณฑ์. สงวนลิขสิทธิ์.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="footer-bottom-links">
                    <a href="<?= BASE_URL ?>privacy">นโยบายความเป็นส่วนตัว</a>
                    <a href="<?= BASE_URL ?>terms">ข้อกำหนดการใช้งาน</a>
                    <a href="<?= BASE_URL ?>sitemap">แผนผังเว็บไซต์</a>
                </div>
            </div>
        </div>
    </div>
</footer> -->

<script src="<?= BASE_URL ?>lib/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>lib/js/jquery-3.7.1.js"></script>

<!-- Custom JavaScript for Dashboard -->
<script>
// Add active class to current nav item
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href').split('/').pop())) {
            link.classList.add('active');
        }
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add loading state to buttons
document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('click', function() {
        if (!this.classList.contains('btn-loading')) {
            this.classList.add('btn-loading');
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="loading me-2"></span>กำลังดำเนินการ...';
            
            // Reset button after 3 seconds (for demo purposes)
            setTimeout(() => {
                this.classList.remove('btn-loading');
                this.innerHTML = originalText;
            }, 3000);
        }
    });
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Example usage:
// showNotification('บันทึกข้อมูลสำเร็จ!', 'success');
// showNotification('เกิดข้อผิดพลาดในการบันทึก', 'danger');
</script>

<style>
.footer {
    background: linear-gradient(135deg, var(--dark-color) 0%, #34495e 100%);
    color: white;
    padding: 3rem 0 2rem 0;
    margin-top: 4rem;
}

.footer h5, .footer h6 {
    color: white;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: var(--transition);
    display: inline-block;
}

.footer-links a:hover {
    color: white;
    transform: translateX(5px);
}

.social-links {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.social-link {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--transition);
}

.social-link:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
}

.system-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
}

.footer-divider {
    border-color: rgba(255, 255, 255, 0.2);
    margin: 2rem 0 1rem 0;
}

.footer-bottom-links {
    display: flex;
    gap: 1.5rem;
    justify-content: flex-end;
}

.footer-bottom-links a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
}

.footer-bottom-links a:hover {
    color: white;
}

.btn-loading {
    pointer-events: none;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .footer {
        text-align: center;
        padding: 2rem 0 1rem 0;
    }
    
    .footer-bottom-links {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>
</body>
</html> 