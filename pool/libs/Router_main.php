<?php
// Enable error reporting for debugging
// Remove these lines in production
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Router {
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }

    public function route($route, $handler) {
        $this->routes[$route] = $handler;
    }

    public function handleRequest($url) {
        $url = str_replace($this->basePath, '', $url);

        $handler = $this->findMatchingRoute($url);

        if ($handler !== null) {
            $this->callHandler($handler['handler'], $handler['params']);
        } else {
            $this->redirectTo('/'); // Default to a 404 page
        }
    }

    private function findMatchingRoute($url) {
        foreach ($this->routes as $route => $handler) {
            $pattern = str_replace('/', '\/', $route);
            $pattern = "/^$pattern$/";

            if (preg_match($pattern, $url)) {
                return [
                    'handler' => $handler,
                    'params' => []
                ];
            }
        }

        return null;
    }

    private function callHandler($handler, $params)
    {
        if (is_callable($handler)) {
            call_user_func($handler, $params);
        } elseif (is_string($handler)) {
            $filePath = __DIR__ . '/../../' . ltrim($handler, '/');
            if (file_exists($filePath)) {
                include $filePath;
            } else {
                echo "Sorry, File not found!";
            }
        } else {
            echo "OOPs Invalid path!";
        }
    }

    public function return_defaultApp($dApp)
    {
        switch ($dApp) {
            case 1:
                $defaultApp = 'edu';
                break;
            case 2:
                $defaultApp = 'work';
                break;
            case 3:
                $defaultApp = 'personal';
                break;
            default:
                $defaultApp = null;
                break;
        }
        return $defaultApp;
    }


    public function redirectTo($url) {
        header("Location: $url");
        exit;
    }
}
