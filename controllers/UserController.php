<?php
/**
 * User Controller
 * จัดการหน้าสำหรับผู้ใช้ทั่วไป
 */
class UserController extends Controller
{
    public function __construct()
    {
        // ตรวจสอบสิทธิ์ user
        $this->requirePermission(['user']);
    }

    /**
     * หน้า Dashboard สำหรับ User
     */
    public function dashboard()
    {
        $data = [
            'title' => 'User Dashboard - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('user/dashboard', $data);
    }
}
?> 