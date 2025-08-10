<?php
/**
 * Home Controller
 * จัดการหน้าแรกและหน้าอื่นๆ
 */
class HomeController extends Controller
{
    /**
     * หน้าแรก
     */
    public function index()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
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
                case 'we':
                    $this->redirect('we/dashboard');
                    break;
                default:
                    $this->redirect('admin/dashboard');
            }
        } else {
            $this->redirect('login');
        }
    }

    /**
     * หน้า Dashboard
     */
    public function dashboard()
    {
        // ตรวจสอบว่า login แล้วหรือไม่
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        $data = [
            'title' => 'Dashboard - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('home/dashboard', $data);
    }

    /**
     * หน้าเกี่ยวกับเรา
     */
    public function about()
    {
        $data = [
            'title' => 'เกี่ยวกับเรา - PHP MVC Framework',
            'content' => 'นี่คือหน้าเกี่ยวกับเรา'
        ];

        $this->view('home/about', $data);
    }

    /**
     * หน้าติดต่อ
     */
    public function contact()
    {
        if ($this->isPost()) {
            // จัดการ form submission
            $name = $this->getPost('name');
            $email = $this->getPost('email');
            $message = $this->getPost('message');

            // ตรวจสอบข้อมูล
            if (empty($name) || empty($email) || empty($message)) {
                $data = [
                    'title' => 'ติดต่อเรา - PHP MVC Framework',
                    'error' => 'กรุณากรอกข้อมูลให้ครบถ้วน'
                ];
            } else {
                // บันทึกข้อมูล (ในที่นี้จะแสดงผลลัพธ์)
                $data = [
                    'title' => 'ติดต่อเรา - PHP MVC Framework',
                    'success' => 'ส่งข้อความเรียบร้อยแล้ว'
                ];
            }
        } else {
            $data = [
                'title' => 'ติดต่อเรา - PHP MVC Framework'
            ];
        }

        $this->view('home/contact', $data);
    }
}
?>