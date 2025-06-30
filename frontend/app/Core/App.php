<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if (!empty($url)) {
            $controllerFile = '../app/Controllers/' . ucfirst($url[0]) . 'Controller.php';
            if (isset($url[0]) && file_exists($controllerFile)) {
                $this->controller = ucfirst($url[0]) . 'Controller';
                unset($url[0]);

                require_once $controllerFile;
                $this->controller = new $this->controller;

                if (!empty($url)) {
                    $method = $url[0];
                    if (method_exists($this->controller, $method)) {
                        $this->method = $method;
                        unset($url[0]);

                        if ($this->controller === 'ProductController' && $this->method === 'detail') {
                            if (!empty($url[0]) && is_numeric($url[0])) {
                                $this->params = [$url[0]];
                                unset($url[0]);
                            }
                        }
                    }
                }
            }
        } else {
            require_once '../app/Controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;
        }

        $this->params = array_merge($this->params, array_values($url));
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}