<?php

require '../vendor/autoload.php';

use Router\Router;

$router = Router::getInstance();

$router->get('/', 'app/Controllers/PageController@index');

var_dump($router->dispatch());
