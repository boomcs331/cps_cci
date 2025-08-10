<?php
/**
 * PC Controller
 * จัดการหน้าสำหรับ PC
 */
class PcController extends Controller
{
    public function __construct()
    {
        // ตรวจสอบสิทธิ์ pc
        $this->requirePermission(['pc']);
    }

    /**
     * หน้า Dashboard สำหรับ PC
     */
    public function dashboard()
    {
        $data = [
            'title' => 'PC Dashboard - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('pc/dashboard', $data);
    }

    public function materials()
    {
        $data = [
            'title' => 'Materials - PHP MVC Framework',
            'user' => $this->getCurrentUser()
        ];

        $this->view('pc/materials', $data);
    }
}
?> 