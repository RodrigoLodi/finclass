<?php

namespace App\Core;

class Router
{
    private $routes;
    private $baseFolder;

    public function __construct($routes, $baseFolder = '/finplanner')
    {
        $this->routes = $routes;
        $this->baseFolder = $baseFolder;
    }

    public function handleRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        
        if ($requestUri === '') {
            $requestUri = $_SERVER['PHP_SELF'] ?? $_SERVER['SCRIPT_NAME'] ?? '/';
        }
        
        $requestUri = parse_url($requestUri, PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];
    
        $foundRoute = false;
        $isApiRequest = strpos($requestUri, '/api') === 0;
    
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $route => $controllerAction) {
                $routePattern = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9-_]+)', $route);
                $routePattern = str_replace('/', '\/', $routePattern);
    
                if (preg_match('/^' . $routePattern . '$/', $requestUri, $matches)) {
                    $foundRoute = true;
                    array_shift($matches);
    
                    $routeParts = explode('@', $controllerAction);
                    $controllerName = "App\\Controllers\\" . $routeParts[0];
                    $methodName = $routeParts[1];

                    if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                        $controller = new $controllerName();
                        call_user_func_array([$controller, $methodName], $matches);
                        return;
                    } else {
                        if ($isApiRequest) {
                            $this->sendJsonError("Controller or method not found: $controllerName::$methodName", 404);
                        } else {
                            $this->sendHtmlError("Controller or method not found", 404);
                        }
                        return;
                    }
                }
            }
        }
    
        if (!$foundRoute) {
            if ($isApiRequest) {
                $this->sendJsonError("Rota não encontrada: $requestUri", 404);
            } else {
                $this->sendHtmlError("Rota não encontrada", 404);
            }
        }
    }

    private function sendJsonError($message, $statusCode)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => $message
        ]);
    }

    private function sendHtmlError($message, $statusCode)
    {
        http_response_code($statusCode);
        header('Content-Type: text/html');
        echo "<!DOCTYPE html>
              <html lang='pt-br'>
              <head><meta charset='UTF-8'><title>$statusCode - Error</title></head>
              <body><h1>$message</h1></body>
              </html>";
    }
}