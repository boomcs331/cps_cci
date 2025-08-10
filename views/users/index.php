<?php
ob_start();
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">
            <i class="fas fa-users me-2"></i>รายการผู้ใช้
        </h1>
        <a href="<?= BASE_URL ?>users/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>เพิ่มผู้ใช้ใหม่
        </a>
    </div>
    
    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>users" class="row g-3">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="search" 
                           placeholder="ค้นหาตามชื่อหรืออีเมล..." 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i>ค้นหา
                    </button>
                    <a href="<?= BASE_URL ?>users" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>ล้าง
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($users['data'])): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">ไม่พบข้อมูลผู้ใช้</h5>
                    <p class="text-muted">ลองเพิ่มผู้ใช้ใหม่หรือเปลี่ยนคำค้นหา</p>
                    <a href="<?= BASE_URL ?>users/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>เพิ่มผู้ใช้ใหม่
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>ชื่อ</th>
                                <th>อีเมล</th>
                                <th>เบอร์โทร</th>
                                <th>วันที่สร้าง</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users['data'] as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars(isset($user['phone']) ? $user['phone'] : '-') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= BASE_URL ?>users/show/<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-outline-info" title="ดู">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>users/edit/<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteUser(<?= $user['id'] ?>)" title="ลบ">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($users['last_page'] > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($users['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>users?page=<?= $users['current_page'] - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $users['last_page']; $i++): ?>
                                <li class="page-item <?= $i == $users['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= BASE_URL ?>users?page=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($users['current_page'] < $users['last_page']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= BASE_URL ?>users?page=<?= $users['current_page'] + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
                
                <!-- Summary -->
                <div class="text-center mt-3">
                    <small class="text-muted">
                        แสดง <?= count($users['data']) ?> รายการ จากทั้งหมด <?= $users['total'] ?> รายการ
                    </small>
                </div>
            <?php endif; ?>
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
                location.reload();
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