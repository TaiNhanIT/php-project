<?php
session_start();

define('BASE_PATH', __DIR__);

// Get the URL from the query string, default empty
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// Explode URL by slash
$urlSegments = explode('/', $url);

// Get controller and action from URL segments, with default fallback
$controller = !empty($urlSegments[0]) ? $urlSegments[0] : 'home';
$action = isset($urlSegments[1]) ? $urlSegments[1] : 'index';

// Extract additional parameters (e.g., productId from /cart/add/1)
$params = array_slice($urlSegments, 2);

// Build controller class name
$controllerName = ucfirst($controller) . 'Controller';

// Controller file path
$controllerFile = BASE_PATH . '/../app/Controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    header("HTTP/1.0 404 Not Found");
    echo "Controller not found.";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    header("HTTP/1.0 404 Not Found");
    echo "Controller class not found.";
    exit;
}

$controllerObj = new $controllerName();

if (!method_exists($controllerObj, $action)) {
    header("HTTP/1.0 404 Not Found");
    echo "Action not found.";
    exit;
}

// Call action with parameters
if (!empty($params)) {
    call_user_func_array([$controllerObj, $action], $params);
} else {
    $controllerObj->$action();
}