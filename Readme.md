# Router - component

## Usage
Get instance of the router
```php
use Router\Router;

$router = Router::getInstance();
```
Then choose method to set routes
```php
$router->get('/', 'app/Controllers/PageController@index');
or
$router->post('/', 'app/Controllers/PageController@index');
or
$router->add('GET', '/', 'app/Controller/PageController@index');
```
Also you can use function as a parameter
```php
$router->get('/about', function () {
    echo "About";
});
```
In the end just dispatch the route
```php
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
```

---
#TODO

* Make possible to get route parameters like:  (Done)
```php
$router->get('/users/{id}', function ($id) {
    echo $id;
})->where('id', '[0-9]+');
```
* Привести в порядок код
