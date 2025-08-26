<?php
/**
 * Material Model
 * จัดการข้อมูลวัตถุดิบและสินค้า
 */
class Material extends Model
{
    /**
     * ดึงข้อมูลวัตถุดิบพร้อมการกรองและแบ่งหน้า
     */
    public function getMaterials($search = '', $type = '', $active = '', $page = 1, $limit = 20, $sort = 'product_id', $order = 'ASC')
    {
        try {
            $sql = "SELECT * FROM material WHERE 1=1 AND type = 'pc'";
            $params = [];
            $offset = ($page - 1) * $limit;

            // เพิ่มเงื่อนไขการค้นหา
            if (!empty($search)) {
                $sql .= " AND (product_id LIKE ? OR product_name LIKE ? OR supplier LIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            // เพิ่มเงื่อนไขประเภท (ถ้ามีการส่งมา)
            if (!empty($type)) {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            // เพิ่มเงื่อนไขสถานะ
            if ($active !== '') {
                $sql .= " AND active = ?";
                $params[] = $active;
            }

            // เพิ่มการเรียงลำดับ
            $sql .= " ORDER BY {$sort} {$order}";

            // เพิ่มการแบ่งหน้า - ใช้ LIMIT และ OFFSET โดยตรงใน SQL
            $sql .= " LIMIT {$limit} OFFSET {$offset}";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getMaterials: " . $e->getMessage());
            return [];
        }
    }

    /**
     * นับจำนวนวัตถุดิบทั้งหมดตามเงื่อนไข
     */
    public function getMaterialsCount($search = '', $type = '', $active = '1')
    {
        $where = ['1=1', "type = 'pc'"];
        $params = [];

        // เงื่อนไขการค้นหา
        if (!empty($search)) {
            $where[] = "(product_id LIKE ? OR product_name LIKE ? OR supplier LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // กรองตามประเภท (ถ้ามีการส่งมา)
        if (!empty($type)) {
            $where[] = "type = ?";
            $params[] = $type;
        }

        // กรองตามสถานะ
        if ($active !== '') {
            $where[] = "active = ?";
            $params[] = $active;
        }

        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT COUNT(*) FROM material WHERE {$whereClause}";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in getMaterialsCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * ดึงข้อมูลวัตถุดิบตาม ID
     */
    public function getMaterialById($id)
    {
        $sql = "SELECT * FROM material WHERE id = ? AND type = 'pc'";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getMaterialById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * เพิ่มวัตถุดิบใหม่
     */
    public function addMaterial($data)
    {
        $sql = "INSERT INTO material 
                (product_id, product_name, packing, lr, supplier, min_stock, due, type, location_plan, img, active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['product_id'],
                $data['product_name'],
                $data['packing'],
                $data['lr'],
                $data['supplier'],
                $data['min_stock'],
                $data['due'],
                $data['type'],
                $data['location_plan'],
                $data['img'] ?? null,
                $data['active']
            ]);
        } catch (PDOException $e) {
            error_log("Error in addMaterial: " . $e->getMessage());
            return false;
        }
    }

    /**
     * แก้ไขข้อมูลวัตถุดิบ
     */
    public function updateMaterial($id, $data)
    {
        $sql = "UPDATE material SET 
                product_id = ?, 
                product_name = ?, 
                packing = ?, 
                lr = ?, 
                supplier = ?, 
                min_stock = ?, 
                due = ?, 
                type = ?, 
                location_plan = ?, 
                active = ?
                WHERE id = ?";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['product_id'],
                $data['product_name'],
                $data['packing'],
                $data['lr'],
                $data['supplier'],
                $data['min_stock'],
                $data['due'],
                $data['type'],
                $data['location_plan'],
                $data['active'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error in updateMaterial: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ลบวัตถุดิบ (soft delete - เปลี่ยนสถานะเป็น inactive)
     */
    public function deleteMaterial($id)
    {
        $sql = "UPDATE material SET active = 0 WHERE id = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error in deleteMaterial: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ลบวัตถุดิบจริง (hard delete)
     */
    public function permanentDeleteMaterial($id)
    {
        $sql = "DELETE FROM material WHERE id = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error in permanentDeleteMaterial: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ดึงรายการประเภทวัตถุดิบ
     */
    public function getTypes()
    {
        return [
            'pc' => 'PC',
            'of' => 'OF',
            'of_mat' => 'OF MAT'
        ];
    }

    /**
     * ดึงรายการผู้จัดหาที่ไม่ซ้ำ
     */
    public function getSuppliers()
    {
        $sql = "SELECT DISTINCT supplier FROM material WHERE supplier IS NOT NULL AND supplier != '' ORDER BY supplier";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error in getSuppliers: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ตรวจสอบว่า product_id ซ้ำหรือไม่
     */
    public function isProductIdExists($productId, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM material WHERE product_id = ?";
        $params = [$productId];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error in isProductIdExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ดึงวัตถุดิบที่สต็อกต่ำกว่าขั้นต่ำ
     */
    public function getLowStockMaterials()
    {
        $sql = "SELECT m.*, 
                COALESCE(s.current_stock, 0) as current_stock
                FROM material m
                LEFT JOIN (
                    SELECT material_id, SUM(quantity) as current_stock 
                    FROM stock 
                    GROUP BY material_id
                ) s ON m.id = s.material_id
                WHERE m.active = 1 
                AND COALESCE(s.current_stock, 0) < m.min_stock
                ORDER BY m.product_id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getLowStockMaterials: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงวัตถุดิบที่ใกล้หมดอายุ
     */
    public function getExpiringMaterials($days = 30)
    {
        $sql = "SELECT * FROM material 
                WHERE active = 1 
                AND type = 'pc'
                AND due IS NOT NULL 
                AND due <= DATE_ADD(CURDATE(), INTERVAL ? DAY)
                AND due >= CURDATE()
                ORDER BY due ASC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$days]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getExpiringMaterials: " . $e->getMessage());
            return [];
        }
    }

    /**
     * สถิติวัตถุดิบ
     */
    public function getStatistics()
    {
        $stats = [];

        try {
            // จำนวนวัตถุดิบทั้งหมด
            $sql = "SELECT COUNT(*) FROM material WHERE active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $stats['total_materials'] = $stmt->fetchColumn();

            // จำนวนตามประเภท
            $sql = "SELECT type, COUNT(*) as count FROM material WHERE active = 1 GROUP BY type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $typeStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stats['by_type'] = [];
            foreach ($typeStats as $row) {
                $stats['by_type'][$row['type']] = $row['count'];
            }

            // จำนวนผู้จัดหา
            $sql = "SELECT COUNT(DISTINCT supplier) FROM material WHERE active = 1 AND supplier IS NOT NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $stats['total_suppliers'] = $stmt->fetchColumn();

            return $stats;
        } catch (PDOException $e) {
            error_log("Error in getStatistics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงข้อมูลวัตถุดิบสำหรับการส่งออก CSV (ไม่มีการแบ่งหน้า)
     */
    public function getMaterialsForExport($search = '', $type = '', $active = '', $sort = 'product_id', $order = 'ASC')
    {
        try {
            $sql = "SELECT * FROM material WHERE 1=1 AND type = 'pc'";
            $params = [];

            // เพิ่มเงื่อนไขการค้นหา
            if (!empty($search)) {
                $sql .= " AND (product_id LIKE ? OR product_name LIKE ? OR supplier LIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            // เพิ่มเงื่อนไขประเภท (ถ้ามีการส่งมา)
            if (!empty($type)) {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            // เพิ่มเงื่อนไขสถานะ
            if ($active !== '') {
                $sql .= " AND active = ?";
                $params[] = $active;
            }

            // เพิ่มการเรียงลำดับ
            $sql .= " ORDER BY {$sort} {$order}";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getMaterialsForExport: " . $e->getMessage());
            return [];
        }
    }
}
