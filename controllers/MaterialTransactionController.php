<?php
/**
 * Material Transaction Controller
 * จัดการการรับเข้า-จ่ายออกวัสดุ
 */
class MaterialTransactionController extends Controller
{
    private $transactionModel;
    private $stockModel;
    private $materialsModel;

    private $db;

    public function __construct()
    {
        // ตรวจสอบสิทธิ์ pc และ admin
        $this->requirePermission(['pc', 'admin']);
        $this->transactionModel = $this->model('MaterialTransactionModel');
        $this->stockModel = $this->model('MaterialStockModel');
        $this->materialsModel = $this->model('MaterialsModel');
        
        // Get database connection for transactions
        $this->db = $this->transactionModel->getDb();
    }

    /**
     * แสดงรายการธุรกรรมทั้งหมด
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [
            'material_id' => isset($_GET['material_id']) ? $_GET['material_id'] : '',
            'transaction_type' => isset($_GET['transaction_type']) ? $_GET['transaction_type'] : '',
            'date_from' => isset($_GET['date_from']) ? $_GET['date_from'] : '',
            'date_to' => isset($_GET['date_to']) ? $_GET['date_to'] : '',
            'search' => isset($_GET['search']) ? trim($_GET['search']) : ''
        ];
        
        $result = $this->transactionModel->getAllTransactions($page, 20, $filters);
        
        $data = [
            'title' => 'รายการธุรกรรมวัสดุ - CPS',
            'transactions' => $result['data'],
            'pagination' => [
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page'],
                'total' => $result['total']
            ],
            'filters' => $filters,
            'materials' => $this->materialsModel->getAllMaterials(1, 1000)['data']
        ];

        $this->view('material_transactions/index', $data);
    }

    /**
     * แสดงฟอร์มรับเข้า
     */
    public function createIn()
    {
        $data = [
            'title' => 'รับเข้าวัสดุ - CPS',
            'materials' => $this->materialsModel->getAllMaterials(1, 1000)['data'],
            'reference_no' => $this->transactionModel->generateReferenceNumber('IN')
        ];

        $this->view('material_transactions/create_in', $data);
    }

    /**
     * แสดงฟอร์มจ่ายออก
     */
    public function createOut()
    {
        $data = [
            'title' => 'จ่ายออกวัสดุ - CPS',
            'materials' => $this->materialsModel->getAllMaterials(1, 1000)['data'],
            'reference_no' => $this->transactionModel->generateReferenceNumber('OUT')
        ];

        $this->view('material_transactions/create_out', $data);
    }

    /**
     * บันทึกการรับเข้า
     */
    public function storeIn()
    {
        if (!$this->isPost()) {
            $this->redirect('material-transactions');
        }

        $material_id = (int)$this->getPost('material_id');
        $total_quantity = (int)$this->getPost('quantity');
        $reference_no = $this->getPost('reference_no');
        $description = $this->getPost('description');
        $unit_price = (float)$this->getPost('unit_price');
        $total_amount = (float)$this->getPost('total_amount');
        $supplier = $this->getPost('supplier');
        $notes = $this->getPost('notes');

        // Get material details
        $material = $this->materialsModel->getMaterialById($material_id);
        if (!$material) {
            $this->redirect('material-transactions?error=Material not found');
            return;
        }

        // Check if material has packing size
        if (empty($material['packing']) || !is_numeric($material['packing'])) {
            $this->redirect('material-transactions?error=Material packing size is required');
            return;
        }

        $packing_size = (int)$material['packing'];
        if ($packing_size <= 0) {
            $this->redirect('material-transactions?error=Invalid packing size');
            return;
        }

        // Calculate packing units
        $full_packing_units = intval($total_quantity / $packing_size);
        $remainder_quantity = $total_quantity % $packing_size;
        $total_packing_units = $full_packing_units + ($remainder_quantity > 0 ? 1 : 0);

        // Validation
        $errors = [];
        if ($total_quantity <= 0) {
            $errors[] = 'จำนวนต้องมากกว่า 0';
        }
        if (empty($reference_no)) {
            $errors[] = 'กรุณากรอกเลขที่อ้างอิง';
        }
        if (empty($supplier)) {
            $errors[] = 'กรุณากรอกชื่อซัพพลายเออร์';
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'title' => 'รับเข้าวัสดุ - CPS',
                'materials' => $this->materialsModel->getAllMaterials(1, 1000)['data'],
                'material_id' => isset($material_id) ? $material_id : '',
                'quantity' => isset($total_quantity) ? $total_quantity : '',
                'reference_no' => isset($reference_no) ? $reference_no : '',
                'description' => isset($description) ? $description : '',
                'unit_price' => isset($unit_price) ? $unit_price : 0,
                'total_amount' => isset($total_amount) ? $total_amount : 0,
                'supplier' => isset($supplier) ? $supplier : '',
                'notes' => isset($notes) ? $notes : ''
            ];
            $this->view('material_transactions/create_in', $data);
            return;
        }

