<?php

class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '')
    {
        if (empty($basePath)) {
            $this->basePath = $this->detectBasePath();
        } else {
            $this->basePath = rtrim($basePath, '/');
        }
    }

    private function detectBasePath()
    {
        $scriptName = $_SERVER['SCRIPT_NAME']; // Example: /wheeleder/index.php
        $scriptDir = dirname($scriptName);     // Example: /wheeleder

        // Return empty string if root, otherwise folder name
        return $scriptDir === '/' ? '' : $scriptDir;
    }

    public function route($path, $handler)
    {
        $path = '/' . ltrim($path, '/'); // Normalize
        $this->routes[$path] = $handler;
    }

    public function handleRequest($requestUri)
    {
        $requestPath = parse_url($requestUri, PHP_URL_PATH);

        // Remove the base path dynamically
        if (!empty($this->basePath) && strpos($requestPath, $this->basePath) === 0) {
            $requestPath = substr($requestPath, strlen($this->basePath));
        }

        // Normalize slashes
        $requestPath = '/' . trim($requestPath, '/');

        if (isset($this->routes[$requestPath])) {
            $handler = $this->routes[$requestPath];
            if (is_callable($handler)) {
                call_user_func($handler);
            } elseif (is_string($handler)) {
                $file = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($handler, '/');
                if (file_exists($file)) {
                    include $file;
                } else {
                    echo "File not found: " . htmlspecialchars($file);
                }
            } else {
                echo "Invalid handler type.";
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
