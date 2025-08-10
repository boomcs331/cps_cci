<?php

class AuthenController extends Controller
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = $this->model('AuthModel');
    }

    public function login()
    {
        // ถ้า login แล้วให้ redirect ไปหน้า dashboard
        if ($this->isLoggedIn()) {
            switch ($_SESSION['role']) {
                case 'admin':
                    $this->redirect('admin/dashboard');
                    break;
                case 'pc':
                    $this->redirect('pc/dashboard');
                    break;
                case 'user':
                    $this->redirect('user/dashboard');
                    break;
                default:
                    $this->redirect('admin/dashboard');
            }
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
                switch ($_SESSION['role']) {
                    case 'admin':
                        $this->redirect('admin/dashboard');
                        break;
                    case 'pc':
                        $this->redirect('pc/dashboard');
                        break;
                    case 'we':
                        $this->redirect('we/dashboard');
                        break;
                    case 'user':
                        $this->redirect('user/dashboard');
                        break;
                    default:
                        $this->redirect('admin/dashboard');
                }
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
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_db_id'] = $user['id']; // Store the actual database ID
        $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';
        $_SESSION['roles'] = isset($user['roles']) ? $user['roles'] : ['user'];
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