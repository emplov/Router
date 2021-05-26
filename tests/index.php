<?php

require '../vendor/autoload.php';

function matchRoute($routes = [], $url = null, $method = 'GET')
{
    // I used PATH_INFO instead of REQUEST_URI, because the
    // application may not be in the root direcory
    // and we dont want stuff like ?var=value
    $reqUrl = $url ?? $_SERVER['PATH_INFO'];
    $reqMet = $method ?? $_SERVER['REQUEST_METHOD'];

    $reqUrl = rtrim($reqUrl,"/");

    foreach ($routes as $route) {
        // convert urls like '/users/:uid/posts/:pid' to regular expression
        // $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route['url'])) . "$@D";
        $pattern = "@^" . preg_replace('/:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', $route['url']) . "$@D";
        // echo $pattern."\n";
        $params = [];
        // check if the current request params the expression
        $match = preg_match($pattern, $reqUrl, $params);
        if ($reqMet == $route['method'] && $match) {
            preg_match_all('/:[a-zA-Z0-9\_\-]+/', $route['url'], $asd);
            // remove the first match
            array_shift($params);
            foreach ($params as $key => $param) {
                unset($params[$key]);
                $params[ltrim($asd[0][$key], ':')] = $param;
            }
            // call the callback with the matched positions as params
            // return call_user_func_array($route['callback'], $params);
            return [$route, $params];
        }
    }
    return [];
}

use Router\Router;

$router = Router::getInstance();

$router->add('GET','/{id}/{name}/{asad}', 'App/Controllers/PageController@index')->where('id', '[0-9]+');
//$router->get('/users/{id}/test/{name}', 'App/Controllers/PageController@index');

//dd($router);

$res = $router->dispatch();

switch ($res['status']) {
    case Router::ROUTE_NOT_FOUND:
        echo "Route not found;";
        break;
    case Router::METHOD_NOT_ALLOWED:
        echo "Method not allowed";
        break;
    case Router::SUCCESS:
        dd($res);
        break;
}
