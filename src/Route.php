<?php

namespace Router;

class Route
{
    public $uri;
    public $handler;
    public $method;
    public $where = [];

    public function __construct($method, $uri, $handler)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->handler = $handler;
    }

    public function where($name, $regex)
    {
        $this->where[$name] = $regex;
    }
}
