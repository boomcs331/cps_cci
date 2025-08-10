<?php
$title = "จัดการข้อมูลวัสดุ";
require_once VIEWS_PATH . 'layouts/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>จัดการข้อมูลวัสดุ
                    </h4>
                    <a href="<?= BASE_URL ?>pc/produce" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>สั่งผลิต
                    </a>
                    
                </div>
                <div class="card-body">
                    <!-- แสดงข้อความสำเร็จ/ข้อผิดพลาด -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php if ($_GET['success'] === 'created'): ?>
                                <i class="fas fa-check-circle me-2"></i>เพิ่มวัสดุใหม่สำเร็จ
                            <?php elseif ($_GET['success'] === 'updated'): ?>
                                <i class="fas fa-check-circle me-2"></i>อัปเดตข้อมูลวัสดุสำเร็จ
                            <?php elseif ($_GET['success'] === 'deleted'): ?>
                                <i class="fas fa-check-circle me-2"></i>ลบวัสดุสำเร็จ
                            <?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php if ($_GET['error'] === 'not_found'): ?>
                                <i class="fas fa-exclamation-triangle me-2"></i>ไม่พบข้อมูลวัสดุ
                            <?php elseif ($_GET['error'] === 'delete_failed'): ?>
                                <i class="fas fa-exclamation-triangle me-2"></i>เกิดข้อผิดพลาดในการลบข้อมูล
                            <?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- ฟอร์มค้นหาและกรอง -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET" action="<?= BASE_URL ?>pc/material" class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="ค้นหารหัสวัสดุหรือชื่อวัสดุ..."
                                        value="<?= htmlspecialchars(isset($search) ? $search : '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="status_filter" class="form-select">
                                        <option value="">สถานะทั้งหมด</option>
                                        <option value="หมด" <?= (isset($status_filter) && $status_filter === 'หมด') ? 'selected' : '' ?>>หมด</option>
                                        <option value="น้อย" <?= (isset($status_filter) && $status_filter === 'น้อย') ? 'selected' : '' ?>>น้อย</option>
                                        <option value="เพียงพอ" <?= (isset($status_filter) && $status_filter === 'เพียงพอ') ? 'selected' : '' ?>>เพียงพอ</option>
                                        <option value="เกิน" <?= (isset($status_filter) && $status_filter === 'เกิน') ? 'selected' : '' ?>>เกิน</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-search me-1"></i>ค้นหา
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <?php if (!empty($search) || !empty($status_filter)): ?>
                                        <a href="<?= BASE_URL ?>pc/material" class="btn btn-outline-danger w-100">
                                            <i class="fas fa-times me-1"></i>ล้าง
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">
                                แสดง <?= count($materials) ?> รายการจากทั้งหมด <?= $pagination['total'] ?> รายการ
                            </span>
                            <?php if (!empty($status_filter)): ?>
                                <br>
                                <small class="text-info">
                                    <i class="fas fa-filter me-1"></i>กรองตามสถานะ: <strong><?= htmlspecialchars($status_filter) ?></strong>
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ตารางแสดงข้อมูล -->
                    <?php if (!empty($status_filter)): ?>
                        <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-filter me-2"></i>
                            กำลังแสดงวัสดุที่มีสถานะ: <strong><?= htmlspecialchars($status_filter) ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="12%">รหัสวัสดุ</th>
                                    <th width="20%">ชื่อวัสดุ</th>
                                    <th width="8%">LR</th>
                                    <th width="10%">หน่วย</th>
                                    <th width="10%">จำนวน</th>
                                    <th width="10%">ขั้นต่ำ</th>
                                    <th width="10%">Packing</th>
                                    <th width="10%">ซัพพลายเออร์</th>
                                    <th width="10%">ตำแหน่ง</th>
                                    <th width="10%">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($materials)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            <?php if (!empty($search) || !empty($status_filter)): ?>
                                                ไม่พบข้อมูลวัสดุที่ตรงกับเงื่อนไขการค้นหา
                                                <?php if (!empty($status_filter)): ?>
                                                    <br><small class="text-info">สถานะ: <?= htmlspecialchars($status_filter) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                ไม่พบข้อมูลวัสดุ
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($materials as $index => $material): ?>
                                        <tr>
                                            <td><?= $pagination['current_page'] * 10 - 10 + $index + 1 ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-primary"><?= htmlspecialchars($material['mat_id']) ?></span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($material['name']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= htmlspecialchars($material['LR']) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch ($material['unit_status']) {
                                                    case 'หมด':
                                                        $status_class = 'bg-danger';
                                                        break;
                                                    case 'น้อย':
                                                        $status_class = 'bg-warning';
                                                        break;
                                                    case 'เกิน':
                                                        $status_class = 'bg-info';
                                                        break;
                                                    case 'เพียงพอ':
                                                        $status_class = 'bg-success';
                                                        break;
                                                    default:
                                                        $status_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?= $status_class ?>">
                                                    <?= htmlspecialchars($material['unit_status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $stock_class = '';
                                                if ($material['unit'] <=0) {
                                                    $stock_class = 'text-danger fw-bold';
                                                } elseif ($material['unit'] < $material['min']) {
                                                    $stock_class = 'text-warning fw-bold';
                                                }
                                                elseif ($material['unit'] >= $material['min'] && $material['unit'] <= $material['min'] * 2) {
                                                    $stock_class = 'text-success fw-bold';
                                                }
                                                elseif ($material['unit'] > $material['min'] * 2) {
                                                    $stock_class = 'text-info fw-bold';
                                                }
                                                else {
                                                    $stock_class = 'text-secondary fw-bold';
                                                }
                                                ?>
                                                <span class="<?= $stock_class ?>">
                                                    <?= number_format($material['unit']) ?>
                                                </span>
                                            </td>
                                            <td><?= number_format($material['min'], 0) ?></td>
                                            <td>
                                                <span class="text-success">
                                                    <?= number_format($material['upk']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($material['supplier']) ?></td>
                                            <td><?= htmlspecialchars($material['location']) ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= BASE_URL ?>pc/showMaterial/<?= $material['mat_id'] ?>"
                                                        class="btn btn-sm btn-outline-info" title="ดูรายละเอียด">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>pc/editMaterial/<?= $material['mat_id'] ?>"
                                                        class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete('<?= $material['mat_id'] ?>')" title="ลบ">
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
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <!-- ปุ่มก่อนหน้า -->
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>pc/material?page=<?= $pagination['current_page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : '' ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- หน้าปัจจุบันและรอบๆ -->
                                <?php
                                $start = max(1, $pagination['current_page'] - 2);
                                $end = min($pagination['last_page'], $pagination['current_page'] + 2);
                                ?>

                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>pc/material?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : '' ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- ปุ่มถัดไป -->
                                <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>pc/material?page=<?= $pagination['current_page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status_filter) ? '&status_filter=' . urlencode($status_filter) : '' ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ยืนยันการลบ -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ยืนยันการลบ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>คุณต้องการลบวัสดุนี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">ลบ</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').href = '<?= BASE_URL ?>pc/deleteMaterial/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    
    // Auto-submit form when status filter changes
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.querySelector('select[name="status_filter"]');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
require_once VIEWS_PATH . 'layouts/main.php';
?> 