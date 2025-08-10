<?php
/**
 * PC Controller
 * จัดการหน้าสำหรับ PC
 */
class WeController extends Controller
{
    public function __construct()
    {
        // ตรวจสอบสิทธิ์ pc
        $this->requirePermission(['we']);
    }

    /**
     * หน้า Dashboard สำหรับ PC
     */
    public function dashboard()
    {
        $data = [
            'title' => 'WE Dashboard - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('we/dashboard', $data);
    }

    public function materials()
    {
        $data = [
            'title' => 'Materials - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('we/materials', $data);
    }
}
?> 