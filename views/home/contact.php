<?php
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="fw-bold mb-4">
                <i class="fas fa-envelope me-2"></i>ติดต่อเรา
            </h1>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-info-circle text-primary me-2"></i>ข้อมูลติดต่อ
                            </h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-envelope me-2"></i>info@example.com</li>
                                <li><i class="fas fa-phone me-2"></i>+66 123 456 789</li>
                                <li><i class="fas fa-map-marker-alt me-2"></i>กรุงเทพฯ, ประเทศไทย</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-paper-plane text-primary me-2"></i>ส่งข้อความ
                            </h5>
                            <form method="POST" action="<?= BASE_URL ?>contact">
                                <div class="mb-3">
                                    <label for="name" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">ข้อความ</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>ส่งข้อความ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-question-circle text-primary me-2"></i>คำถามที่พบบ่อย
                    </h5>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    PHP MVC Framework คืออะไร?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    PHP MVC Framework เป็นเฟรมเวิร์คที่ใช้ Model-View-Controller pattern 
                                    เพื่อแยกส่วนการทำงานของแอปพลิเคชันให้ชัดเจน
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    ต้องใช้ความรู้อะไรบ้าง?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    ต้องมีความรู้พื้นฐาน PHP, HTML, CSS, JavaScript และ SQL
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/main.php';
?> 