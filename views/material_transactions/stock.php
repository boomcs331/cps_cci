<?php require_once 'views/layouts/main.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-boxes me-2"></i>รายงานสต็อกวัสดุ</h3>
                    <div>
                        <a href="<?= BASE_URL ?>material-transactions" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>กลับ
                        </a>
                        <a href="<?= BASE_URL ?>material-transactions/report" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>รายงานธุรกรรม
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['total_materials']) ?></h4>
                                    <p class="mb-0">วัสดุทั้งหมด</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['normal_stock_count']) ?></h4>
                                    <p class="mb-0">สต็อกปกติ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['low_stock_count']) ?></h4>
                                    <p class="mb-0">สต็อกต่ำ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['out_of_stock_count']) ?></h4>
                                    <p class="mb-0">หมดสต็อก</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['excess_stock_count']) ?></h4>
                                    <p class="mb-0">สต็อกเกิน</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Page Summary -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>ผลการค้นหา:</strong>
                                        <?php if (!empty($filters['search'])): ?>
                                            ค้นหา "<?= htmlspecialchars($filters['search']) ?>"
                                        <?php endif; ?>
                                        <?php if (!empty($filters['status'])): ?>
                                            <?= !empty($filters['search']) ? ' และ ' : '' ?>
                                            สถานะ:
                                            <?php 
                                            switch($filters['status']) {
                                                case 'normal':
                                                    echo 'ปกติ';
                                                    break;
                                                case 'low':
                                                    echo 'สต็อกต่ำ';
                                                    break;
                                                case 'out_of_stock':
                                                    echo 'หมดสต็อก';
                                                    break;
                                                case 'excess':
                                                    echo 'สต็อกเกิน';
                                                    break;
                                                default:
                                                    echo $filters['status'];
                                            }
                                            ?>
                                        <?php endif; ?>
                                        <?php if (empty($filters['search']) && empty($filters['status'])): ?>
                                            แสดงวัสดุทั้งหมด
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <strong>จำนวนรายการ:</strong> <?= number_format($pagination['total']) ?> รายการ
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Low Stock Alert -->
                    <!--   <?php if (!empty($low_stock)): ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i>แจ้งเตือน: สต็อกต่ำ</h5>
                            <p class="mb-0">มีวัสดุ <?= count($low_stock) ?> รายการที่มีสต็อกต่ำกว่าจำนวนขั้นต่ำ</p>
                        </div>
                    <?php endif; ?> -->

                    <!-- Search and Filter Form -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <form method="GET" action="<?= BASE_URL ?>material-transactions/stock" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="ค้นหาวัสดุ..."
                                        value="<?= htmlspecialchars(isset($filters['search']) ? $filters['search'] : '') ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">สถานะทั้งหมด</option>
                                        <option value="normal" <?= (isset($filters['status']) ? $filters['status'] : '') === 'normal' ? 'selected' : '' ?>>ปกติ</option>
                                        <option value="low" <?= (isset($filters['status']) ? $filters['status'] : '') === 'low' ? 'selected' : '' ?>>สต็อกต่ำ</option>
                                        <option value="out_of_stock" <?= (isset($filters['status']) ? $filters['status'] : '') === 'out_of_stock' ? 'selected' : '' ?>>หมดสต็อก</option>
                                        <option value="excess" <?= (isset($filters['status']) ? $filters['status'] : '') === 'excess' ? 'selected' : '' ?>>สต็อกเกิน</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="per_page" class="form-select">
                                        <option value="10" <?= (isset($pagination['per_page']) ? $pagination['per_page'] : 20) == 10 ? 'selected' : '' ?>>10 รายการ</option>
                                        <option value="20" <?= (isset($pagination['per_page']) ? $pagination['per_page'] : 20) == 20 ? 'selected' : '' ?>>20 รายการ</option>
                                        <option value="50" <?= (isset($pagination['per_page']) ? $pagination['per_page'] : 20) == 50 ? 'selected' : '' ?>>50 รายการ</option>
                                        <option value="100" <?= (isset($pagination['per_page']) ? $pagination['per_page'] : 20) == 100 ? 'selected' : '' ?>>100 รายการ</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i>ค้นหา
                                    </button>
                                    <a href="<?= BASE_URL ?>material-transactions/stock" class="btn btn-secondary">
                                        <i class="fas fa-refresh me-1"></i>ล้าง
                                    </a>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <small class="text-muted">
                                <?php if ($pagination['total'] > 0): ?>
                                    แสดง
                                    <?= number_format(($pagination['current_page'] - 1) * $pagination['per_page'] + 1) ?> -
                                    <?= number_format(min($pagination['current_page'] * $pagination['per_page'], $pagination['total'])) ?>
                                    จาก <?= number_format($pagination['total']) ?> รายการ
                                <?php else: ?>
                                    ไม่มีข้อมูล
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>

                    <!-- Stock Table -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            รายการสต็อกวัสดุ
                        </h5>
                        <div class="text-muted">
                            <small>
                                <?php if ($pagination['total'] > 0): ?>
                                    หน้า <?= $pagination['current_page'] ?> จาก <?= $pagination['last_page'] ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>รหัสวัสดุ</th>
                                    <th>ชื่อวัสดุ</th>
                                    <th>สต็อกปัจจุบัน</th>
                                    <th>จำนวนขั้นต่ำ</th>
                                    <th>อัตราส่วน</th>
                                    <th>สถานะ</th>
                                    <th>ซัพพลายเออร์</th>
                                    <th>ตำแหน่งที่เก็บ</th>
                                    <th>อัปเดตล่าสุด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stock_items)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                                                ไม่พบข้อมูลที่ตรงกับการค้นหา<br>
                                                <small class="text-muted">
                                                    ลองเปลี่ยนคำค้นหาหรือล้างตัวกรอง
                                                </small>
                                            <?php else: ?>
                                                ไม่พบข้อมูลสต็อก
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stock_items as $item): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($item['mat_id']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($item['mat_name']) ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?= $item['current_qty'] > 0 ? 'success' : 'danger' ?> fs-6">
                                                    <?= number_format($item['current_qty']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"></span>
                                                    <?= number_format($item['min_qty'] * (isset($item['due']) ? $item['due'] : 2)) ?>
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    (<?= number_format($item['min_qty']) ?> × <?= isset($item['due']) ? $item['due'] : 2 ?>)
                                                </small>
                                            </td>
                                            <td>
                                                <?php
                                                $effective_min_qty = $item['min_qty'] * (isset($item['due']) ? $item['due'] : 2);
                                                $stock_ratio = $effective_min_qty > 0 ? ($item['current_qty'] / $effective_min_qty) * 100 : 0;
                                                $ratio_color = $stock_ratio >= 100 ? 'success' : ($stock_ratio >= 50 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $ratio_color ?>">
                                                    <?= number_format($stock_ratio, 1) ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($item['current_qty'] == 0): ?>
                                                    <span class="badge bg-danger">หมดสต็อก</span>
                                                <?php elseif ($item['current_qty'] > 0 && $item['current_qty'] <= $item['min_qty']): ?>
                                                    <span class="badge bg-warning">สต็อกต่ำ</span>
                                                <?php elseif ($item['current_qty'] > $item['min_qty'] * 2): ?>
                                                    <span class="badge bg-info">สต็อกเกิน</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">ปกติ</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($item['supplier']) ?></td>
                                            <td><?= htmlspecialchars($item['location']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($item['last_updated'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pagination['last_page'] > 1): ?>
                        <nav aria-label="Stock pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <!-- First Page -->
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>material-transactions/stock?page=1&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode(isset($filters['search']) ? $filters['search'] : '') ?>&status=<?= urlencode(isset($filters['status']) ? $filters['status'] : '') ?>">
                                            <i class="fas fa-angle-double-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Previous Page -->
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>material-transactions/stock?page=<?= $pagination['current_page'] - 1 ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode(isset($filters['search']) ? $filters['search'] : '') ?>&status=<?= urlencode(isset($filters['status']) ? $filters['status'] : '') ?>">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php
                                $start_page = max(1, $pagination['current_page'] - 2);
                                $end_page = min($pagination['last_page'], $pagination['current_page'] + 2);

                                if ($start_page > 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>material-transactions/stock?page=<?= $i ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode(isset($filters['search']) ? $filters['search'] : '') ?>&status=<?= urlencode(isset($filters['status']) ? $filters['status'] : '') ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($end_page < $pagination['last_page']): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Next Page -->
                                <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>material-transactions/stock?page=<?= $pagination['current_page'] + 1 ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode(isset($filters['search']) ? $filters['search'] : '') ?>&status=<?= urlencode(isset($filters['status']) ? $filters['status'] : '') ?>">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Last Page -->
                                <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="<?= BASE_URL ?>material-transactions/stock?page=<?= $pagination['last_page'] ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode(isset($filters['search']) ? $filters['search'] : '') ?>&status=<?= urlencode(isset($filters['status']) ? $filters['status'] : '') ?>">
                                            <i class="fas fa-angle-double-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                    <!-- Low Stock Details -->
                    <?php if (!empty($low_stock)): ?>
                        <div class="mt-4">
                            <h5>
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                                รายการสต็อกต่ำ
                                <span class="badge bg-warning text-dark"><?= count($low_stock) ?> รายการ</span>
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-warning">
                                    <thead>
                                        <tr>
                                            <th>รหัสวัสดุ</th>
                                            <th>ชื่อวัสดุ</th>
                                            <th>สต็อกปัจจุบัน</th>
                                            <th>จำนวนขั้นต่ำ</th>
                                            <th>อัตราส่วน</th>
                                            <th>ขาด</th>
                                            <th>ซัพพลายเออร์</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($low_stock as $item): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($item['mat_id']) ?></strong></td>
                                                <td><?= htmlspecialchars($item['mat_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-warning">
                                                        <?= number_format($item['current_qty']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= number_format($item['min_qty'] * (isset($item['due']) ? $item['due'] : 2)) ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        (<?= number_format($item['min_qty']) ?> × <?= isset($item['due']) ? $item['due'] : 2 ?>)
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $effective_min_qty = $item['min_qty'] * (isset($item['due']) ? $item['due'] : 2);
                                                    $stock_ratio = $effective_min_qty > 0 ? ($item['current_qty'] / $effective_min_qty) * 100 : 0;
                                                    $ratio_color = $stock_ratio >= 100 ? 'success' : ($stock_ratio >= 50 ? 'warning' : 'danger');
                                                    ?>
                                                    <span class="badge bg-<?= $ratio_color ?>">
                                                        <?= number_format($stock_ratio, 1) ?>%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        <?= number_format(($item['min_qty'] * (isset($item['due']) ? $item['due'] : 2)) - $item['current_qty']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($item['supplier']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Excess Stock Details -->
                    <?php if (!empty($excess_stock)): ?>
                        <div class="mt-4">
                            <h5>
                                <i class="fas fa-arrow-up me-2 text-info"></i>
                                รายการสต็อกเกิน
                                <span class="badge bg-info text-dark"><?= count($excess_stock) ?> รายการ</span>
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-info">
                                    <thead>
                                        <tr>
                                            <th>รหัสวัสดุ</th>
                                            <th>ชื่อวัสดุ</th>
                                            <th>สต็อกปัจจุบัน</th>
                                            <th>จำนวนขั้นต่ำ</th>
                                            <th>อัตราส่วน</th>
                                            <th>เกิน</th>
                                            <th>ซัพพลายเออร์</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($excess_stock as $item): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($item['mat_id']) ?></strong></td>
                                                <td><?= htmlspecialchars($item['mat_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?= number_format($item['current_qty']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= number_format($item['min_qty'] * (isset($item['due']) ? $item['due'] : 2)) ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        (<?= number_format($item['min_qty']) ?> × <?= isset($item['due']) ? $item['due'] : 2 ?>)
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $effective_min_qty = $item['min_qty'] * (isset($item['due']) ? $item['due'] : 2);
                                                    $stock_ratio = $effective_min_qty > 0 ? ($item['current_qty'] / $effective_min_qty) * 100 : 0;
                                                    $ratio_color = $stock_ratio >= 200 ? 'info' : 'success';
                                                    ?>
                                                    <span class="badge bg-<?= $ratio_color ?>">
                                                        <?= number_format($stock_ratio, 1) ?>%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?= number_format($item['current_qty'] - ($item['min_qty'] * (isset($item['due']) ? $item['due'] : 2))) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($item['supplier']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>