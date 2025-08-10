<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
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
                                    <h4><?= number_format($summary['total_stock']) ?></h4>
                                    <p class="mb-0">สต็อกรวม</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['total_min_qty']) ?></h4>
                                    <p class="mb-0">ขั้นต่ำรวม</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-<?= $summary['stock_status'] === 'sufficient' ? 'success' : ($summary['stock_status'] === 'low' ? 'warning' : 'danger') ?> text-white">
                                <div class="card-body text-center">
                                    <h4><?= number_format($summary['stock_ratio'], 1) ?>%</h4>
                                    <p class="mb-0">อัตราส่วนสต็อก</p>
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
                    </div>

                    <!-- Stock Status Summary -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-chart-pie me-2"></i>สรุปสถานะสต็อก</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>สต็อกปัจจุบัน:</span>
                                                <strong class="text-success"><?= number_format($summary['total_stock']) ?> หน่วย</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>จำนวนขั้นต่ำรวม:</span>
                                                <strong class="text-info"><?= number_format($summary['total_min_qty']) ?> หน่วย</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>สถานะโดยรวม:</span>
                                                <span class="badge bg-<?= $summary['stock_status'] === 'sufficient' ? 'success' : ($summary['stock_status'] === 'low' ? 'warning' : 'danger') ?>">
                                                    <?= $summary['stock_status'] === 'sufficient' ? 'เพียงพอ' : ($summary['stock_status'] === 'low' ? 'ต่ำ' : 'วิกฤต') ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress mt-3" style="height: 25px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?= min(100, ($summary['total_stock'] / max(1, $summary['total_min_qty'])) * 100) ?>%"
                                             aria-valuenow="<?= $summary['stock_ratio'] ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($summary['stock_ratio'], 1) ?>%
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        อัตราส่วนสต็อกปัจจุบันเทียบกับจำนวนขั้นต่ำ (<?= number_format($summary['total_stock']) ?> / <?= number_format($summary['total_min_qty']) ?>)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <?php if (!empty($low_stock)): ?>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i>แจ้งเตือน: สต็อกต่ำ</h5>
                            <p class="mb-0">มีวัสดุ <?= count($low_stock) ?> รายการที่มีสต็อกต่ำกว่าจำนวนขั้นต่ำ</p>
                        </div>
                    <?php endif; ?>

                    <!-- Stock Table -->
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
                                        <td colspan="9" class="text-center text-muted">ไม่พบข้อมูลสต็อก</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stock_items as $item): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($item['mat_id']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($item['mat_name']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $item['current_qty'] > 0 ? 'success' : 'danger' ?> fs-6">
                                                    <?= number_format($item['current_qty']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= number_format($item['min_qty']) ?></span>
                                            </td>
                                            <td>
                                                <?php 
                                                $stock_ratio = $item['min_qty'] > 0 ? ($item['current_qty'] / $item['min_qty']) * 100 : 0;
                                                $ratio_color = $stock_ratio >= 100 ? 'success' : ($stock_ratio >= 50 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $ratio_color ?>">
                                                    <?= number_format($stock_ratio, 1) ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($item['current_qty'] == 0): ?>
                                                    <span class="badge bg-danger">หมดสต็อก</span>
                                                <?php elseif ($item['current_qty'] <= $item['min_qty']): ?>
                                                    <span class="badge bg-warning">สต็อกต่ำ</span>
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

                    <!-- Low Stock Details -->
                    <?php if (!empty($low_stock)): ?>
                        <div class="mt-4">
                            <h5><i class="fas fa-exclamation-triangle me-2 text-warning"></i>รายการสต็อกต่ำ</h5>
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
                                                    <span class="badge bg-<?= $item['current_qty'] == 0 ? 'danger' : 'warning' ?>">
                                                        <?= number_format($item['current_qty']) ?>
                                                    </span>
                                                </td>
                                                <td><?= number_format($item['min_qty']) ?></td>
                                                <td>
                                                    <?php 
                                                    $stock_ratio = $item['min_qty'] > 0 ? ($item['current_qty'] / $item['min_qty']) * 100 : 0;
                                                    $ratio_color = $stock_ratio >= 100 ? 'success' : ($stock_ratio >= 50 ? 'warning' : 'danger');
                                                    ?>
                                                    <span class="badge bg-<?= $ratio_color ?>">
                                                        <?= number_format($stock_ratio, 1) ?>%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        <?= number_format($item['min_qty'] - $item['current_qty']) ?>
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