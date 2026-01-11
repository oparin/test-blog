<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use Smarty\Smarty;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../app/views/templates');
$smarty->setCompileDir(__DIR__ . '/../app/views/compiled');
$smarty->setCacheDir(__DIR__ . '/../app/views/cache');
$smarty->caching = false;

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$url   = parse_url($request, PHP_URL_PATH);
$query = parse_url($request, PHP_URL_QUERY) ?? '';
parse_str($query, $params);

// Routes
if ($url === '/' || $url === '') {
    $controller = new HomeController($smarty);
    $controller->index();
}
