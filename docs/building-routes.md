# Building Routes

Each time you declare a new route, the `Router` gets you back a `Route` object. Thanks to that,
you can also chain some methods that `Route` gives you.

## Available Router methods

The router allows you to register routes with these HTTP verb:
```php
<?php
$router->get($path, $name, $callback);
$router->post($path, $name, $callback);
$router->put($path, $name, $callback);
$router->delete($path, $name, $callback);
$router->patch($path, $name, $callback);
$router->options($path, $name, $callback);
```

Also, you can add some HTTP verb on each route like this :
```php
<?php
$router->post('/home', 'home', function () {
    echo 'hi from home';
})->allows('GET'); # this route will accept {POST, GET} verbs

$router->post('/contact', 'contact', function () {
    echo 'hi from contact page';
})->allows(['GET', 'PUT']); # this route will accept {POST, GET, PUT} verbs
```

## Add rules to your routes

`Route` object has much methods in its tools box. One of them is `where()` method. It allows you to type 
arguments that you add to your route : 

```php
<?php
    $router->get('/user/{id}', 'user', function ($id) {
       echo 'welcome user ' . $id;
    })->where(['id' => '[0-9]+']);
```
At this point, the id parameter will only accept numbers from 0 to 9.

WORK IN PROGRESS
