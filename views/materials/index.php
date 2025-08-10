<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-boxes me-2"></i>จัดการวัสดุ</h3>
                    <a href="<?= BASE_URL ?>materials/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>เพิ่มวัสดุใหม่
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="<?= BASE_URL ?>materials" class="mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="ค้นหาวัสดุ..." value="<?= htmlspecialchars($search) ?>">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Success/Error Messages -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php
                            switch ($_GET['success']) {
                                case '1':
                                    echo 'เพิ่มวัสดุเรียบร้อยแล้ว';
                                    break;
                                case '2':
                                    echo 'แก้ไขวัสดุเรียบร้อยแล้ว';
                                    break;
                                case '3':
                                    echo 'ลบวัสดุเรียบร้อยแล้ว';
                                    break;
                            }
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_GET['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Materials Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>รหัสวัสดุ</th>
                                    <th>ชื่อวัสดุ</th>
                                    <th>LR</th>
                                    <th>Min Qty</th>
                                    <th>Supplier</th>
                                    <th>Location</th>
                                    <th>วันที่สร้าง</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($materials)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">ไม่พบข้อมูลวัสดุ</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($materials as $material): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($material['mat_id']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($material['mat_name']) ?></td>
                                            <td><?= htmlspecialchars($material['lr']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $material['min_qty'] > 0 ? 'warning' : 'secondary' ?>">
                                                    <?= $material['min_qty'] ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($material['supplier']) ?></td>
                                            <td><?= htmlspecialchars($material['location']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($material['create_date'])) ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= BASE_URL ?>materials/show/<?= $material['id'] ?>" 
                                                       class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>materials/edit/<?= $material['id'] ?>" 
                                                       class="btn btn-sm btn-warning" title="แก้ไข">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?= $material['id'] ?>)" title="ลบ">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pagination['last_page'] > 1): ?>
                        <nav aria-label="Materials pagination">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= BASE_URL ?>materials?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($search) ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                                    <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= BASE_URL ?>materials?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= BASE_URL ?>materials?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($search) ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                    <!-- Summary -->
                    <div class="text-muted text-center mt-3">
                        แสดง <?= count($materials) ?> รายการ จากทั้งหมด <?= $pagination['total'] ?> รายการ
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ยืนยันการลบ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>คุณต้องการลบวัสดุนี้หรือไม่?</p>
                <p class="text-danger">การดำเนินการนี้ไม่สามารถยกเลิกได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">ลบ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = '<?= BASE_URL ?>materials/delete/' + id;
    modal.show();
}
</script>

<?php require_once 'views/layouts/footer.php'; ?> 