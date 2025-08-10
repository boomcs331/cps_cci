<?php 
require_once 'views/layouts/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-exchange-alt me-2"></i>รายการธุรกรรมวัสดุ</h3>
                    <div>
                        <a href="<?= BASE_URL ?>material-transactions/create-in" class="btn btn-success me-2">
                            <i class="fas fa-plus me-2"></i>รับเข้า
                        </a>
                        <a href="<?= BASE_URL ?>material-transactions/create-out" class="btn btn-warning me-2">
                            <i class="fas fa-minus me-2"></i>จ่ายออก
                        </a>
                        <a href="<?= BASE_URL ?>material-transactions/stock" class="btn btn-info me-2">
                            <i class="fas fa-boxes me-2"></i>สต็อก
                        </a>
                        <a href="<?= BASE_URL ?>material-transactions/report" class="btn btn-secondary">
                            <i class="fas fa-chart-bar me-2"></i>รายงาน
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="<?= BASE_URL ?>material-transactions" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">วัสดุ</label>
                                <select name="material_id" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                    <?php foreach ($materials as $material): ?>
                                        <option value="<?= $material['id'] ?>" <?= $filters['material_id'] == $material['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($material['mat_id']) ?> - <?= htmlspecialchars($material['mat_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ประเภท</label>
                                <select name="transaction_type" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                    <option value="IN" <?= $filters['transaction_type'] === 'IN' ? 'selected' : '' ?>>รับเข้า</option>
                                    <option value="OUT" <?= $filters['transaction_type'] === 'OUT' ? 'selected' : '' ?>>จ่ายออก</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">วันที่เริ่ม</label>
                                <input type="date" class="form-control" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">วันที่สิ้นสุด</label>
                                <input type="date" class="form-control" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ค้นหา</label>
                                <input type="text" class="form-control" name="search" placeholder="ค้นหา..." value="<?= htmlspecialchars($filters['search']) ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>ค้นหา
                                    </button>
                                    <a href="<?= BASE_URL ?>material-transactions" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>ล้าง
                                    </a>
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
                                    echo 'บันทึกการรับเข้าเรียบร้อยแล้ว';
                                    break;
                                case '2':
                                    echo 'บันทึกการจ่ายออกเรียบร้อยแล้ว';
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

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>วันที่</th>
                                    <th>รหัสอ้างอิง</th>
                                    <th>ประเภท</th>
                                    <th>วัสดุ</th>
                                    <th>จำนวน</th>
                                    <th>ราคาต่อหน่วย</th>
                                    <th>จำนวนเงิน</th>
                                    <th>ซัพพลายเออร์/ผู้รับ</th>
                                    <th>รหัส QR</th>
                                    <th>ผู้บันทึก</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">ไม่พบข้อมูลธุรกรรม</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($transaction['transaction_date'])) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($transaction['reference_no']) ?></strong>
                                            </td>
                                            <td>
                                                <?php if ($transaction['transaction_type'] === 'IN'): ?>
                                                    <span class="badge bg-success">รับเข้า</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">จ่ายออก</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($transaction['mat_id']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($transaction['mat_name']) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $transaction['transaction_type'] === 'IN' ? 'success' : 'warning' ?>">
                                                    <?= number_format($transaction['quantity']) ?>
                                                </span>
                                            </td>
                                            <td><?= $transaction['unit_price'] > 0 ? number_format($transaction['unit_price'], 2) : '-' ?></td>
                                            <td><?= $transaction['total_amount'] > 0 ? number_format($transaction['total_amount'], 2) : '-' ?></td>
                                            <td>
                                                <?php if ($transaction['transaction_type'] === 'IN'): ?>
                                                    <small><?= htmlspecialchars($transaction['supplier']) ?></small>
                                                <?php else: ?>
                                                    <small><?= htmlspecialchars($transaction['recipient']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($transaction['transaction_type'] === 'IN' && !empty($transaction['qr_code'])): ?>
                                                    <span class="badge bg-primary small"><?= htmlspecialchars($transaction['qr_code']) ?></span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($transaction['created_by_user']) ?></small>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?>material-transactions/show/<?= $transaction['id'] ?>" 
                                                   class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pagination['last_page'] > 1): ?>
                        <nav aria-label="Transactions pagination">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= BASE_URL ?>material-transactions?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query($filters) ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                                    <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= BASE_URL ?>material-transactions?page=<?= $i ?>&<?= http_build_query($filters) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= BASE_URL ?>material-transactions?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query($filters) ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                    <!-- Summary -->
                    <div class="text-muted text-center mt-3">
                        แสดง <?= count($transactions) ?> รายการ จากทั้งหมด <?= $pagination['total'] ?> รายการ
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?> 