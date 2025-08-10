<?php
/**
 * Material Transaction Model
 * จัดการข้อมูลการรับเข้า-จ่ายออกวัสดุ
 */
class MaterialTransactionModel extends Model
{
    protected $table = 'material_transactions';

    /**
     * Create a new transaction
     */
    public function createTransaction($data)
    {
        $fields = [
            'material_id', 'transaction_type', 'quantity', 'reference_no', 
            'description', 'unit_price', 'total_amount', 'supplier', 
            'recipient', 'created_by', 'notes', 'qr_code'
        ];
        $filtered_data = array_intersect_key($data, array_flip($fields));
        
        return $this->create($filtered_data);
    }

    /**
     * Get all transactions with pagination
     */
    public function getAllTransactions($page = 1, $per_page = 20, $filters = [])
    {
        $offset = ($page - 1) * $per_page;
        
        $where_conditions = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['material_id'])) {
            $where_conditions[] = "mt.material_id = :material_id";
            $params[':material_id'] = $filters['material_id'];
        }
        
        if (!empty($filters['transaction_type'])) {
            $where_conditions[] = "mt.transaction_type = :transaction_type";
            $params[':transaction_type'] = $filters['transaction_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $where_conditions[] = "DATE(mt.transaction_date) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where_conditions[] = "DATE(mt.transaction_date) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        if (!empty($filters['search'])) {
            $where_conditions[] = "(mt.reference_no LIKE :search OR mt.description LIKE :search OR m.mat_id LIKE :search OR m.mat_name LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }
        
        $where_clause = '';
        if (!empty($where_conditions)) {
            $where_clause = "WHERE " . implode(" AND ", $where_conditions);
        }
        
        // Count total records
        $count_sql = "SELECT COUNT(*) as total 
                      FROM {$this->table} mt 
                      JOIN materials m ON mt.material_id = m.id 
                      {$where_clause}";
        $count_stmt = $this->db->prepare($count_sql);
        foreach ($params as $key => $value) {
            $count_stmt->bindValue($key, $value);
        }
        $count_stmt->execute();
        $total = $count_stmt->fetch()['total'];
        
        // Get transactions
        $sql = "SELECT mt.*, m.mat_id, m.mat_name, u.user_id as created_by_user 
                FROM {$this->table} mt 
                JOIN materials m ON mt.material_id = m.id 
                LEFT JOIN users u ON mt.created_by = u.id 
                {$where_clause} 
                ORDER BY mt.transaction_date DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $per_page,
            'current_page' => $page,
            'last_page' => ceil($total / $per_page)
        ];
    }

    /**
     * Get transaction by ID
     */
    public function getTransactionById($id)
    {
        $sql = "SELECT mt.*, m.mat_id, m.mat_name, u.user_id as created_by_user 
                FROM {$this->table} mt 
                JOIN materials m ON mt.material_id = m.id 
                LEFT JOIN users u ON mt.created_by = u.id 
                WHERE mt.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Get transactions for a specific material
     */
    public function getTransactionsByMaterial($material_id, $limit = 50)
    {
        $sql = "SELECT mt.*, m.mat_id, m.mat_name, u.user_id as created_by_user 
                FROM {$this->table} mt 
                JOIN materials m ON mt.material_id = m.id 
                LEFT JOIN users u ON mt.created_by = u.id 
                WHERE mt.material_id = :material_id 
                ORDER BY mt.transaction_date DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':material_id', $material_id);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get transaction summary by type
     */
    public function getTransactionSummary($material_id = null, $date_from = null, $date_to = null)
    {
        $where_conditions = [];
        $params = [];
        
        if ($material_id) {
            $where_conditions[] = "material_id = :material_id";
            $params[':material_id'] = $material_id;
        }
        
        if ($date_from) {
            $where_conditions[] = "DATE(transaction_date) >= :date_from";
            $params[':date_from'] = $date_from;
        }
        
        if ($date_to) {
            $where_conditions[] = "DATE(transaction_date) <= :date_to";
            $params[':date_to'] = $date_to;
        }
        
        $where_clause = '';
        if (!empty($where_conditions)) {
            $where_clause = "WHERE " . implode(" AND ", $where_conditions);
        }
        
        $sql = "SELECT 
                    transaction_type,
                    COUNT(*) as transaction_count,
                    SUM(quantity) as total_quantity,
                    SUM(total_amount) as total_amount
                FROM {$this->table} 
                {$where_clause} 
                GROUP BY transaction_type";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get recent transactions
     */
    public function getRecentTransactions($limit = 10)
    {
        $sql = "SELECT mt.*, m.mat_id, m.mat_name, u.user_id as created_by_user 
                FROM {$this->table} mt 
                JOIN materials m ON mt.material_id = m.id 
                LEFT JOIN users u ON mt.created_by = u.id 
                ORDER BY mt.transaction_date DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Generate reference number
     * Format: PO-YYYYMM00000001, REQ-YYYYMM00000001
     */
    public function generateReferenceNumber($type = 'IN')
    {
        $prefix = $type === 'IN' ? 'PO' : 'REQ';
        $year = date('Y');
        $month = date('m');
        
        // Get the last reference number for this year-month
        $sql = "SELECT reference_no FROM {$this->table} 
                WHERE reference_no LIKE :pattern 
                ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $pattern = "{$prefix}-{$year}{$month}%";
        $stmt->bindValue(':pattern', $pattern);
        $stmt->execute();
        $last_ref = $stmt->fetch();
        
        if ($last_ref) {
            // Extract the number part (last 8 digits)
            $number_part = substr($last_ref['reference_no'], -8);
            $last_number = (int)$number_part;
            $new_number = $last_number + 1;
        } else {
            // If no reference number for this year-month, start from 1
            $new_number = 1;
        }
        
        // Format: PO-YYYYMM00000001 (8 digits with leading zeros)
        return sprintf("%s-%s%02d%08d", $prefix, $year, $month, $new_number);
    }

    /**
     * Generate QR code for material transaction
     * Format: mat_id - dateTh(010168)-runnumber(4 digits)
     */
    public function generateQrCode($material_id, $packing_unit_number = 1)
    {
        // Get material mat_id
        $sql = "SELECT mat_id FROM materials WHERE id = :material_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':material_id', $material_id);
        $stmt->execute();
        $material = $stmt->fetch();
        
        if (!$material) {
            return false;
        }
        
        $mat_id = $material['mat_id'];
        
        // Get current date in Thai format (ddmmyy) - ปี พ.ศ. 2568 = 68
        $date_th = date('dm') . (date('Y') + 543 - 2500);
        
        // Get run number (4 digits) for this material today
        $today = date('Y-m-d');
        
        // Get the next sequential run number for today
        $sql = "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(qr_code, '-', -1) AS UNSIGNED)), 0) as max_run 
                FROM {$this->table} 
                WHERE material_id = :material_id 
                AND DATE(transaction_date) = :today 
                AND transaction_type = 'IN' 
                AND qr_code IS NOT NULL 
                AND qr_code != ''";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':material_id', $material_id);
        $stmt->bindValue(':today', $today);
        $stmt->execute();
        $result = $stmt->fetch();
        
        // Get the next sequential run number
        $next_run_number = (int)$result['max_run'] + 1;
        $run_number = str_pad($next_run_number, 4, '0', STR_PAD_LEFT);
        
        // Format: mat_id - dateTh(ddmmyy)-runnumber(4 digits)
        return sprintf("%s-%s-%s", $mat_id, $date_th, $run_number);
    }
}
?> 