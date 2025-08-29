<?php
require_once 'core/BaseModel.php';

class MaterialTransactionModel extends BaseModel {
    protected $table = 'material_transactions';
    
    public function __construct($database) {
        parent::__construct($database);
    }
    
    public function getTransactions($search = '', $type = '', $dateFrom = '', $dateTo = '', $limit = 20, $offset = 0) {
        $whereConditions = [];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(mt.product_id LIKE ? OR mt.transaction_no LIKE ? OR m.product_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($type)) {
            $whereConditions[] = "mt.transaction_type = ?";
            $params[] = $type;
        }
        
        if (!empty($dateFrom)) {
            $whereConditions[] = "mt.date_transaction >= ?";
            $params[] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $whereConditions[] = "mt.date_transaction <= ?";
            $params[] = $dateTo;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $sql = "SELECT mt.*, m.product_name, m.packing 
                FROM material_transactions mt 
                LEFT JOIN material m ON mt.product_id = m.product_id 
                $whereClause 
                ORDER BY mt.date_transaction DESC, mt.create_date DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTransactionCount($search = '', $type = '', $dateFrom = '', $dateTo = '') {
        $whereConditions = [];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(mt.product_id LIKE ? OR mt.transaction_no LIKE ? OR m.product_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($type)) {
            $whereConditions[] = "mt.transaction_type = ?";
            $params[] = $type;
        }
        
        if (!empty($dateFrom)) {
            $whereConditions[] = "mt.date_transaction >= ?";
            $params[] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $whereConditions[] = "mt.date_transaction <= ?";
            $params[] = $dateTo;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $sql = "SELECT COUNT(*) FROM material_transactions mt LEFT JOIN material m ON mt.product_id = m.product_id $whereClause";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public function getStatistics() {
        $today = date('Y-m-d');
        
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(unit_received), 0) FROM material_transactions WHERE transaction_type = 'IN' AND date_transaction = ?");
        $stmt->execute([$today]);
        $totalReceived = $stmt->fetchColumn();
        
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(unit), 0) FROM material_transactions WHERE transaction_type = 'OUT' AND date_transaction = ?");
        $stmt->execute([$today]);
        $totalIssued = $stmt->fetchColumn();
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM material_transactions");
        $stmt->execute();
        $totalTransactions = $stmt->fetchColumn();
        
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(balance), 0) FROM material_stock");
        $stmt->execute();
        $totalStock = $stmt->fetchColumn();
        
        return compact('totalReceived', 'totalIssued', 'totalTransactions', 'totalStock');
    }
    
    public function getMaterials() {
        $stmt = $this->db->prepare("SELECT product_id, product_name, packing FROM material WHERE active = 1 ORDER BY product_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStockItems() {
        $stmt = $this->db->prepare("
            SELECT ms.product_id, m.product_name, ms.balance 
            FROM material_stock ms 
            JOIN material m ON ms.product_id = m.product_id 
            WHERE m.active = 1 AND ms.balance > 0 
            ORDER BY m.product_name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStockData($search = '', $limit = 20, $offset = 0) {
        $whereConditions = [];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(mti.product_id LIKE ? OR m.product_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $sql = "SELECT mti.product_id, m.product_name, SUM(mti.unit) as total_balance, COUNT(*) as item_count
                FROM material_transactions_detail mti 
                LEFT JOIN material m ON mti.product_id = m.product_id COLLATE utf8mb4_unicode_ci
                {$whereClause} 
                GROUP BY mti.product_id, m.product_name
                HAVING total_balance > 0
                ORDER BY mti.product_id 
                LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStockCount($search = '') {
        $whereConditions = [];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(mti.product_id LIKE ? OR m.product_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $sql = "SELECT COUNT(*) FROM (
                    SELECT mti.product_id
                    FROM material_transactions_detail mti 
                    LEFT JOIN material m ON mti.product_id = m.product_id COLLATE utf8mb4_unicode_ci
                    {$whereClause}
                    GROUP BY mti.product_id
                    HAVING SUM(mti.unit) > 0
                ) as stock_count";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public function getStockStatistics() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM material_transactions_detail WHERE unit > 0");
        $stmt->execute();
        $totalItems = $stmt->fetchColumn();
        
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(unit), 0) FROM material_transactions_detail WHERE unit > 0");
        $stmt->execute();
        $totalBalance = $stmt->fetchColumn();
        
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT transaction_no) FROM material_transactions_detail WHERE unit > 0");
        $stmt->execute();
        $totalLots = $stmt->fetchColumn();
        
        // Low stock items (set to 0 if no min_stock column)
        $lowStockItems = 0;
        
        return compact('totalItems', 'totalBalance', 'totalLots', 'lowStockItems');
    }
    
    public function getMaterial($productId) {
        $stmt = $this->db->prepare("SELECT id, packing FROM material WHERE product_id = ? COLLATE utf8mb4_unicode_ci AND active = 1");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }
    
    public function generateTransactionNo($materialId, $type) {
        $date = date('Ymd');
        $stmt = $this->db->prepare("SELECT transaction_no FROM material_transactions WHERE transaction_no LIKE ? ORDER BY create_date DESC LIMIT 1");
        $stmt->execute([$materialId . $date . $type . '%']);
        $existingTransactionNo = $stmt->fetchColumn();
        
        if ($existingTransactionNo) {
            return $existingTransactionNo;
        } else {
            $stmt = $this->db->prepare("SELECT COALESCE(MAX(CAST(SUBSTRING(transaction_no, -3) AS UNSIGNED)), 0) + 1 as next_running FROM material_transactions WHERE transaction_no LIKE ?");
            $stmt->execute([$materialId . $date . '%']);
            $running = str_pad($stmt->fetchColumn(), 3, '0', STR_PAD_LEFT);
            return $materialId . $date . $type . $running;
        }
    }
    
    public function receiveTransaction($transactionNo, $productId, $unitReceived, $calculatedPieces, $packing, $remark, $createdBy) {
        $this->db->beginTransaction();
        
        try {
            // Insert main transaction
            $stmt = $this->db->prepare("INSERT INTO material_transactions (transaction_no, product_id, unit_received, calculated_pieces, unit, transaction_type, date_transaction, remark, created_by, date_revice) VALUES (?, ?, ?, ?, ?, 'IN', CURDATE(), ?, ?, NOW())");
            $stmt->execute([$transactionNo, $productId, $unitReceived, $calculatedPieces, $calculatedPieces, $remark, $createdBy]);
            
            // Insert detail records for each calculated piece
            for ($i = 1; $i <= $calculatedPieces; $i++) {
                $qrCode = $transactionNo . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                $unitPerPiece = ($i <= floor($unitReceived / $packing)) ? $packing : ($unitReceived % $packing);
                $stmt = $this->db->prepare("INSERT INTO material_transactions_detail (transaction_no, product_id, unit, qr_code, transaction_type, date_revice) VALUES (?, ?, ?, ?, 'IN', NOW())");
                $stmt->execute([$transactionNo, $productId, $unitPerPiece, $qrCode]);
            }
            
            // Insert into material_transactions_info for each piece
            for ($i = 1; $i <= $calculatedPieces; $i++) {
                $unitPerPiece = ($i <= floor($unitReceived / $packing)) ? $packing : ($unitReceived % $packing);
                $stmt = $this->db->prepare("INSERT INTO material_transactions_info (run_id, w, balance, status, date_revice) VALUES (?, ?, ?, 1, NOW())");
                $stmt->execute([$i, $productId, $unitPerPiece]);
            }
            
            $this->updateStock($productId);
            $this->db->commit();
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function issueTransaction($transactionNo, $productId, $unit, $remark, $createdBy) {
        // Get material packing
        $stmt = $this->db->prepare("SELECT packing FROM material WHERE product_id = ? COLLATE utf8mb4_unicode_ci");
        $stmt->execute([$productId]);
        $packing = $stmt->fetchColumn() ?: 1;
        
        // Calculate pieces to issue
        $piecesToIssue = ceil($unit / $packing);
        
        $this->db->beginTransaction();
        
        try {
            // Insert main transaction
            $stmt = $this->db->prepare("INSERT INTO material_transactions (transaction_no, product_id, unit_received, calculated_pieces, unit, transaction_type, date_transaction, remark, created_by, date_revice) VALUES (?, ?, 0, 0, ?, 'OUT', CURDATE(), ?, ?, NOW())");
            $stmt->execute([$transactionNo, $productId, $unit, $remark, $createdBy]);
            
            // FIFO: Get oldest items from material_transactions_info
            $stmt = $this->db->prepare("
                SELECT id, balance 
                FROM material_transactions_info 
                WHERE w = ? COLLATE utf8mb4_unicode_ci AND balance > 0 AND status = 1
                ORDER BY date_revice ASC, id ASC
            ");
            $stmt->execute([$productId]);
            $availableItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $remainingToIssue = $unit;
            $issueCount = 0;
            
            foreach ($availableItems as $item) {
                if ($remainingToIssue <= 0) break;
                
                $issueFromThis = min($remainingToIssue, $item['balance']);
                $newBalance = $item['balance'] - $issueFromThis;
                
                // Update material_transactions_info
                if ($newBalance > 0) {
                    $stmt = $this->db->prepare("UPDATE material_transactions_info SET balance = ? WHERE id = ?");
                    $stmt->execute([$newBalance, $item['id']]);
                } else {
                    $stmt = $this->db->prepare("UPDATE material_transactions_info SET balance = 0, status = 0 WHERE id = ?");
                    $stmt->execute([$item['id']]);
                }
                
                // Insert issue record in detail
                $issueCount++;
                $qrCode = $transactionNo . '-OUT-' . str_pad($issueCount, 3, '0', STR_PAD_LEFT);
                $stmt = $this->db->prepare("INSERT INTO material_transactions_detail (transaction_no, product_id, unit, qr_code, transaction_type, date_revice) VALUES (?, ?, ?, ?, 'OUT', NOW())");
                $stmt->execute([$transactionNo, $productId, -$issueFromThis, $qrCode]);
                
                $remainingToIssue -= $issueFromThis;
            }
            
            if ($remainingToIssue > 0) {
                throw new Exception('ยอดคงเหลือไม่เพียงพอ');
            }
            
            $this->updateStock($productId);
            $this->db->commit();
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function updateStock($productId) {
        $stmt = $this->db->prepare("
            INSERT INTO material_stock (product_id, balance) 
            SELECT ?, COALESCE(SUM(balance), 0) 
            FROM material_transactions_info 
            WHERE w = ? COLLATE utf8mb4_unicode_ci AND status = 1
            ON DUPLICATE KEY UPDATE balance = VALUES(balance)
        ");
        $stmt->execute([$productId, $productId]);
        
        $stmt = $this->db->prepare("DELETE FROM material_stock WHERE product_id = ? COLLATE utf8mb4_unicode_ci AND balance <= 0");
        $stmt->execute([$productId]);
    }
    
    public function getTransactionDetail($transactionNo) {
        $stmt = $this->db->prepare("
            SELECT mt.*, m.product_name, m.packing 
            FROM material_transactions mt 
            LEFT JOIN material m ON mt.product_id = m.product_id COLLATE utf8mb4_unicode_ci
            WHERE mt.transaction_no = ? 
            LIMIT 1
        ");
        $stmt->execute([$transactionNo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getTransactionDetails($transactionNo) {
        $stmt = $this->db->prepare("
            SELECT * FROM material_transactions_detail 
            WHERE transaction_no = ? 
            ORDER BY id
        ");
        $stmt->execute([$transactionNo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>