<?php
/**
 * Material Stock Model
 * จัดการข้อมูลสต็อกและการรับเข้า-จ่ายออกวัสดุ
 */
class MaterialStockModel extends Model
{
    protected $table = 'material_stock';

    /**
     * Get current stock for a material
     */
    public function getCurrentStock($material_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE material_id = :material_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':material_id', $material_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Get current stock for all materials with material details
     */
    public function getAllCurrentStock()
    {
        $sql = "SELECT ms.*, m.mat_id, m.mat_name, m.min_qty, m.supplier, m.location 
                FROM {$this->table} ms 
                JOIN materials m ON ms.material_id = m.id 
                WHERE m.active = 1 
                ORDER BY m.mat_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Update stock quantity
     */
    public function updateStock($material_id, $quantity)
    {
        $sql = "UPDATE {$this->table} SET current_qty = :quantity WHERE material_id = :material_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':material_id', $material_id);
        return $stmt->execute();
    }

    /**
     * Initialize stock for a material (if not exists)
     */
    public function initializeStock($material_id, $initial_qty = 0)
    {
        // Check if stock record exists
        $existing = $this->getCurrentStock($material_id);
        if (!$existing) {
            $sql = "INSERT INTO {$this->table} (material_id, current_qty) VALUES (:material_id, :quantity)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':material_id', $material_id);
            $stmt->bindValue(':quantity', $initial_qty);
            return $stmt->execute();
        }
        return true;
    }

    /**
     * Get low stock materials (below minimum quantity but not out of stock)
     */
    public function getLowStockMaterials()
    {
        $sql = "SELECT ms.*, m.mat_id, m.mat_name, m.min_qty, m.supplier, m.location 
                FROM {$this->table} ms 
                JOIN materials m ON ms.material_id = m.id 
                WHERE ms.current_qty > 0 AND ms.current_qty <= m.min_qty AND m.active = 1 
                ORDER BY (m.min_qty - ms.current_qty) DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get stock summary statistics
     */
    public function getStockSummary()
    {
        $sql = "SELECT 
                    COUNT(*) as total_materials,
                    SUM(ms.current_qty) as total_stock,
                    SUM(m.min_qty) as total_min_qty,
                    COUNT(CASE WHEN ms.current_qty > 0 AND ms.current_qty <= m.min_qty THEN 1 END) as low_stock_count,
                    COUNT(CASE WHEN ms.current_qty = 0 THEN 1 END) as out_of_stock_count,
                    COUNT(CASE WHEN ms.current_qty > m.min_qty THEN 1 END) as normal_stock_count
                FROM {$this->table} ms 
                JOIN materials m ON ms.material_id = m.id 
                WHERE m.active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        
        // Calculate additional statistics
        $total_stock = (int)$result['total_stock'];
        $total_min_qty = (int)$result['total_min_qty'];
        
        // Calculate stock ratio (percentage of current stock vs minimum required)
        $stock_ratio = $total_min_qty > 0 ? ($total_stock / $total_min_qty) * 100 : 0;
        
        // Determine overall stock status
        $stock_status = 'sufficient';
        if ($stock_ratio < 50) {
            $stock_status = 'critical';
        } elseif ($stock_ratio < 100) {
            $stock_status = 'low';
        }
        
        return array_merge($result, [
            'total_min_qty' => $total_min_qty,
            'stock_ratio' => round($stock_ratio, 1),
            'stock_status' => $stock_status
        ]);
    }

    /**
     * Calculate current stock from material_transactions
     * This method calculates the actual stock based on IN/OUT transactions
     */
    public function calculateStockFromTransactions($material_id = null)
    {
        $where_clause = '';
        $params = [];
        
        if ($material_id) {
            $where_clause = "WHERE material_id = :material_id";
            $params[':material_id'] = $material_id;
        }
        
        $sql = "SELECT 
                    material_id,
                    SUM(CASE WHEN transaction_type = 'IN' THEN quantity ELSE 0 END) as total_in,
                    SUM(CASE WHEN transaction_type = 'OUT' THEN quantity ELSE 0 END) as total_out,
                    SUM(CASE WHEN transaction_type = 'IN' THEN quantity ELSE -quantity END) as net_quantity
                FROM material_transactions 
                {$where_clause}
                GROUP BY material_id";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        if ($material_id) {
            return $stmt->fetch();
        } else {
            return $stmt->fetchAll();
        }
    }
    
    /**
     * Update stock for a specific material based on transactions
     */
    public function updateStockFromTransactions($material_id)
    {
        $stock_data = $this->calculateStockFromTransactions($material_id);
        
        if ($stock_data) {
            $current_qty = (int)$stock_data['net_quantity'];
            
            // Check if stock record exists
            $existing = $this->getCurrentStock($material_id);
            
            if ($existing) {
                // Update existing record
                return $this->updateStock($material_id, $current_qty);
            } else {
                // Create new record
                return $this->initializeStock($material_id, $current_qty);
            }
        }
        
        return false;
    }
    
    /**
     * Update stock for all materials based on transactions
     */
    public function updateAllStockFromTransactions()
    {
        $all_stock_data = $this->calculateStockFromTransactions();
        $success_count = 0;
        
        foreach ($all_stock_data as $stock_data) {
            $material_id = $stock_data['material_id'];
            $current_qty = (int)$stock_data['net_quantity'];
            
            // Check if stock record exists
            $existing = $this->getCurrentStock($material_id);
            
            if ($existing) {
                // Update existing record
                if ($this->updateStock($material_id, $current_qty)) {
                    $success_count++;
                }
            } else {
                // Create new record
                if ($this->initializeStock($material_id, $current_qty)) {
                    $success_count++;
                }
            }
        }
        
        return $success_count;
    }
    
    /**
     * Get current stock for all materials with calculated quantities from transactions
     */
    public function getAllCurrentStockFromTransactions()
    {
        $sql = "SELECT 
                    m.id as material_id,
                    m.mat_id,
                    m.mat_name,
                    m.min_qty,
                    m.supplier,
                    m.location,
                    COALESCE(ms.current_qty, 0) as current_qty,
                    COALESCE(ms.last_updated, '') as last_updated
                FROM materials m 
                LEFT JOIN {$this->table} ms ON m.id = ms.material_id 
                WHERE m.active = 1 
                ORDER BY m.mat_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $materials = $stmt->fetchAll();
        
        // Calculate actual stock from transactions for each material
        foreach ($materials as &$material) {
            $stock_data = $this->calculateStockFromTransactions($material['material_id']);
            if ($stock_data) {
                $material['calculated_qty'] = (int)$stock_data['net_quantity'];
                $material['total_in'] = (int)$stock_data['total_in'];
                $material['total_out'] = (int)$stock_data['total_out'];
                
                // Update the current_qty if it's different from calculated
                if ($material['current_qty'] != $material['calculated_qty']) {
                    $material['current_qty'] = $material['calculated_qty'];
                    $material['needs_update'] = true;
                }
            } else {
                $material['calculated_qty'] = 0;
                $material['total_in'] = 0;
                $material['total_out'] = 0;
                $material['needs_update'] = false;
            }
        }
        
        return $materials;
    }
    
    /**
     * Get current stock for all materials with pagination and filters
     */
    public function getAllCurrentStockFromTransactionsPaginated($page = 1, $per_page = 20, $search = '', $status_filter = '')
    {
        $offset = ($page - 1) * $per_page;
        
        // Build WHERE clause for search and status filter
        $where_conditions = ['m.active = 1'];
        $params = [];
        
        if (!empty($search)) {
            $where_conditions[] = "(m.mat_id LIKE :search OR m.mat_name LIKE :search OR m.supplier LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        if (!empty($status_filter)) {
            switch ($status_filter) {
                case 'normal':
                    $where_conditions[] = "ms.current_qty > m.min_qty";
                    break;
                case 'low':
                    $where_conditions[] = "ms.current_qty <= m.min_qty AND ms.current_qty > 0";
                    break;
                case 'out_of_stock':
                    $where_conditions[] = "ms.current_qty = 0";
                    break;
            }
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        // Get total count for pagination
        $count_sql = "SELECT COUNT(*) as total FROM materials m 
                      LEFT JOIN {$this->table} ms ON m.id = ms.material_id 
                      WHERE {$where_clause}";
        $count_stmt = $this->db->prepare($count_sql);
        foreach ($params as $key => $value) {
            $count_stmt->bindValue($key, $value);
        }
        $count_stmt->execute();
        $total = $count_stmt->fetch()['total'];
        
        // Get paginated data
        $sql = "SELECT 
                    m.id as material_id,
                    m.mat_id,
                    m.mat_name,
                    m.min_qty,
                    m.supplier,
                    m.location,
                    COALESCE(ms.current_qty, 0) as current_qty,
                    COALESCE(ms.last_updated, '') as last_updated
                FROM materials m 
                LEFT JOIN {$this->table} ms ON m.id = ms.material_id 
                WHERE {$where_clause}
                ORDER BY m.mat_id
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $materials = $stmt->fetchAll();
        
        // Calculate actual stock from transactions for each material
        foreach ($materials as &$material) {
            $stock_data = $this->calculateStockFromTransactions($material['material_id']);
            if ($stock_data) {
                $material['calculated_qty'] = (int)$stock_data['net_quantity'];
                $material['total_in'] = (int)$stock_data['total_in'];
                $material['total_out'] = (int)$stock_data['total_out'];
                
                // Update the current_qty if it's different from calculated
                if ($material['current_qty'] != $material['calculated_qty']) {
                    $material['current_qty'] = $material['calculated_qty'];
                    $material['needs_update'] = true;
                }
            } else {
                $material['calculated_qty'] = 0;
                $material['total_in'] = 0;
                $material['total_out'] = 0;
                $material['needs_update'] = false;
            }
        }
        
        return [
            'data' => $materials,
            'current_page' => $page,
            'last_page' => ceil($total / $per_page),
            'total' => $total,
            'per_page' => $per_page
        ];
    }
}
?> 