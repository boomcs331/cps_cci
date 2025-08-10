<?php
/**
 * Materials Controller
 * จัดการวัสดุ/อุปกรณ์
 */
class MaterialsController extends Controller
{
    private $materialsModel;

    public function __construct()
    {
        // ตรวจสอบสิทธิ์ pc และ admin
        $this->requirePermission(['pc', 'admin']);
        $this->materialsModel = $this->model('MaterialsModel');
    }

    /**
     * แสดงรายการวัสดุทั้งหมด
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $result = $this->materialsModel->getAllMaterials($page, 10, $search);
        
        $data = [
            'title' => 'จัดการวัสดุ - CPS',
            'materials' => $result['data'],
            'pagination' => [
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page'],
                'total' => $result['total']
            ],
            'search' => $search
        ];

        $this->view('materials/index', $data);
    }

    /**
     * แสดงฟอร์มเพิ่มวัสดุใหม่
     */
    public function create()
    {
        $data = [
            'title' => 'เพิ่มวัสดุใหม่ - CPS'
        ];

        $this->view('materials/create', $data);
    }

    /**
     * บันทึกวัสดุใหม่
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('materials');
        }

        $data = [
            'mat_id' => $this->getPost('mat_id'),
            'mat_name' => $this->getPost('mat_name'),
            'lr' => $this->getPost('lr'),
            'min_qty' => (int)$this->getPost('min_qty'),
            'packing' => $this->getPost('packing'),
            'supplier' => $this->getPost('supplier'),
            'location' => $this->getPost('location'),
            'img' => $this->getPost('img')
        ];

        // Validation
        $errors = [];
        if (empty($data['mat_id'])) {
            $errors[] = 'กรุณากรอกรหัสวัสดุ';
        }
        if (empty($data['mat_name'])) {
            $errors[] = 'กรุณากรอกชื่อวัสดุ';
        }

        // Check if mat_id already exists
        if ($this->materialsModel->matIdExists($data['mat_id'])) {
            $errors[] = 'รหัสวัสดุนี้มีอยู่ในระบบแล้ว';
        }

        if (!empty($errors)) {
            $data['errors'] = $errors;
            $data['title'] = 'เพิ่มวัสดุใหม่ - CPS';
            $this->view('materials/create', $data);
            return;
        }

        // Save material
        if ($this->materialsModel->createMaterial($data)) {
            $this->redirect('materials?success=1');
        } else {
            $data['errors'] = ['เกิดข้อผิดพลาดในการบันทึกข้อมูล'];
            $data['title'] = 'เพิ่มวัสดุใหม่ - CPS';
            $this->view('materials/create', $data);
        }
    }

    /**
     * แสดงรายละเอียดวัสดุ
     */
    public function show($id)
    {
        $material = $this->materialsModel->getMaterialById($id);
        
        if (!$material) {
            $this->redirect('materials?error=Material not found');
        }

        $data = [
            'title' => 'รายละเอียดวัสดุ - CPS',
            'material' => $material
        ];

        $this->view('materials/show', $data);
    }

    /**
     * แสดงฟอร์มแก้ไขวัสดุ
     */
    public function edit($id)
    {
        $material = $this->materialsModel->getMaterialById($id);
        
        if (!$material) {
            $this->redirect('materials?error=Material not found');
        }

        $data = [
            'title' => 'แก้ไขวัสดุ - CPS',
            'material' => $material
        ];

        $this->view('materials/edit', $data);
    }

    /**
     * อัปเดตวัสดุ
     */
    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('materials');
        }

        $material = $this->materialsModel->getMaterialById($id);
        if (!$material) {
            $this->redirect('materials?error=Material not found');
        }

        $data = [
            'mat_id' => $this->getPost('mat_id'),
            'mat_name' => $this->getPost('mat_name'),
            'lr' => $this->getPost('lr'),
            'min_qty' => (int)$this->getPost('min_qty'),
            'packing' => $this->getPost('packing'),
            'supplier' => $this->getPost('supplier'),
            'location' => $this->getPost('location'),
            'img' => $this->getPost('img')
        ];

        // Validation
        $errors = [];
        if (empty($data['mat_id'])) {
            $errors[] = 'กรุณากรอกรหัสวัสดุ';
        }
        if (empty($data['mat_name'])) {
            $errors[] = 'กรุณากรอกชื่อวัสดุ';
        }

        // Check if mat_id already exists (excluding current material)
        if ($this->materialsModel->matIdExists($data['mat_id'], $id)) {
            $errors[] = 'รหัสวัสดุนี้มีอยู่ในระบบแล้ว';
        }

        if (!empty($errors)) {
            $data['errors'] = $errors;
            $data['title'] = 'แก้ไขวัสดุ - CPS';
            $data['material'] = $material;
            $this->view('materials/edit', $data);
            return;
        }

        // Update material
        if ($this->materialsModel->updateMaterial($id, $data)) {
            $this->redirect('materials?success=2');
        } else {
            $data['errors'] = ['เกิดข้อผิดพลาดในการอัปเดตข้อมูล'];
            $data['title'] = 'แก้ไขวัสดุ - CPS';
            $data['material'] = $material;
            $this->view('materials/edit', $data);
        }
    }

    /**
     * ลบวัสดุ
     */
    public function delete($id)
    {
        if (!$this->isPost()) {
            $this->redirect('materials');
        }

        $material = $this->materialsModel->getMaterialById($id);
        if (!$material) {
            $this->redirect('materials?error=Material not found');
        }

        if ($this->materialsModel->deleteMaterial($id)) {
            $this->redirect('materials?success=3');
        } else {
            $this->redirect('materials?error=Delete failed');
        }
    }

    /**
     * ค้นหาวัสดุ
     */
    public function search()
    {
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($search)) {
            $this->redirect('materials');
        }

        $materials = $this->materialsModel->searchMaterials($search);
        
        $data = [
            'title' => 'ค้นหาวัสดุ - CPS',
            'materials' => $materials,
            'search' => $search
        ];

        $this->view('materials/search', $data);
    }
}
?> 