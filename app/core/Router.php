<?php

namespace App\Core;
use Throwable;

class Router
{
    /** @var array<string, array<int, array{pattern:string,handler:mixed}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

  
    public function get(string $path, $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

  
    public function post(string $path, $handler): self
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

   
    public function dispatch(string $method, string $uri): bool
    {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        if (!isset($this->routes[$method])) {
            return false;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches); // remove full match
                return $this->invoke($route['handler'], $matches);
            }
        }

        return false;
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $pattern = $this->compilePathToRegex($path);
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    private function compilePathToRegex(string $path): string
    {


        $regex = preg_replace('#\{[^}]+\}#', '([^/]+)', $path);
        if ($regex === null) {
            $regex = $path;
        }
        return '#^' . str_replace('#', '\#', $regex) . '$#';
    }

   
    private function invoke($handler, array $params): bool
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return true;
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controller, $method] = explode('@', $handler, 2);
            return $this->invokeController($controller, $method, $params);
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $instance = is_string($class) ? new $class() : $class;
            call_user_func_array([$instance, $method], $params);
            return true;
        }

        if (is_string($handler) && strpos($handler, '::') !== false) {
            [$class, $method] = explode('::', $handler, 2);
            $instance = class_exists($class) ? new $class() : null;
            if ($instance && method_exists($instance, $method)) {
                call_user_func_array([$instance, $method], $params);
                return true;
            }
        }

        return false;
    }

    private function invokeController(string $controller, string $method, array $params): bool
    {


        if (!class_exists($controller)) {

            $controllerFile = __DIR__ . '/../../app/controller/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
            }
        }

        if (!class_exists($controller)) {
            error_log("Router: Controller class not found: {$controller}");
            return false;
        }

        try {
            $instance = new $controller();
            if (!method_exists($instance, $method)) {
                error_log("Router: Method not found: {$controller}::{$method}");
                return false;
            }

            call_user_func_array([$instance, $method], $params);
            return true;
        } catch (Throwable $e) {
            error_log('Router error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return false;
        }
    }
}