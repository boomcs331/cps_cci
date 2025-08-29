<?php require_once 'views/layouts/main.php'; ?>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-title">
                        <i class="fas fa-file-alt me-2"></i>
                        รายละเอียดธุรกรรม
                    </h2>
                    <p class="text-muted">รายละเอียดการรับเข้าและจ่ายออกวัตถุดิบ</p>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>materials/transactions" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>กลับ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Info -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-info-circle me-2"></i>ข้อมูลธุรกรรม
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>เลขที่เอกสาร:</strong></td>
                            <td><?= htmlspecialchars($transaction['transaction_no'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>รหัสสินค้า:</strong></td>
                            <td><?= htmlspecialchars($transaction['product_id'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>ชื่อสินค้า:</strong></td>
                            <td><?= htmlspecialchars($transaction['product_name'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <td><strong>ประเภท:</strong></td>
                            <td>
                                <span class="badge bg-<?= $transaction['transaction_type'] == 'IN' ? 'success' : 'danger' ?>">
                                    <?= $transaction['transaction_type'] == 'IN' ? 'รับเข้า' : 'จ่ายออก' ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>วันที่ทำรายการ:</strong></td>
                            <td><?= date('d/m/Y', strtotime($transaction['date_transaction'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>จำนวนรับเข้า:</strong></td>
                            <td><?= number_format($transaction['unit_received'] ?? 0) ?></td>
                        </tr>
                        <tr>
                            <td><strong>จำนวนชิ้น:</strong></td>
                            <td><?= number_format($transaction['calculated_pieces'] ?? 0) ?></td>
                        </tr>
                        <tr>
                            <td><strong>ผู้บันทึก:</strong></td>
                            <td><?= htmlspecialchars($transaction['created_by'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php if (!empty($transaction['remark'])): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <strong>หมายเหตุ:</strong>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($transaction['remark'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>รายละเอียดชิ้นงาน
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($details)): ?>
                <div class="empty-state text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่พบรายละเอียดชิ้นงาน</h5>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80">ลำดับ</th>
                                <th>QR Code</th>
                                <th>รหัสสินค้า</th>
                                <th>จำนวน</th>
                                <th width="120">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $index => $detail): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <code><?= htmlspecialchars($detail['qr_code']) ?></code>
                                    </td>
                                    <td><?= htmlspecialchars($detail['product_id']) ?></td>
                                    <td><?= number_format($detail['unit']) ?></td>
                                    <td>
                                        <span class="badge bg-success">ปกติ</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($details)): ?>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">รวม <?= count($details) ?> รายการ</span>
                    <button class="btn btn-outline-primary btn-sm" onclick="printQRCodes()">
                        <i class="fas fa-print me-2"></i>พิมพ์ QR Code
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-title {
    color: #2c3e50;
    font-weight: 600;
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

<script>
function printQRCodes() {
    window.print();
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>