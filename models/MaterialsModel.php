<?php
/**
 * Materials Model
 * จัดการข้อมูลวัสดุ/อุปกรณ์
 */
class MaterialsModel extends Model
{
    protected $table = 'materials';

    /**
     * Get all materials with pagination
     */
    public function getAllMaterials($page = 1, $per_page = 10, $search = '')
    {
        $offset = ($page - 1) * $per_page;
        
        $where_clause = '';
        $params = [];
        
        if (!empty($search)) {
            $where_clause = "WHERE mat_id LIKE :search OR mat_name LIKE :search OR supplier LIKE :search";
            $params[':search'] = "%{$search}%";
        }
        
        // Count total records
        $count_sql = "SELECT COUNT(*) as total FROM {$this->table} {$where_clause}";
        $count_stmt = $this->db->prepare($count_sql);
        foreach ($params as $key => $value) {
            $count_stmt->bindValue($key, $value);
        }
        $count_stmt->execute();
        $total = $count_stmt->fetch()['total'];
        
        // Get materials
        $sql = "SELECT * FROM {$this->table} {$where_clause} ORDER BY create_date DESC LIMIT :limit OFFSET :offset";
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
     * Get material by ID
     */
    public function getMaterialById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Get material by mat_id
     */
    public function getMaterialByMatId($mat_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE mat_id = :mat_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':mat_id', $mat_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new material
     */
    public function createMaterial($data)
    {
        $fields = ['mat_id', 'mat_name', 'lr', 'min_qty', 'packing', 'supplier', 'location', 'img'];
        $filtered_data = array_intersect_key($data, array_flip($fields));
        
        return $this->create($filtered_data);
    }

    /**
     * Update material
     */
    public function updateMaterial($id, $data)
    {
        $fields = ['mat_id', 'mat_name', 'lr', 'min_qty', 'packing', 'supplier', 'location', 'img'];
        $filtered_data = array_intersect_key($data, array_flip($fields));
        
        return $this->update($id, $filtered_data);
    }

    /**
     * Delete material
     */
    public function deleteMaterial($id)
    {
        return $this->delete($id);
    }

    /**
     * Check if mat_id exists
     */
    public function matIdExists($mat_id, $exclude_id = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE mat_id = :mat_id";
        $params = [':mat_id' => $mat_id];
        
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $exclude_id;
        }
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        return $stmt->fetch()['count'] > 0;
    }

    /**
     * Get materials with low stock (below minimum quantity)
     */
    public function getLowStockMaterials()
    {
        $sql = "SELECT * FROM {$this->table} WHERE min_qty > 0 ORDER BY min_qty ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Search materials
     */
    public function searchMaterials($search_term)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE mat_id LIKE :search 
                OR mat_name LIKE :search 
                OR supplier LIKE :search 
                OR location LIKE :search 
                ORDER BY create_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search', "%{$search_term}%");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?> 