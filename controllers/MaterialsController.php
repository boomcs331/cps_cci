<?php
require_once 'models/Material.php';

class MaterialsController extends Controller
{
    private $materialModel;

    public function __construct()
    {
        $this->materialModel = new Material();
    }

    /**
     * หน้าแสดงรายการวัตถุดิบ
     */
    public function dashboard()
    {
        // ตรวจสอบการ login
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        // รับพารามิเตอร์การค้นหาและกรอง
        $search = $this->getGet('search') ?? '';
        $type = $this->getGet('type') ?? '';
        $active = $this->getGet('active') ?? '1';
        $page = (int)($this->getGet('page') ?? 1);
        $limit = (int)($this->getGet('limit') ?? 20); // รับค่า limit จาก GET parameter
        $sort = $this->getGet('sort') ?? 'product_id'; // รับค่า sort จาก GET parameter
        $order = $this->getGet('order') ?? 'ASC'; // รับค่า order จาก GET parameter

        // ตรวจสอบค่า limit ที่อนุญาต
        $allowedLimits = [10, 20, 50, 100];
        if (!in_array($limit, $allowedLimits)) {
            $limit = 20;
        }

        // ตรวจสอบค่า sort ที่อนุญาต
        $allowedSorts = ['product_id', 'product_name', 'type', 'supplier', 'min_stock', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'product_id';
        }

        // ตรวจสอบค่า order ที่อนุญาต
        $allowedOrders = ['ASC', 'DESC'];
        if (!in_array(strtoupper($order), $allowedOrders)) {
            $order = 'ASC';
        }

        // ดึงข้อมูลวัตถุดิบ
        $materials = $this->materialModel->getMaterials($search, $type, $active, $page, $limit, $sort, $order);
        $totalCount = $this->materialModel->getMaterialsCount($search, $type, $active);
        $totalPages = ceil($totalCount / $limit);

        // ดึงข้อมูลสำหรับ dropdown
        $types = $this->materialModel->getTypes();
        $suppliers = $this->materialModel->getSuppliers();

        $data = [
            'user' => $this->getCurrentUser(),
            'materials' => $materials,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'search' => $search,
            'selectedType' => $type,
            'selectedActive' => $active,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'types' => $types,
            'suppliers' => $suppliers,
            'title' => 'จัดการวัตถุดิบ'
        ];

        $this->view('materials/dashboard', $data);
    }

    /**
     * ดูรายละเอียดวัตถุดิบ
     */
    public function viewMaterial($id)
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        $material = $this->materialModel->getMaterialById($id);
        if (!$material) {
            $_SESSION['error'] = 'ไม่พบข้อมูลวัตถุดิบ';
            $this->redirect('materials/dashboard');
        }

        $data = [
            'user' => $this->getCurrentUser(),
            'material' => $material,
            'title' => 'รายละเอียดวัตถุดิบ - ' . $material['product_name']
        ];

        $this->view('materials/view', $data);
    }

    /**
     * เพิ่มวัตถุดิบใหม่
     */
    public function add()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        if ($this->isPost()) {
            // จัดการการบันทึกข้อมูล
            $data = [
                'product_id' => $this->getPost('product_id'),
                'product_name' => $this->getPost('product_name'),
                'packing' => $this->getPost('packing'),
                'lr' => $this->getPost('lr'),
                'supplier' => $this->getPost('supplier'),
                'min_stock' => (int)$this->getPost('min_stock'),
                'due' => $this->getPost('due'),
                'type' => $this->getPost('type'),
                'location_plan' => $this->getPost('location_plan'),
                'active' => 1
            ];

            if ($this->materialModel->addMaterial($data)) {
                $_SESSION['success'] = 'เพิ่มวัตถุดิบเรียบร้อยแล้ว';
                $this->redirect('materials/dashboard');
            } else {
                $_SESSION['error'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล';
            }
        }

        $data = [
            'user' => $this->getCurrentUser(),
            'suppliers' => $this->materialModel->getSuppliers(),
            'title' => 'เพิ่มวัตถุดิบใหม่'
        ];

        $this->view('materials/add', $data);
    }

    /**
     * แก้ไขวัตถุดิบ
     */
    public function edit($id)
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        $material = $this->materialModel->getMaterialById($id);
        if (!$material) {
            $_SESSION['error'] = 'ไม่พบข้อมูลวัตถุดิบ';
            $this->redirect('materials/dashboard');
        }

        if ($this->isPost()) {
            // จัดการการแก้ไขข้อมูล
            $data = [
                'product_id' => $this->getPost('product_id'),
                'product_name' => $this->getPost('product_name'),
                'packing' => $this->getPost('packing'),
                'lr' => $this->getPost('lr'),
                'supplier' => $this->getPost('supplier'),
                'min_stock' => (int)$this->getPost('min_stock'),
                'due' => $this->getPost('due'),
                'type' => $this->getPost('type'),
                'location_plan' => $this->getPost('location_plan'),
                'active' => (int)$this->getPost('active')
            ];

            if ($this->materialModel->updateMaterial($id, $data)) {
                $_SESSION['success'] = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
                $this->redirect('materials/dashboard');
            } else {
                $_SESSION['error'] = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
            }
        }

        $data = [
            'user' => $this->getCurrentUser(),
            'material' => $material,
            'suppliers' => $this->materialModel->getSuppliers(),
            'title' => 'แก้ไขวัตถุดิบ - ' . $material['product_name']
        ];

        $this->view('materials/edit', $data);
    }

    /**
     * ลบวัตถุดิบ
     */
    public function delete($id)
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        if ($this->materialModel->deleteMaterial($id)) {
            $_SESSION['success'] = 'ลบข้อมูลเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบข้อมูล';
        }

        $this->redirect('materials/dashboard');
    }

    /**
     * ส่งออกข้อมูลเป็น CSV
     */
    public function exportCSV()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }

        // รับพารามิเตอร์การกรอง
        $search = $this->getGet('search') ?? '';
        $type = $this->getGet('type') ?? '';
        $active = $this->getGet('active') ?? '';
        $sort = $this->getGet('sort') ?? 'product_id';
        $order = $this->getGet('order') ?? 'ASC';

        // ดึงข้อมูลทั้งหมด (ไม่มีการแบ่งหน้า)
        $materials = $this->materialModel->getMaterialsForExport($search, $type, $active, $sort, $order);
        $types = $this->materialModel->getTypes();

        // ตั้งค่า header สำหรับ CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="materials_' . date('Y-m-d_H-i-s') . '.csv"');
        
        // สร้างไฟล์ CSV
        $output = fopen('php://output', 'w');
        
        // เพิ่ม BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // เขียน header
        $headers = [
            'รหัสสินค้า',
            'ชื่อสินค้า', 
            'บรรจุภัณฑ์',
            'LR',
            'ผู้จัดหา',
            'สต็อกขั้นต่ำ',
            'ตัวคูณ สต็อกขั้นต่ำ',
            'ประเภท',
            'ตำแหน่ง',
            'สถานะ'
        ];
        fputcsv($output, $headers);

        // เขียนข้อมูล
        foreach ($materials as $material) {
            $row = [
                $material['product_id'],
                $material['product_name'],
                $material['packing'],
                $material['lr'],
                $material['supplier'],
                $material['min_stock'],
                $material['due'],
                $types[$material['type']] ?? $material['type'],
                $material['location_plan'],
                $material['active'] ? 'ใช้งาน' : 'ไม่ใช้งาน'
            ];
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}