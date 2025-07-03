<?php
class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        $basePath = dirname(__DIR__) . '/Views/';
        $headerPath = $basePath . 'partials/header.php';
        $viewPath = $basePath . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';
        $footerPath = $basePath . 'partials/footer.php';

        if (!file_exists($headerPath) || !file_exists($viewPath) || !file_exists($footerPath)) {
            error_log("View file missing: header=$headerPath, view=$viewPath, footer=$footerPath");
            // Fallback đến trang lỗi 404
            $errorViewPath = $basePath . 'errors/404.php';
            if (file_exists($errorViewPath)) {
                require_once $errorViewPath;
            } else {
                echo "Debug: View file missing for $view and no fallback available.";
            }
            return;
        }

        require_once $headerPath;
        require_once $viewPath;
        require_once $footerPath;
    }
}