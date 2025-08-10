<?php
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="fw-bold mb-4">
                <i class="fas fa-user me-2"></i>ข้อมูลผู้ใช้
            </h1>
            
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">ข้อมูลส่วนตัว</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td><?= $user['id'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>ชื่อ:</strong></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>อีเมล:</strong></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>เบอร์โทร:</strong></td>
                                    <td><?= htmlspecialchars(isset($user['phone']) ? $user['phone'] : '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่สร้าง:</strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <i class="fas fa-user-circle" style="font-size: 150px; color: #6c757d;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <a href="<?= BASE_URL ?>users/edit/<?= $user['id'] ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>แก้ไข
                        </a>
                        <a href="<?= BASE_URL ?>users" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>กลับ
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                            <i class="fas fa-trash me-2"></i>ลบ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?')) {
        fetch(`<?= BASE_URL ?>users/delete/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '<?= BASE_URL ?>users';
            } else {
                alert('เกิดข้อผิดพลาด: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการลบผู้ใช้');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/main.php';
?> 