        // Start transaction
        $this->db->beginTransaction();
        
        try {
            $created_transactions = [];

            // Generate new reference number for this transaction batch
            $new_reference_no = $this->transactionModel->generateReferenceNumber('IN');

            // Create transaction records for each packing unit
            for ($i = 1; $i <= $total_packing_units; $i++) {
                $current_quantity = ($i <= $full_packing_units) ? $packing_size : $remainder_quantity;
                
                // Generate QR code for this packing unit
                $qr_code = $this->transactionModel->generateQrCode($material_id, $i);
                
                // Create transaction data
                $transaction_data = [
                    'material_id' => $material_id,
                    'transaction_type' => 'IN',
                    'quantity' => $current_quantity,
                    'reference_no' => $new_reference_no,
                    'description' => $description . ' (Packing Unit ' . $i . ' of ' . $total_packing_units . ')',
                    'unit_price' => $unit_price,
                    'total_amount' => $current_quantity * $unit_price,
                    'supplier' => $supplier,
                    'recipient' => null,
                    'created_by' => $this->getCurrentUser()['id'],
                    'notes' => $notes . ' - Packing Unit ' . $i,
                    'qr_code' => $qr_code
                ];

                // Create transaction record
                $transaction_id = $this->transactionModel->createTransaction($transaction_data);
                if (!$transaction_id) {
                    throw new Exception('Failed to create transaction record for packing unit ' . $i);
                }
                $created_transactions[] = $transaction_id;
            }

            // Update stock from transactions (automatic calculation)
            $this->stockModel->updateStockFromTransactions($material_id);
            
            $this->db->commit();
            
            // Redirect with success message
            $this->redirect("material-transactions?success=1&total_units={$total_packing_units}");
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $data = [
                'errors' => ['เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()],
                'title' => 'รับเข้าวัสดุ - CPS',
                'materials' => $this->materialsModel->getAllMaterials(1, 1000)['data'],
                'material_id' => isset($material_id) ? $material_id : '',
                'quantity' => isset($total_quantity) ? $total_quantity : '',
                'reference_no' => isset($reference_no) ? $reference_no : '',
                'description' => isset($description) ? $description : '',
                'unit_price' => isset($unit_price) ? $unit_price : 0,
                'total_amount' => isset($total_amount) ? $total_amount : 0,
                'supplier' => isset($supplier) ? $supplier : '',
                'notes' => isset($notes) ? $notes : ''
            ];
            $this->view('material_transactions/create_in', $data);
        }
    }

