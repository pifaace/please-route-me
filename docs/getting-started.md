# Getting started

Please-route-me is a router built around one main object called `Router`. It allows you to 
define a route, match an requested route, get all routes and more.
In other words, to getting started you have to instantiate this `Router` object :
```php
<?php
   use Piface\Router\Router;
   
   $router = new Router();
?>
```

Then you can start to add a custom route.

## Adding a simple route

To add a route, `GET` for the exemple, declare it like this :
```php
<?php
    $router->get('/home', 'home', function () {
       echo 'hi from home';
    });
?>
````

Each route you will create will be built with the same three parameters.
* An `$uri`
* A `$name`
* A `callback` _(a name controller soon)_

In a second time, you can easily add some parameters in your route definition :
```php
<?php
    $router->get('/user/{id}', 'user', function ($id) {
       echo 'welcome user ' . $id;
    });
?>
```

## Matching a route from the Request
To match a [PSR-7](https://www.php-fig.org/psr/psr-7/) _ServerRequestInterface_, 
you can call the `matcher()` from `Router`
```php
<?php
    $route = $router->match($request);
?>
```
The method will return a `Route` object or null if available route is found.

[Here](https://packagist.org/providers/psr/http-message-implementation) some packages that you can implement.

WORK IN PROGRESS
