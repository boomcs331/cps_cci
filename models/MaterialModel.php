<?php
require_once 'core/BaseModel.php';

class MaterialModel extends BaseModel {
    protected $table = 'material';
    
    public function getActiveMaterials($search = '', $limit = 20, $offset = 0) {
        $whereConditions = ['active = 1'];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(product_id LIKE ? OR product_name LIKE ? OR supplier LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY product_name LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActiveCount($search = '') {
        $whereConditions = ['active = 1'];
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(product_id LIKE ? OR product_name LIKE ? OR supplier LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $sql = "SELECT COUNT(*) FROM {$this->table} {$whereClause}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public function getSuppliers() {
        $stmt = $this->db->prepare("SELECT DISTINCT supplier FROM {$this->table} WHERE supplier IS NOT NULL AND supplier != '' ORDER BY supplier");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public function getTypes() {
        return [
            'pc' => 'ชิ้นงาน',
            'of' => 'สำนักงาน',
            'of_mat' => 'วัสดุสำนักงาน'
        ];
    }
}
?>