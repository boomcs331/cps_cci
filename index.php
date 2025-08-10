<?php
// Entry point ของแอปพลิเคชัน
require_once 'config/config.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

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

// Materials routes
$router->addRoute('/materials', 'MaterialsController', 'index');
$router->addRoute('/materials/create', 'MaterialsController', 'create');
$router->addRoute('/materials/store', 'MaterialsController', 'store');
$router->addRoute('/materials/show/{id}', 'MaterialsController', 'show');
$router->addRoute('/materials/edit/{id}', 'MaterialsController', 'edit');
$router->addRoute('/materials/update/{id}', 'MaterialsController', 'update');
$router->addRoute('/materials/delete/{id}', 'MaterialsController', 'delete');
$router->addRoute('/materials/search', 'MaterialsController', 'search');

       // Material Transaction routes
       $router->addRoute('/material-transactions', 'MaterialTransactionController', 'index');
       $router->addRoute('/material-transactions/create-in', 'MaterialTransactionController', 'createIn');
       $router->addRoute('/material-transactions/create-out', 'MaterialTransactionController', 'createOut');
       $router->addRoute('/material-transactions/store-in', 'MaterialTransactionController', 'storeIn');
       $router->addRoute('/material-transactions/store-out', 'MaterialTransactionController', 'storeOut');
       $router->addRoute('/material-transactions/show/{id}', 'MaterialTransactionController', 'show');
       $router->addRoute('/material-transactions/stock', 'MaterialTransactionController', 'stock');
       $router->addRoute('/material-transactions/report', 'MaterialTransactionController', 'report');

$router->addRoute('/production', 'PcController', 'production');
$router->addRoute('/we', 'WeController', 'welding');

// Fallback for direct access (when .htaccess doesn't work)
if (isset($_GET['url'])) {
    $_SERVER['REQUEST_URI'] = '/' . $_GET['url'];
}

// เริ่มต้นแอปพลิเคชัน
$router->dispatch();
?> 