# Router - component

## Usage
Get instance of the router
```php
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
$router->dispatch();
```

---
#TODO

* Make possible to get route parameters like: 
```php
$router->get('/users/\d+', function ($id) {
    echo $id;
});
```
