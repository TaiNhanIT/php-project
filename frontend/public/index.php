<?php
ob_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

define('BASE_PATH', dirname(__DIR__));

$url = $_GET['url'] ?? 'home/index';
$segments = explode('/', trim($url, '/'));
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
$action = isset($segments[1]) && !empty($segments[1]) ? $segments[1] : 'index';
$token = $_GET['token'] ?? '';

$controllerFile = BASE_PATH . '/app/Controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    ob_end_clean();
    header("HTTP/1.0 404 Not Found");
    echo "Controller file not found: $controllerFile";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    ob_end_clean();
    header("HTTP/1.0 404 Not Found");
    echo "Class $controllerName not found";
    exit;
}

$controller = new $controllerName();
$validActions = get_class_methods($controllerName);

if (!in_array($action, $validActions)) {
    ob_end_clean();
    header("HTTP/1.0 404 Not Found");
    echo "Action '$action' not found in $controllerName. Valid actions: " . implode(', ', $validActions);
    exit;
}

// Xử lý tham số bổ sung (ví dụ: id cho productDetail)
$params = [];
if (isset($segments[2])) {
    $params[] = $segments[2];
}
if (isset($_GET['id'])) {
    $params[] = $_GET['id'];
}

if (!empty($params)) {
    call_user_func_array([$controller, $action], $params);
} else {
    $controller->$action($token);
}

ob_end_flush();