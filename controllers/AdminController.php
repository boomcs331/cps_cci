<?php
/**
 * Admin Controller
 * จัดการหน้าสำหรับผู้ดูแลระบบ
 */
class AdminController extends Controller
{
    public function __construct()
    {
        // ตรวจสอบสิทธิ์ admin
        $this->requirePermission(['admin']);
    }

    /**
     * หน้า Dashboard สำหรับ Admin
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('admin/dashboard', $data);
    }
}
?> 