    /**
     * บันทึกการจ่ายออก
     */
    public function storeOut()
    {
        if (!$this->isPost()) {
            $this->redirect('material-transactions');
        }

        $data = [
            'material_id' => (int)$this->getPost('material_id'),
            'transaction_type' => 'OUT',
            'quantity' => (int)$this->getPost('quantity'),
            'reference_no' => $this->getPost('reference_no'),
            'description' => $this->getPost('description'),
            'unit_price' => 0.00,
            'total_amount' => 0.00,
            'supplier' => null,
            'recipient' => $this->getPost('recipient'),
            'created_by' => $this->getCurrentUser()['id'],
            'notes' => $this->getPost('notes')
        ];

        // Validation
        $errors = $this->validateTransaction($data);
        
        // Check stock availability
        $current_stock = $this->stockModel->getCurrentStock($data['material_id']);
        if ($current_stock && $current_stock['current_qty'] < $data['quantity']) {
            $errors[] = 'สต็อกไม่เพียงพอ (สต็อกปัจจุบัน: ' . $current_stock['current_qty'] . ')';
        }
        
        if (!empty($errors)) {
            $data['errors'] = $errors;
            $data['title'] = 'จ่ายออกวัสดุ - CPS';
            $data['materials'] = $this->materialsModel->getAllMaterials(1, 1000)['data'];
            $this->view('material_transactions/create_out', $data);
            return;
        }

        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Create transaction record
            $transaction_id = $this->transactionModel->createTransaction($data);
            
            if ($transaction_id) {
                // Update stock from transactions (automatic calculation)
                $this->stockModel->updateStockFromTransactions($data['material_id']);
                
                $this->db->commit();
                $this->redirect('material-transactions?success=2');
            } else {
                throw new Exception('Failed to create transaction');
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            $data['errors'] = ['เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()];
            $data['title'] = 'จ่ายออกวัสดุ - CPS';
            $data['materials'] = $this->materialsModel->getAllMaterials(1, 1000)['data'];
            $this->view('material_transactions/create_out', $data);
        }
    }

    /**
     * แสดงรายละเอียดธุรกรรม
     */
    public function show($id)
    {
        $transaction = $this->transactionModel->getTransactionById($id);
        
        if (!$transaction) {
            $this->redirect('material-transactions?error=Transaction not found');
        }

        $data = [
            'title' => 'รายละเอียดธุรกรรม - CPS',
            'transaction' => $transaction
        ];

        $this->view('material_transactions/show', $data);
    }

    /**
     * แสดงรายงานสต็อก
     */
    public function stock()
    {
        // Update all stock from transactions first
        $this->stockModel->updateAllStockFromTransactions();
        
        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
        
        // Get stock data with pagination
        $result = $this->stockModel->getAllCurrentStockFromTransactionsPaginated($page, $per_page, $search, $status_filter);
        
        $data = [
            'title' => 'รายงานสต็อกวัสดุ - CPS',
            'stock_items' => $result['data'],
            'summary' => $this->stockModel->getStockSummary(),
            'low_stock' => $this->stockModel->getLowStockMaterials(),
            'pagination' => [
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page'],
                'total' => $result['total'],
                'per_page' => $per_page
            ],
            'filters' => [
                'search' => $search,
                'status' => $status_filter
            ]
        ];

        $this->view('material_transactions/stock', $data);
    }

    /**
     * แสดงรายงานสรุปธุรกรรม
     */
    public function report()
    {
        $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-01');
        $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
        $material_id = isset($_GET['material_id']) ? $_GET['material_id'] : null;

        $data = [
            'title' => 'รายงานธุรกรรมวัสดุ - CPS',
            'summary' => $this->transactionModel->getTransactionSummary($material_id, $date_from, $date_to),
            'recent_transactions' => $this->transactionModel->getRecentTransactions(10),
            'materials' => $this->materialsModel->getAllMaterials(1, 1000)['data'],
            'filters' => [
                'date_from' => $date_from,
                'date_to' => $date_to,
                'material_id' => $material_id
            ]
        ];

        $this->view('material_transactions/report', $data);
    }

    /**
     * Validation helper
     */
    private function validateTransaction($data)
    {
        $errors = [];
        
        if (empty($data['material_id'])) {
            $errors[] = 'กรุณาเลือกวัสดุ';
        }
        
        if (empty($data['quantity']) || $data['quantity'] <= 0) {
            $errors[] = 'กรุณากรอกจำนวนที่ถูกต้อง';
        }
        
        if (empty($data['reference_no'])) {
            $errors[] = 'กรุณากรอกรหัสอ้างอิง';
        }
        
        if (empty($data['description'])) {
            $errors[] = 'กรุณากรอกรายละเอียด';
        }
        
        if ($data['transaction_type'] === 'IN') {
            if (empty($data['supplier'])) {
                $errors[] = 'กรุณากรอกซัพพลายเออร์';
            }
            if ($data['unit_price'] < 0) {
                $errors[] = 'ราคาต่อหน่วยต้องไม่ติดลบ';
            }
        } else {
            if (empty($data['recipient'])) {
                $errors[] = 'กรุณากรอกผู้รับ';
            }
        }
        
        return $errors;
    }


}
?> 