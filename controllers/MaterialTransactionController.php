<?php
require_once 'core/BaseController.php';
require_once 'models/MaterialTransactionModel.php';

class MaterialTransactionController extends BaseController {
    private $model;
    
    public function __construct($database) {
        parent::__construct($database);
        $this->model = new MaterialTransactionModel($database);
    }
    
    public function index() {
        $search = $_GET['search'] ?? '';
        $selectedType = $_GET['transaction_type'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        $limit = (int)($_GET['limit'] ?? 20);
        $currentPage = (int)($_GET['page'] ?? 1);
        $offset = ($currentPage - 1) * $limit;
        
        $transactions = $this->model->getTransactions($search, $selectedType, $dateFrom, $dateTo, $limit, $offset);
        $totalCount = $this->model->getTransactionCount($search, $selectedType, $dateFrom, $dateTo);
        $totalPages = ceil($totalCount / $limit);
        
        $statistics = $this->model->getStatistics();
        extract($statistics);
        
        $materials = $this->model->getMaterials();
        $stockItems = $this->model->getStockItems();
        
        $this->view('materials/transactions', compact(
            'transactions', 'totalCount', 'totalPages', 'currentPage', 'limit',
            'search', 'selectedType', 'dateFrom', 'dateTo',
            'totalReceived', 'totalIssued', 'totalTransactions', 'totalStock',
            'materials', 'stockItems'
        ));
    }
    
    public function receive() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('materials/transactions');
        }
        
        try {
            $productId = $_POST['product_id'] ?? '';
            $unitReceived = (int)($_POST['unit_received'] ?? 0);
            $remark = $_POST['remark'] ?? '';
            $createdBy = $_SESSION['user_id'] ?? 'SYSTEM';
            
            if (empty($productId) || $unitReceived <= 0) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }
            
            $material = $this->model->getMaterial($productId);
            if (!$material) {
                throw new Exception('ไม่พบข้อมูลสินค้า');
            }
            
            $materialId = $material['id'];
            $packing = $material['packing'] ?: 1;
            $calculatedPieces = ceil($unitReceived / $packing);
            
            $transactionNo = $this->model->generateTransactionNo($materialId, 'IN');
            
            $this->model->receiveTransaction($transactionNo, $productId, $unitReceived, $calculatedPieces, $packing, $remark, $createdBy);
            
            $this->redirect('materials/transactions', 'บันทึกการรับเข้าเรียบร้อยแล้ว');
            
        } catch (Exception $e) {
            $this->redirect('materials/transactions', 'เกิดข้อผิดพลาด: ' . $e->getMessage(), 'error');
        }
    }
    
    public function detail() {
        $transactionNo = $_GET['transaction_no'] ?? '';
        if (empty($transactionNo)) {
            $this->redirect('materials/transactions', 'ไม่พบเลขที่เอกสาร', 'error');
        }
        
        $transaction = $this->model->getTransactionDetail($transactionNo);
        if (!$transaction) {
            $this->redirect('materials/transactions', 'ไม่พบข้อมูลธุรกรรม', 'error');
        }
        
        $details = $this->model->getTransactionDetails($transactionNo);
        
        $this->view('materials/transaction_detail', compact('transaction', 'details'));
    }
    
    public function stock() {
        $search = $_GET['search'] ?? '';
        $limit = (int)($_GET['limit'] ?? 20);
        $currentPage = (int)($_GET['page'] ?? 1);
        $offset = ($currentPage - 1) * $limit;
        
        $stockData = $this->model->getStockData($search, $limit, $offset);
        $totalCount = $this->model->getStockCount($search);
        $totalPages = ceil($totalCount / $limit);
        
        $statistics = $this->model->getStockStatistics();
        extract($statistics);
        
        $this->view('materials/stock', compact(
            'stockData', 'totalCount', 'totalPages', 'currentPage', 'limit', 'search',
            'totalItems', 'totalBalance', 'totalLots', 'lowStockItems'
        ));
    }
    
    public function issue() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('materials/transactions');
        }
        
        try {
            $productId = $_POST['product_id'] ?? '';
            $unit = (int)($_POST['unit'] ?? 0);
            $remark = $_POST['remark'] ?? '';
            $createdBy = $_SESSION['user_id'] ?? 'SYSTEM';
            
            if (empty($productId) || $unit <= 0) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }
            
            $material = $this->model->getMaterial($productId);
            if (!$material) {
                throw new Exception('ไม่พบข้อมูลสินค้า');
            }
            
            $materialId = $material['id'];
            $transactionNo = $this->model->generateTransactionNo($materialId, 'OUT');
            
            $this->model->issueTransaction($transactionNo, $productId, $unit, $remark, $createdBy);
            
            $this->redirect('materials/transactions', 'บันทึกการจ่ายออกเรียบร้อยแล้ว');
            
        } catch (Exception $e) {
            $this->redirect('materials/transactions', 'เกิดข้อผิดพลาด: ' . $e->getMessage(), 'error');
        }
    }
}
?>