<?php


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
                if ($route['is_callable']) {
                    $data = self::getInstance()->parseVariables();
                    return call_user_func_array($route['handler'], $data);
                } else {
                    $data = explode('@', $route['handler']);
                    $class = $data[0];
                    $exploded_class = explode('/', $class);
                    $class_name = end($exploded_class);
                    $method = $data[1];

                    require $class . '.php';

                    $controller = new $class_name();

                    $data = self::getInstance()->parseVariables();
                    return call_user_func_array([$controller, $method], $data);
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
