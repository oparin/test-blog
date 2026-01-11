<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Smarty\Smarty;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../app/views/templates');
$smarty->setCompileDir(__DIR__ . '/../app/views/compiled');
$smarty->setCacheDir(__DIR__ . '/../app/views/cache');
$smarty->caching = false;

$request = $_SERVER['REQUEST_URI'];
$method  = $_SERVER['REQUEST_METHOD'];

$url   = parse_url($request, PHP_URL_PATH);
$query = parse_url($request, PHP_URL_QUERY) ?? '';
parse_str($query, $params);

// Routes
if ($url === '/' || $url === '') {
    $controller = new HomeController($smarty);
    $controller->index();
} elseif (preg_match('#^/category/(\d+)$#', $url, $matches)) {
    $categoryId = $matches[1];
    $page       = $_GET['page'] ?? 1;
    $orderBy    = $_GET['order'] ?? 'created_at';

    $controller = new CategoryController($smarty);
    $controller->show($categoryId, $page, $orderBy);
}
