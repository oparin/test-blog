<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Smarty\Smarty;
use App\Controllers\HomeController;
use App\Controllers\CategoryController;
use App\Controllers\PostController;
use App\Exceptions\NotFoundException;

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


try {
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
    } elseif (preg_match('#^/post/(\d+)$#', $url, $matches)) {
        $postId = $matches[1];

        $controller = new PostController($smarty);
        $controller->show($postId);
    } else {
        throw new NotFoundException('Page not found');
    }
} catch (NotFoundException $e) {
    http_response_code(404);
    $message = $e->getMessage();
    require_once __DIR__ . '/../app/Views/errors/404.tpl';

} catch (Throwable $e) {
    http_response_code(500);
    $message = $e->getMessage();
    require_once __DIR__ . '/../app/Views/errors/500.tpl';
}
