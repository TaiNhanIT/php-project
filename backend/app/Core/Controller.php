<?php
class Controller {
    public function view($view, $data = []) {
        // Extract $data array keys as variables for the view file
        extract($data);

        // Require the view file
        require_once "../app/Views/partials/header.php";
        require_once "../app/Views/{$view}.php";
        require_once "../app/Views/partials/footer.php";
    }
}
