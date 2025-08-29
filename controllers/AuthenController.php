<?php
require_once 'core/BaseController.php';
require_once 'models/AuthModel.php';

class AuthenController extends BaseController
{
    private $authModel;

    public function __construct($database = null)
    {
        parent::__construct($database);
        $this->authModel = new AuthModel($database);
    }

    public function login()
    {
        // ถ้า login แล้วให้ redirect ไปหน้า dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard/dashboard');
        }

        $this->view('Login/login');
    }

    public function register()
    {
        $this->view('Login/register');
    }

    public function login_check()
    {
        if ($this->isPost()) {
            $user_id = $this->getPost('user_id');
            $user = $this->authModel->login($user_id);

            if ($user) {
                $this->createSession($user);
                // Redirect to dashboard after successful login
                $this->redirect('dashboard/dashboard');
            } else {
                $this->view('Login/login', ['error' => 'User ID ไม่ถูกต้อง']);
            }
        } else {
            $this->redirect('/login');
        }
    }

    public function createSession($user)
    {
        // เริ่มต้น session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // เก็บข้อมูล user ใน session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_db_id'] = $user['id']; // Store the actual database ID
        // Always store roles as array
        if (isset($user['roles']) && is_array($user['roles'])) {
            $_SESSION['roles'] = $user['roles'];
        } elseif (isset($user['role'])) {
            if (is_array($user['role'])) {
                $_SESSION['roles'] = $user['role'];
            } else {
                // If role is a comma-separated string, split it
                $_SESSION['roles'] = array_filter(array_map('trim', explode(',', $user['role'])));
            }
        } else {
            $_SESSION['roles'] = ['user'];
        }
        // For backward compatibility, keep single role as string
        $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();

        // ตั้งค่า session timeout (2 ชั่วโมง)
        $_SESSION['expires'] = time() + (2 * 60 * 60);
    }

    public function logout()
    {
        // เริ่มต้น session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // ล้าง session
        session_unset();
        session_destroy();

        // redirect ไปหน้า login
        $this->redirect('/login');
    }

    public function isLoggedIn()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true &&
            isset($_SESSION['expires']) && $_SESSION['expires'] > time();
    }
}
