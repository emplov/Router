<?php

namespace Router;

class Router
{
    private static $instance = null;

    private $REQUEST_URI;
    private $REQUEST_METHOD;

    private $routes;

    private function __construct()
    {
        $this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
        $this->REQUEST_URI = $_SERVER['REQUEST_URI'];
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    private function addRoute($method, $path, $handler = [])
    {
        $router = self::getInstance();

        $router->routes[] = [
            'method' => $method,
            'path' => $path,
            'is_callable' => is_callable($handler),
            'handler' => $handler
        ];
    }

    public function add($method, $path, $handler)
    {
        self::getInstance()->addRoute($method, $path, $handler);

        return self::getInstance();
    }

    public function get($path, $handler)
    {
        self::getInstance()->addRoute('GET', $path, $handler);

        return self::getInstance();
    }

    public function post($path, $function)
    {
        self::getInstance()->addRoute('POST', $path, $function);

        return self::getInstance();
    }

    public function dispatch()
    {
        foreach (self::getInstance()->routes as $route) {
            if (self::getInstance()->matches($route['path'])) {
                $data = self::getInstance()->parseVariables();
                if ($route['is_callable']) {
                    return [
                        $route['handler'],
                        $data
                    ];
                } else {
                    $handler_data = explode('@', $route['handler']);

                    return [
                        $handler_data,
                        $data
                    ];
                }
            }
        }

        throw new Exception('Routes not found');
    }

    private function parseVariables()
    {
        // TODO спарсить данные с url
        return [];
    }

    private function matches($regex)
    {
        return (bool) preg_match('~^' . $regex . '$~', self::getInstance()->REQUEST_URI);
    }
}
