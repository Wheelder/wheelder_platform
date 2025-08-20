<?php

class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public function route($path, $handler)
    {
        $path = '/' . ltrim($path, '/'); // Always ensure leading slash
        $this->routes[$path] = $handler;
    }

    public function handleRequest($requestUri)
    {
        // Remove query string (everything after ?)
        $requestPath = parse_url($requestUri, PHP_URL_PATH);

        // Remove base path (e.g., /wheeleder)
        if (!empty($this->basePath) && strpos($requestPath, $this->basePath) === 0) {
            $requestPath = substr($requestPath, strlen($this->basePath));
        }

        // Normalize
        $requestPath = '/' . trim($requestPath, '/');

        if (isset($this->routes[$requestPath])) {
            $handler = $this->routes[$requestPath];
            if (is_callable($handler)) {
                call_user_func($handler);
            } else {
                echo "Handler for route $requestPath is not callable.";
            }
        } else {
            $this->handleNotFound();
        }
    }

    private function handleNotFound()
    {
        http_response_code(404);
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/404.html')) {
            include $_SERVER['DOCUMENT_ROOT'] . '/404.html';
        } else {
            echo "<h1>404 Not Found</h1>";
        }
    }
}
