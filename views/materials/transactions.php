<?php require_once 'views/layouts/main.php'; ?>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-title">
                        <i class="fas fa-exchange-alt me-2"></i>
                        รายการเคลื่อนไหววัตถุดิบ
                    </h2>
                    <p class="text-muted">ติดตามการรับเข้าและจ่ายออกวัตถุดิบ</p>
                </div>
                <div>
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#receiveModal">
                        <i class="fas fa-plus me-2"></i>รับเข้า
                    </button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#issueModal">
                        <i class="fas fa-minus me-2"></i>จ่ายออก
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">ค้นหา</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="<?= htmlspecialchars($search ?? '') ?>" placeholder="รหัสสินค้า, เลขที่เอกสาร...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="transaction_type" class="form-label">ประเภท</label>
                    <select class="form-select" id="transaction_type" name="transaction_type">
                        <option value="">ทั้งหมด</option>
                        <option value="IN" <?= ($selectedType ?? '') == 'IN' ? 'selected' : '' ?>>รับเข้า</option>
                        <option value="OUT" <?= ($selectedType ?? '') == 'OUT' ? 'selected' : '' ?>>จ่ายออก</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">วันที่เริ่มต้น</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?= htmlspecialchars($dateFrom ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">วันที่สิ้นสุด</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?= htmlspecialchars($dateTo ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="limit" class="form-label">แสดงต่อหน้า</label>
                    <select class="form-select" id="limit" name="limit">
                        <option value="20" <?= ($limit ?? 20) == 20 ? 'selected' : '' ?>>20 รายการ</option>
                        <option value="50" <?= ($limit ?? 20) == 50 ? 'selected' : '' ?>>50 รายการ</option>
                        <option value="100" <?= ($limit ?? 20) == 100 ? 'selected' : '' ?>>100 รายการ</option>
                    </select>
                </div>
                <div class="col-md-1">
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success text-white me-3">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalReceived ?? 0) ?></h5>
                            <small class="text-muted">รับเข้าวันนี้</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger text-white me-3">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalIssued ?? 0) ?></h5>
                            <small class="text-muted">จ่ายออกวันนี้</small>
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
                            <i class="fas fa-list"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalTransactions ?? 0) ?></h5>
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
                        <div class="stats-icon bg-warning text-white me-3">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalStock ?? 0) ?></h5>
                            <small class="text-muted">คงเหลือทั้งหมด</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>รายการเคลื่อนไหว
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($transactions)): ?>
                <div class="empty-state text-center py-5">
                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่พบรายการเคลื่อนไหว</h5>
                    <p class="text-muted">เริ่มต้นโดยการรับเข้าหรือจ่ายออกวัตถุดิบ</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>วันที่</th>
                                <th>เลขที่เอกสาร</th>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>ประเภท</th>
                                <th>จำนวนรับ</th>
                                <th>จำนวนชิ้น</th>
                                <th>จำนวนสุดท้าย</th>
                                <th>หมายเหตุ</th>
                                <th>ผู้บันทึก</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($transaction['date_transaction'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>materials/transaction-detail?transaction_no=<?= urlencode($transaction['transaction_no']) ?>" class="fw-bold text-decoration-none">
                                            <?= htmlspecialchars($transaction['transaction_no']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($transaction['product_id']) ?></td>
                                    <td><?= htmlspecialchars($transaction['product_name'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $transaction['transaction_type'] == 'IN' ? 'success' : 'danger' ?>">
                                            <?= $transaction['transaction_type'] == 'IN' ? 'รับเข้า' : 'จ่ายออก' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($transaction['transaction_type'] == 'IN'): ?>
                                            <span class="text-success"><?= number_format($transaction['unit_received'] ?? 0) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($transaction['transaction_type'] == 'IN'): ?>
                                            <span class="text-success"><?= number_format($transaction['calculated_pieces'] ?? 0) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-<?= $transaction['transaction_type'] == 'IN' ? 'success' : 'danger' ?>">
                                            <?= $transaction['transaction_type'] == 'IN' ? '+' : '-' ?><?= number_format($transaction['unit'] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($transaction['remark'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($transaction['created_by'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (($totalPages ?? 1) > 1): ?>
                    <div class="card-footer">
                        <nav aria-label="Transaction pagination">
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

<!-- Receive Modal -->
<div class="modal fade" id="receiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>รับเข้าวัตถุดิบ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>materials/receive">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="receive_product_id" class="form-label">รหัสสินค้า</label>
                        <select class="form-select" id="receive_product_id" name="product_id" required>
                            <option value="">เลือกสินค้า</option>
                            <?php foreach ($materials ?? [] as $material): ?>
                                <option value="<?= htmlspecialchars($material['product_id']) ?>" data-packing="<?= $material['packing'] ?>">
                                    <?= htmlspecialchars($material['product_id']) ?> - <?= htmlspecialchars($material['product_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="receive_unit_received" class="form-label">จำนวนที่รับเข้า (หน่วยเต็ม)</label>
                        <input type="number" class="form-control" id="receive_unit_received" name="unit_received" min="1" required>
                        <div class="form-text">จำนวนที่รับเข้าจริงก่อนหารด้วย packing</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">จำนวนชิ้นที่คำนวณได้</label>
                        <input type="text" class="form-control" id="calculated_pieces_display" readonly>
                        <div class="form-text">จำนวนชิ้น = จำนวนที่รับเข้า ÷ packing</div>
                    </div>
                    <div class="mb-3">
                        <label for="receive_remark" class="form-label">หมายเหตุ</label>
                        <textarea class="form-control" id="receive_remark" name="remark" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>รับเข้า
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Issue Modal -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-minus me-2"></i>จ่ายออกวัตถุดิบ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>materials/issue">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="issue_product_id" class="form-label">รหัสสินค้า</label>
                        <select class="form-select" id="issue_product_id" name="product_id" required>
                            <option value="">เลือกสินค้า</option>
                            <?php foreach ($stockItems ?? [] as $item): ?>
                                <option value="<?= htmlspecialchars($item['product_id']) ?>" data-balance="<?= $item['balance'] ?>">
                                    <?= htmlspecialchars($item['product_id']) ?> - <?= htmlspecialchars($item['product_name']) ?> (คงเหลือ: <?= number_format($item['balance']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="issue_unit" class="form-label">จำนวนที่จ่ายออก</label>
                        <input type="number" class="form-control" id="issue_unit" name="unit" min="1" required>
                        <div class="form-text">คงเหลือ: <span id="current_balance">0</span> ชิ้น</div>
                    </div>
                    <div class="mb-3">
                        <label for="issue_remark" class="form-label">หมายเหตุ</label>
                        <textarea class="form-control" id="issue_remark" name="remark" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-minus me-2"></i>จ่ายออก
                    </button>
                </div>
            </form>
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
</style>

<script>
// Calculate pieces when receive form changes
document.getElementById('receive_product_id').addEventListener('change', function() {
    calculatePieces();
});

document.getElementById('receive_unit_received').addEventListener('input', function() {
    calculatePieces();
});

function calculatePieces() {
    const productSelect = document.getElementById('receive_product_id');
    const unitReceived = document.getElementById('receive_unit_received').value;
    const calculatedDisplay = document.getElementById('calculated_pieces_display');
    
    if (productSelect.value && unitReceived) {
        const packing = parseInt(productSelect.options[productSelect.selectedIndex].dataset.packing) || 1;
        const totalPieces = Math.ceil(unitReceived / packing);
        const fullBoxes = Math.floor(unitReceived / packing);
        const remainingPieces = unitReceived % packing;
        
        let displayText = '';
        if (fullBoxes > 0) {
            displayText += `${fullBoxes} กล่องเต็ม`;
        }
        if (remainingPieces > 0) {
            if (fullBoxes > 0) displayText += ' และ ';
            displayText += `1 กล่อง จำนวน ${remainingPieces} ชิ้น`;
        }
        if (displayText === '') {
            displayText = '0 ชิ้น';
        }
        displayText += ` (รวม ${totalPieces} ชิ้น)`;
        
        calculatedDisplay.value = displayText;
    } else {
        calculatedDisplay.value = '';
    }
}

// Update balance when issue form changes
document.getElementById('issue_product_id').addEventListener('change', function() {
    const balance = this.options[this.selectedIndex].dataset.balance || 0;
    document.getElementById('current_balance').textContent = new Intl.NumberFormat().format(balance);
    document.getElementById('issue_unit').max = balance;
});

// Transaction numbers are auto-generated by server
</script>

<?php require_once 'views/layouts/footer.php'; ?>