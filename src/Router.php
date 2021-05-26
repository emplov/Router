<?php

namespace Router;

use Exception;

class Router extends Parser
{
    const ROUTE_NOT_FOUND = 0;
    const SUCCESS = 1;
    const METHOD_NOT_ALLOWED = 2;

    private static $instance = null;

    protected $REQUEST_URI;
    protected $REQUEST_METHOD;

    private $routes;

    private function __construct()
    {
        $this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];
        $this->REQUEST_URI = $_SERVER['PATH_INFO'] ?? '/';
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    private function addRoute($method, $uri, $handler = [])
    {
        $router = self::getInstance();

        $route = new Route($method, $uri, $handler);
        $router->routes[] = $route;

        return $route;
    }

    public function add($method, $path, $handler)
    {
        return self::getInstance()->addRoute($method, $path, $handler);
    }

    public function get($path, $handler)
    {
        return self::getInstance()->addRoute('GET', $path, $handler);
    }

    public function post($path, $function)
    {
        return self::getInstance()->addRoute('POST', $path, $function);
    }

    public function dispatch()
    {
        foreach (self::getInstance()->routes as $route) {
            if (self::getInstance()->matches($route->uri, $route->where)) {
                if (strtoupper($route->method) != $this->REQUEST_METHOD) {
                    return [
                        'status' => self::METHOD_NOT_ALLOWED
                    ];
                }

                $data = self::getInstance()->parseVariables($route);

                if (is_callable($route->handler)) {
                    return [
                        'status' => self::SUCCESS,
                        'result' => [
                            $route->handler,
                            $data
                        ]
                    ];
                } else {
                    $handler = explode('@', $route->handler);

                    return [
                        'status' => self::SUCCESS,
                        'result' => [
                            $handler,
                            $data
                        ]
                    ];
                }
            }
        }

        return [
            'status' => self::ROUTE_NOT_FOUND
        ];
    }
}
