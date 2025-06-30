<?php
class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        $basePath = dirname(__DIR__) . '/Views/';
        if (!file_exists($basePath . 'partials/header.php') || !file_exists($basePath . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php') || !file_exists($basePath . 'partials/footer.php')) {
            die("Debug: View file missing for $view");
        }
        require_once $basePath . 'partials/header.php';
        require_once $basePath . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';
        require_once $basePath . 'partials/footer.php';
    }
}