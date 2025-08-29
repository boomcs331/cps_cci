<?php
class BaseController {
    protected $db;
    
    public function __construct($database = null) {
        if ($database) {
            $this->db = $database;
        }
    }
    
    protected function view($viewPath, $data = []) {
        extract($data);
        require_once "views/{$viewPath}.php";
    }
    
    protected function redirect($url, $message = null, $type = 'success') {
        if ($message) {
            $_SESSION[$type] = $message;
        }
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function validateRequired($fields, $data) {
        $errors = [];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[] = "กรุณากรอก {$field}";
            }
        }
        return $errors;
    }
    
    protected function getGet($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
    
    protected function getPost($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function getCurrentUser() {
        return $_SESSION['user'] ?? null;
    }
}
?>