<?php
class AuthModel extends Model
{
    protected $table = 'users';

    public function login($user_id)
    {
        $sql = "SELECT u.*, GROUP_CONCAT(r.role_name) as roles 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.user_id = :user_id AND u.active = 1 
                GROUP BY u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            // Convert roles string to array
            $result['roles'] = $result['roles'] ? explode(',', $result['roles']) : [];
            // Set primary role (first role in the list)
            $result['role'] = !empty($result['roles']) ? $result['roles'][0] : 'user';
        }
        
        return $result;
    }
    
    /**
     * ตรวจสอบสิทธิ์ของผู้ใช้
     */
    public function checkPermission($user_id, $allowed_roles = [])
    {
        $sql = "SELECT r.name as role 
                FROM users u 
                JOIN user_roles ur ON u.id = ur.id 
                JOIN roles r ON ur.role_id = r.role_id 
                WHERE u.user_id = :user_id AND u.status = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        $user_roles = array();
        foreach ($results as $result) {
            $user_roles[] = $result['role'];
        }
        
        foreach ($allowed_roles as $allowed_role) {
            if (in_array($allowed_role, $user_roles)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT u.*, GROUP_CONCAT(r.name) as roles 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.id 
                LEFT JOIN roles r ON ur.role_id = r.role_id 
                WHERE u.id = :id AND u.status = 1 
                GROUP BY u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            $result['roles'] = $result['roles'] ? explode(',', $result['roles']) : [];
            $result['role'] = !empty($result['roles']) ? $result['roles'][0] : 'user';
        }
        
        return $result;
    }
    
    /**
     * Get all users with their roles
     */
    public function getAllUsers()
    {
        $sql = "SELECT u.*, GROUP_CONCAT(r.name) as roles 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.id 
                LEFT JOIN roles r ON ur.role_id = r.role_id 
                WHERE u.status = 1 
                GROUP BY u.id 
                ORDER BY u.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        foreach ($results as &$result) {
            $result['roles'] = $result['roles'] ? explode(',', $result['roles']) : [];
            $result['role'] = !empty($result['roles']) ? $result['roles'][0] : 'user';
        }
        
        return $results;
    }
}
?>