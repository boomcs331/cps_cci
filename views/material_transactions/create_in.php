<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-plus me-2"></i>รับเข้าวัสดุ</h3>
                </div>
                <div class="card-body">
                    <!-- Error Messages -->
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= BASE_URL ?>material-transactions/store-in">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reference_no" class="form-label">รหัสอ้างอิง <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="reference_no" name="reference_no"
                                        value="<?= htmlspecialchars($reference_no) ?>" required readonly>
                                    <div class="form-text">รหัสอ้างอิงจะถูกสร้างอัตโนมัติ</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="material_id" class="form-label">วัสดุ <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="material_id" name="material_id" required>
                                        <option value="">เลือกวัสดุ</option>
                                        <?php foreach ($materials as $material): ?>
                                            <option value="<?= $material['id'] ?>" <?= isset($_POST['material_id']) && $_POST['material_id'] == $material['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($material['mat_id']) ?> -
                                                <?= htmlspecialchars($material['mat_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">จำนวน <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '' ?>"
                                        min="1" required>
                                    <div class="form-text">ระบบจะแบ่งตามขนาด packing ของวัสดุ</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">ราคาต่อหน่วย</label>
                                    <input type="number" class="form-control" id="unit_price" name="unit_price"
                                        value="<?= isset($_POST['unit_price']) ? htmlspecialchars($_POST['unit_price']) : '0' ?>"
                                        min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <!-- Packing Information Display -->
                        <div class="alert alert-info" id="packing-info" style="display: none;">
                            <h6><i class="fas fa-info-circle me-2"></i>ข้อมูล Packing</h6>
                            <div id="packing-details"></div>
                            <div id="qr-code-preview" class="mt-3" style="display: none;">
                                <h6><i class="fas fa-qrcode me-2"></i>รหัส QR ที่จะถูกสร้าง:</h6>
                                <div id="qr-code-list" class="small"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_amount" class="form-label">จำนวนเงินรวม</label>
                                    <input type="number" class="form-control" id="total_amount" name="total_amount"
                                        value="<?= isset($_POST['total_amount']) ? htmlspecialchars($_POST['total_amount']) : '0' ?>"
                                        min="0" step="0.01" readonly>
                                    <div class="form-text">จะคำนวณอัตโนมัติจาก จำนวน × ราคาต่อหน่วย</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier" class="form-label">ซัพพลายเออร์ <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="supplier" name="supplier"
                                        value="<?= isset($_POST['supplier']) ? htmlspecialchars($_POST['supplier']) : '' ?>"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">รายละเอียด <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">หมายเหตุ</label>
                            <textarea class="form-control" id="notes" name="notes"
                                rows="2"><?= isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '' ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>material-transactions" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับ
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>บันทึกการรับเข้า
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Material data for packing calculations
    const materials = <?= json_encode($materials) ?>;

    // Auto-calculate total amount
    document.getElementById('quantity').addEventListener('input', calculateTotal);
    document.getElementById('unit_price').addEventListener('input', calculateTotal);
    document.getElementById('material_id').addEventListener('change', showPackingInfo);
    document.getElementById('quantity').addEventListener('input', showPackingInfo);

    function calculateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
        const totalAmount = quantity * unitPrice;
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
    }

    function showPackingInfo() {
        const materialId = document.getElementById('material_id').value;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const packingInfo = document.getElementById('packing-info');
        const packingDetails = document.getElementById('packing-details');
        const qrCodePreview = document.getElementById('qr-code-preview');
        const qrCodeList = document.getElementById('qr-code-list');
        
        if (!materialId || quantity <= 0) {
            packingInfo.style.display = 'none';
            return;
        }
        
        // Find selected material
        const material = materials.find(m => m.id == materialId);
        if (!material || !material.packing || isNaN(material.packing) || material.packing <= 0) {
            packingInfo.style.display = 'none';
            return;
        }
        
        const packingSize = parseInt(material.packing);
        const fullUnits = Math.floor(quantity / packingSize);
        const remainder = quantity % packingSize;
        const totalUnits = fullUnits + (remainder > 0 ? 1 : 0);
        
        let details = `
            <div class="row">
                <div class="col-md-3">
                    <strong>ขนาด Packing:</strong> ${packingSize} หน่วย
                </div>
                <div class="col-md-3">
                    <strong>จำนวนเต็ม:</strong> ${fullUnits} กล่อง
                </div>
                <div class="col-md-3">
                    <strong>เศษเหลือ:</strong> ${remainder} หน่วย
                </div>
                <div class="col-md-3">
                    <strong>รวม QR Code:</strong> ${totalUnits} รายการ
                </div>
            </div>
        `;
        
        if (totalUnits > 0) {
            details += '<div class="mt-2"><strong>รายละเอียดการแบ่ง:</strong><ul class="mb-0 mt-1">';
            for (let i = 1; i <= totalUnits; i++) {
                const unitQuantity = (i <= fullUnits) ? packingSize : remainder;
                details += `<li>QR Code ${i}: ${unitQuantity} หน่วย</li>`;
            }
            details += '</ul></div>';
        }
        
        packingDetails.innerHTML = details;
        packingInfo.style.display = 'block';
        
        // Show QR code preview
        if (totalUnits > 0) {
            generateQrCodePreview(material.mat_id, totalUnits);
            qrCodePreview.style.display = 'block';
        } else {
            qrCodePreview.style.display = 'none';
        }
    }

    function generateQrCodePreview(matId, totalUnits) {
        const qrCodeList = document.getElementById('qr-code-list');
        const today = new Date();
        const dateTh = today.getDate().toString().padStart(2, '0') + 
                       (today.getMonth() + 1).toString().padStart(2, '0') + 
                       today.getFullYear().toString().slice(-2);
        
        let qrCodes = '';
        // Note: The actual run numbers will be calculated by the backend
        // based on existing QR codes for today, but for preview we show sequential numbers
        for (let i = 1; i <= totalUnits; i++) {
            const runNumber = i.toString().padStart(4, '0');
            const qrCode = `${matId}-${dateTh}-${runNumber}`;
            qrCodes += `<div class="badge bg-primary me-2 mb-1">${qrCode}</div>`;
        }
        
        qrCodeList.innerHTML = qrCodes;
    }

    // Initialize packing info on page load
    document.addEventListener('DOMContentLoaded', function () {
        showPackingInfo();
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>