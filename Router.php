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
        $scriptName = $_SERVER['SCRIPT_NAME']; // Example: /wheelder/index.php or /index.php
        $scriptDir = dirname($scriptName);     // Example: /wheelder or /
        
        // If script is in root, return empty string
        if ($scriptDir === '/' || $scriptDir === '\\') {
            return '';
        }
        
        // Return the directory path (e.g., /wheelder)
        return $scriptDir;
    }

    public function route($path, $handler)
    {
        $path = '/' . ltrim($path, '/'); // Normalize
        $this->routes[$path] = $handler;
    }

    public function handleRequest($requestUri)
    {
        $requestPath = parse_url($requestUri, PHP_URL_PATH);

        // Remove the base path if it exists in the request path
        // This handles both /log_api and /wheelder/log_api
        if (!empty($this->basePath)) {
            // If request path starts with base path, remove it
            if (strpos($requestPath, $this->basePath) === 0) {
                $requestPath = substr($requestPath, strlen($this->basePath));
            }
        }

        // Normalize slashes - ensure it starts with /
        $requestPath = '/' . trim($requestPath, '/');
        
        // If path is empty after normalization, make it root
        if (empty($requestPath) || $requestPath === '/') {
            $requestPath = '/';
        }

        // Try to match the route
        if (isset($this->routes[$requestPath])) {
            $handler = $this->routes[$requestPath];
            if (is_callable($handler)) {
                call_user_func($handler);
            } elseif (is_string($handler)) {
                // Use relative path from project root instead of DOCUMENT_ROOT
                $file = __DIR__ . '/' . ltrim($handler, '/');
                if (file_exists($file)) {
                    include $file;
                } else {
                    echo "File not found: " . htmlspecialchars($file);
                }
            } else {
                echo "Invalid handler type.";
            }
        } else {
            // If route not found, try without base path (for cases where base path detection failed)
            // This handles both /log_api and /wheelder/log_api scenarios
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
