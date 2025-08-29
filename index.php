<?php
// Entry point ของแอปพลิเคชัน
require_once 'config/config.php';
require_once 'core/Router.php';
require_once 'core/BaseController.php';
require_once 'core/BaseModel.php';
require_once 'controllers/MaterialTransactionController.php';

// เริ่มต้น session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// เริ่มต้น router
$router = new Router();

// กำหนด routes
$router->addRoute('/', 'HomeController', 'index');
$router->addRoute('/login', 'AuthenController', 'login');
$router->addRoute('/login_check', 'AuthenController', 'login_check');
$router->addRoute('/logout', 'AuthenController', 'logout');
$router->addRoute('/register', 'AuthenController', 'register');
$router->addRoute('/dashboard', 'HomeController', 'dashboard');
$router->addRoute('/admin/dashboard', 'AdminController', 'dashboard');
$router->addRoute('/pc/dashboard', 'PcController', 'dashboard');
$router->addRoute('/user/dashboard', 'UserController', 'dashboard');
$router->addRoute('/dashboard/dashboard', 'HomeController', 'dashboard');
$router->addRoute('/materials/dashboard', 'MaterialsController', 'dashboard');
$router->addRoute('/materials/view', 'MaterialsController', 'viewMaterial');
$router->addRoute('/materials/add', 'MaterialsController', 'add');
$router->addRoute('/materials/edit', 'MaterialsController', 'edit');
$router->addRoute('/materials/delete', 'MaterialsController', 'delete');
$router->addRoute('/materials/exportCSV', 'MaterialsController', 'exportCSV');
$router->addRoute('/materials/transactions', 'MaterialTransactionController', 'index');
$router->addRoute('/materials/transaction-detail', 'MaterialTransactionController', 'detail');
$router->addRoute('/materials/stock', 'MaterialTransactionController', 'stock');
$router->addRoute('/materials/receive', 'MaterialTransactionController', 'receive');
$router->addRoute('/materials/issue', 'MaterialTransactionController', 'issue');







// Fallback for direct access (when .htaccess doesn't work)
if (isset($_GET['url'])) {
    $_SERVER['REQUEST_URI'] = '/' . $_GET['url'];
}

// เริ่มต้นแอปพลิเคชัน
$router->dispatch();
?> 