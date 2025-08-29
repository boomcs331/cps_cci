<?php require_once 'views/layouts/main.php'; ?>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-title">
                        <i class="fas fa-warehouse me-2"></i>
                        ยอดคงเหลือ Stock
                    </h2>
                    <p class="text-muted">แสดงยอดคงเหลือของวัตถุดิบทั้งหมด</p>
                </div>
                <div>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>พิมพ์รายงาน
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">ค้นหา</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="<?= htmlspecialchars($search ?? '') ?>" placeholder="รหัสสินค้า, ชื่อสินค้า...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="limit" class="form-label">แสดงต่อหน้า</label>
                    <select class="form-select" id="limit" name="limit">
                        <option value="20" <?= ($limit ?? 20) == 20 ? 'selected' : '' ?>>20 รายการ</option>
                        <option value="50" <?= ($limit ?? 20) == 50 ? 'selected' : '' ?>>50 รายการ</option>
                        <option value="100" <?= ($limit ?? 20) == 100 ? 'selected' : '' ?>>100 รายการ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary text-white me-3">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalItems ?? 0) ?></h5>
                            <small class="text-muted">รายการทั้งหมด</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success text-white me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalBalance ?? 0) ?></h5>
                            <small class="text-muted">ยอดรวมทั้งหมด</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning text-white me-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($lowStockItems ?? 0) ?></h5>
                            <small class="text-muted">ใกล้หมด</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info text-white me-3">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalLots ?? 0) ?></h5>
                            <small class="text-muted">จำนวน Lot</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>รายการคงเหลือ
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($stockData)): ?>
                <div class="empty-state text-center py-5">
                    <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่พบข้อมูลคงเหลือ</h5>
                    <p class="text-muted">ยังไม่มีการรับเข้าวัตถุดิบ</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>ยอดคงเหลือ</th>
                                <th>จำนวนชิ้น</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stockData as $stock): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($stock['product_id']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($stock['product_name'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-success fs-6">
                                            <?= number_format($stock['total_balance']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= number_format($stock['item_count']) ?> ชิ้น
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">มีสินค้า</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (($totalPages ?? 1) > 1): ?>
                    <div class="card-footer">
                        <nav aria-label="Stock pagination">
                            <ul class="pagination justify-content-center mb-0">
                                <?php if (($currentPage ?? 1) > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => ($currentPage ?? 1) - 1])) ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
                                    <li class="page-item <?= $i == ($currentPage ?? 1) ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if (($currentPage ?? 1) < ($totalPages ?? 1)): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => ($currentPage ?? 1) + 1])) ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.page-title {
    color: #2c3e50;
    font-weight: 600;
}

.stats-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stats-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
}

.empty-state {
    background-color: #f8f9fa;
    border-radius: 8px;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

code {
    background-color: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>