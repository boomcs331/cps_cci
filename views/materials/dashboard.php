<?php require_once 'views/layouts/main.php'; ?>

<div class="container-fluid mt-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-title">
                        <i class="fas fa-boxes me-2"></i>
                        จัดการวัตถุดิบ
                    </h2>
                    <p class="text-muted">จัดการและติดตามข้อมูลวัตถุดิบและสินค้าคงคลัง</p>
                </div>
                <!--   <div>
                    <button class="btn btn-primary" onclick="location.href='materials/add'">
                        <i class="fas fa-plus me-2"></i>เพิ่มวัตถุดิบใหม่
                    </button>
                </div> -->
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

    <!-- Filter and Search Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">ค้นหา</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="<?= htmlspecialchars($search) ?>" placeholder="รหัสสินค้า, ชื่อสินค้า, ผู้จัดหา...">
                    </div>
                </div>
                <!-- <div class="col-md-2">
                    <label for="type" class="form-label">ประเภท</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">ทุกประเภท</option>
                        <?php foreach ($types as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $selectedType == $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div> -->
                <div class="col-md-2">
                    <label for="active" class="form-label">สถานะ</label>
                    <select class="form-select" id="active" name="active">
                        <option value="1" <?= $selectedActive == '1' ? 'selected' : '' ?>>ใช้งาน</option>
                        <option value="0" <?= $selectedActive == '0' ? 'selected' : '' ?>>ไม่ใช้งาน</option>
                        <option value="" <?= $selectedActive == '' ? 'selected' : '' ?>>ทั้งหมด</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="limit" class="form-label">แสดงต่อหน้า</label>
                    <select class="form-select" id="limit" name="limit">
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 รายการ</option>
                        <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20 รายการ</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 รายการ</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100 รายการ</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort" class="form-label">เรียงลำดับ</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="product_id" <?= $sort == 'product_id' ? 'selected' : '' ?>>รหัสสินค้า</option>
                        <option value="product_name" <?= $sort == 'product_name' ? 'selected' : '' ?>>ชื่อสินค้า</option>
                        <option value="type" <?= $sort == 'type' ? 'selected' : '' ?>>ประเภท</option>
                        <option value="supplier" <?= $sort == 'supplier' ? 'selected' : '' ?>>ผู้จัดหา</option>
                        <option value="min_stock" <?= $sort == 'min_stock' ? 'selected' : '' ?>>สต็อกขั้นต่ำ</option>
                        <option value="created_at" <?= $sort == 'created_at' ? 'selected' : '' ?>>วันที่สร้าง</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="order" class="form-label">ลำดับ</label>
                    <select class="form-select" id="order" name="order">
                        <option value="ASC" <?= $order == 'ASC' ? 'selected' : '' ?>>น้อยไปมาก</option>
                        <option value="DESC" <?= $order == 'DESC' ? 'selected' : '' ?>>มากไปน้อย</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search me-2"></i>ค้นหา
                        </button>
                    </div>
                </div>
            </form>

            <!-- Clear Filters Row -->
            <div class="row mt-3">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllFilters()">
                        <i class="fas fa-times me-2"></i>ล้างตัวกรองทั้งหมด
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="exportToCSV()">
                        <i class="fas fa-download me-2"></i>ส่งออก CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary text-white me-3">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <h5 class="stats-number mb-0"><?= number_format($totalCount) ?></h5>
                            <small class="text-muted">วัตถุดิบทั้งหมด</small>
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
                            <h5 class="stats-number mb-0">
                                <?= number_format(count(array_filter($materials, function ($m) {
                                    return $m['active'] == 1;
                                }))) ?>
                            </h5>
                            <small class="text-muted">ใช้งานอยู่</small>
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
                            <h5 class="stats-number mb-0">
                                <?= number_format(count($suppliers)) ?>
                            </h5>
                            <small class="text-muted">ผู้จัดหา</small>
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
                            <h5 class="stats-number mb-0"><?= count($types) ?></h5>
                            <small class="text-muted">ประเภทสินค้า</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Summary -->
    <?php if (!empty($search) || !empty($selectedType) || $selectedActive !== '1' || $limit != 20 || $sort != 'product_id' || $order != 'ASC'): ?>
        <div class="card mb-4">
            <div class="card-body py-2">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-filter text-primary me-2"></i>
                        <span class="text-muted">ตัวกรองที่ใช้งาน:</span>
                        <div class="ms-3">
                            <?php if (!empty($search)): ?>
                                <span class="badge bg-primary me-1">ค้นหา: <?= htmlspecialchars($search) ?></span>
                            <?php endif; ?>
                            <!--   <?php if (!empty($selectedType)): ?>
                                <span class="badge bg-secondary me-1">ประเภท:
                                    <?= htmlspecialchars($types[$selectedType] ?? $selectedType) ?></span>
                            <?php endif; ?> -->
                            <?php if ($selectedActive !== '1'): ?>
                                <span class="badge bg-info me-1">สถานะ:
                                    <?= $selectedActive == '0' ? 'ไม่ใช้งาน' : 'ทั้งหมด' ?></span>
                            <?php endif; ?>
                            <?php if ($limit != 20): ?>
                                <span class="badge bg-warning me-1">แสดง: <?= $limit ?> รายการ</span>
                            <?php endif; ?>
                            <?php if ($sort != 'product_id' || $order != 'ASC'): ?>
                                <span class="badge bg-dark me-1">เรียง: <?= htmlspecialchars($types[$sort] ?? $sort) ?>
                                    (<?= $order == 'ASC' ? 'น้อยไปมาก' : 'มากไปน้อย' ?>)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearAllFilters()">
                        <i class="fas fa-times me-1"></i>ล้างทั้งหมด
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Materials Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>รายการวัตถุดิบ
                </h5>
                <div class="d-flex align-items-center">
                    <small class="text-muted me-3">
                        แสดง <?= count($materials) ?> จาก <?= number_format($totalCount) ?> รายการ
                    </small>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleViewMode('table')"
                            id="btnTable">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleViewMode('card')"
                            id="btnCard">
                            <i class="fas fa-th-large"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (empty($materials)): ?>
                <div class="empty-state text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่พบข้อมูลวัตถุดิบ</h5>
                    <p class="text-muted">
                        <?php if (!empty($search) || !empty($selectedType)): ?>
                            ไม่พบข้อมูลที่ตรงกับเงื่อนไขการค้นหา
                        <?php else: ?>
                            เริ่มต้นใช้งานโดยการเพิ่มวัตถุดิบใหม่
                        <?php endif; ?>
                    </p>
                    <?php if (empty($search) && empty($selectedType)): ?>
                        <button class="btn btn-primary" onclick="location.href='materials/add'">
                            <i class="fas fa-plus me-2"></i>เพิ่มวัตถุดิบใหม่
                        </button>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>รหัสสินค้า</th>
                                <th>ชื่อสินค้า</th>
                                <th>บรรจุภัณฑ์</th>
                                <!-- <th>ประเภท</th> -->
                                <th>LR</th>
                                <th>ผู้จัดหา</th>
                                <th>สต็อกขั้นต่ำ</th>
                                <th>ตัวคูณ สต็อกขั้นต่ำ</th>
                                <th>ตำแหน่ง</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($material['product_id']) ?></strong>
                                    </td>
                                    <!-- <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($material['img'])): ?>
                                                <img src="<?= htmlspecialchars($material['img']) ?>" alt="Product Image"
                                                    class="material-thumb me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div class="material-thumb-placeholder me-2">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-medium"><?= htmlspecialchars($material['product_name']) ?></div>
                                            </div>
                                        </div>
                                    </td> -->
                                    <td><?= htmlspecialchars($material['product_name'] ?? '-') ?></td>

                                    <td><?= htmlspecialchars($material['packing'] ?? '-') ?></td>
                                    <!--  <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($types[$material['type']] ?? $material['type']) ?>
                                        </span>
                                    </td> -->
                                    <td>
                                        <?php if ($material['lr']): ?>
                                            <span class="badge bg-<?= $material['lr'] == 'LH' ? 'primary' : 'success' ?>">
                                                <?= htmlspecialchars($material['lr']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($material['supplier'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= number_format($material['min_stock']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= number_format($material['due']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($material['location_plan'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $material['active'] ? 'success' : 'secondary' ?>">
                                            <?= $material['active'] ? 'ใช้งาน' : 'ไม่ใช้งาน' ?>
                                        </span>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="card-footer">
                        <nav aria-label="Material pagination">
                            <ul class="pagination justify-content-center mb-0">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                                ?>

                                <?php if ($startPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                                    </li>
                                    <?php if ($startPage > 2): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($endPage < $totalPages): ?>
                                    <?php if ($endPage < $totalPages - 1): ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>">
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

    <!-- Card View Container -->
    <div id="cardViewContainer" style="display: none;">
        <div class="row">
            <?php foreach ($materials as $material): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 material-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 text-truncate"
                                    title="<?= htmlspecialchars($material['product_name']) ?>">
                                    <?= htmlspecialchars($material['product_name']) ?>
                                </h6>
                                <span class="badge bg-<?= $material['active'] ? 'success' : 'secondary' ?>">
                                    <?= $material['active'] ? 'ใช้งาน' : 'ไม่ใช้งาน' ?>
                                </span>
                            </div>
                            <p class="card-text text-muted small mb-2">
                                <strong>รหัส:</strong> <?= htmlspecialchars($material['product_id']) ?>
                            </p>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <small class="text-muted">ประเภท:</small><br>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($types[$material['type']] ?? $material['type']) ?>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">LR:</small><br>
                                    <?php if ($material['lr']): ?>
                                        <span class="badge bg-<?= $material['lr'] == 'LH' ? 'primary' : 'success' ?>">
                                            <?= htmlspecialchars($material['lr']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <small class="text-muted">สต็อกขั้นต่ำ:</small><br>
                                    <span class="badge bg-info"><?= number_format($material['min_stock']) ?></span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">ตัวคูณ:</small><br>
                                    <span class="badge bg-info"><?= number_format($material['due']) ?></span>
                                </div>
                            </div>
                            <p class="card-text text-muted small mb-2">
                                <strong>ผู้จัดหา:</strong> <?= htmlspecialchars($material['supplier'] ?? '-') ?>
                            </p>
                            <p class="card-text text-muted small mb-2">
                                <strong>ตำแหน่ง:</strong> <?= htmlspecialchars($material['location_plan'] ?? '-') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="viewMaterial(<?= $material['id'] ?>)">
                                    <i class="fas fa-eye"></i> ดู
                                </button>
                                <button type="button" class="btn btn-outline-warning"
                                    onclick="location.href='materials/edit/<?= $material['id'] ?>'">
                                    <i class="fas fa-edit"></i> แก้ไข
                                </button>
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="deleteMaterial(<?= $material['id'] ?>, '<?= htmlspecialchars($material['product_name']) ?>')">
                                    <i class="fas fa-trash"></i> ลบ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Material Detail Modal -->
<div class="modal fade" id="materialDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดวัตถุดิบ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="materialDetailContent">
                <div class="d-flex justify-content-center py-3">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">กำลังโหลด...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
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

    .material-thumb-placeholder {
        width: 40px;
        height: 40px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
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

    .btn-group .btn {
        border-radius: 4px !important;
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .alert {
        border: none;
        border-radius: 8px;
    }

    /* Material Card Styles */
    .material-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e9ecef;
    }

    .material-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .material-card .card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .material-card .card-text {
        font-size: 0.85rem;
    }

    .material-card .badge {
        font-size: 0.75rem;
    }

    .material-card .btn-group .btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }

    /* Filter Summary Styles */
    .filter-summary {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-2 {
            margin-bottom: 1rem;
        }

        .btn-group .btn {
            font-size: 0.75rem;
            padding: 0.2rem 0.4rem;
        }
    }
</style>

<!-- JavaScript -->
<script>
    function viewMaterial(id) {
        const modal = new bootstrap.Modal(document.getElementById('materialDetailModal'));
        const content = document.getElementById('materialDetailContent');

        // Show loading
        content.innerHTML = `
        <div class="d-flex justify-content-center py-3">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">กำลังโหลด...</span>
            </div>
        </div>
    `;

        modal.show();

        // Load material details via AJAX (would need to implement endpoint)
        fetch(`materials/viewMaterial/${id}`)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(error => {
                content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    เกิดข้อผิดพลาดในการโหลดข้อมูล
                </div>
            `;
            });
    }

    function deleteMaterial(id, name) {
        if (confirm(`คุณต้องการลบวัตถุดิบ "${name}" หรือไม่?`)) {
            window.location.href = `materials/delete/${id}`;
        }
    }

    // Auto-submit form on filter change
    document.getElementById('type').addEventListener('change', function () {
        this.closest('form').submit();
    });

    document.getElementById('active').addEventListener('change', function () {
        this.closest('form').submit();
    });

    // Clear search functionality
    function clearSearch() {
        document.getElementById('search').value = '';
        document.getElementById('type').value = '';
        document.getElementById('active').value = '1';
        document.querySelector('form').submit();
    }

    // Add clear search button if there are active filters
    <?php if (!empty($search) || !empty($selectedType) || $selectedActive !== '1'): ?>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            if (searchInput.value || document.getElementById('type').value || document.getElementById('active').value !== '1') {
                const clearBtn = document.createElement('button');
                clearBtn.type = 'button';
                clearBtn.className = 'btn btn-outline-secondary';
                clearBtn.innerHTML = '<i class="fas fa-times me-2"></i>ล้างตัวกรอง';
                clearBtn.onclick = clearSearch;

                const formRow = document.querySelector('.row.g-3');
                const clearCol = document.createElement('div');
                clearCol.className = 'col-md-12 text-end';
                clearCol.appendChild(clearBtn);
                formRow.appendChild(clearCol);
            }
        });
    <?php endif; ?>

    function clearAllFilters() {
        document.getElementById('search').value = '';
        document.getElementById('type').value = '';
        document.getElementById('active').value = '';
        document.getElementById('limit').value = '10'; // Reset limit to default
        document.getElementById('sort').value = 'product_id'; // Reset sort to default
        document.getElementById('order').value = 'ASC'; // Reset order to default
        document.querySelector('form').submit();
    }

    function exportToCSV() {
        const searchParams = new URLSearchParams(window.location.search);
        const params = {
            search: searchParams.get('search'),
            type: searchParams.get('type'),
            active: searchParams.get('active'),
            limit: searchParams.get('limit'),
            sort: searchParams.get('sort'),
            order: searchParams.get('order')
        };

        const url = `materials/exportCSV?${new URLSearchParams(params).toString()}`;
        window.location.href = url;
    }

    // Toggle view mode between table and card
    function toggleViewMode(mode) {
        const tableContainer = document.querySelector('.table-responsive');
        const cardContainer = document.getElementById('cardViewContainer');
        const btnTable = document.getElementById('btnTable');
        const btnCard = document.getElementById('btnCard');

        if (mode === 'table') {
            tableContainer.style.display = 'block';
            if (cardContainer) cardContainer.style.display = 'none';
            btnTable.classList.remove('btn-outline-secondary');
            btnTable.classList.add('btn-secondary');
            btnCard.classList.remove('btn-secondary');
            btnCard.classList.add('btn-outline-secondary');
        } else {
            tableContainer.style.display = 'none';
            if (cardContainer) cardContainer.style.display = 'block';
            btnCard.classList.remove('btn-outline-secondary');
            btnCard.classList.add('btn-secondary');
            btnTable.classList.remove('btn-secondary');
            btnTable.classList.add('btn-outline-secondary');
        }
    }

    // Initialize view mode
    document.addEventListener('DOMContentLoaded', function () {
        // Set default view mode
        toggleViewMode('table');

        // Add auto-submit for new filter options
        document.getElementById('limit').addEventListener('change', function () {
            this.closest('form').submit();
        });

        document.getElementById('sort').addEventListener('change', function () {
            this.closest('form').submit();
        });

        document.getElementById('order').addEventListener('change', function () {
            this.closest('form').submit();
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>