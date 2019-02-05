# Please-route-me

[![Build Status](https://travis-ci.org/pifaace/please-route-me.svg?branch=master)](https://travis-ci.org/pifaace/please-route-me) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pifaace/please-route-me/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pifaace/please-route-me/?branch=master) [![Code Intelligence Status](https://scrutinizer-ci.com/g/pifaace/please-route-me/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## Getting started

Please-route-me is a router built around one main object called `Router`. It allows you to
define a route, match an requested route, get all routes and more.
In other words, to getting started you have to instantiate this `Router` object :
```php
<?php
   use Piface\Router\Router;

   $router = new Router();
```

Then you can start to add a custom route.

## Adding a simple route

To add a route, `GET` for the example, declare it like this :
```php
<?php
    $router->get('/home', 'home', function () {
       echo 'hi from home';
    });
````

Each route you will create will be built with the same three parameters.
* a `$path`
* A `$name`
* A `callback` or a `string` like name's controller

```php
<?php
    $router->get('/home', 'home', 'indexController@home');
```
**_obviously, you need to implement a `controller resolver` to do that._**

In a second time, you can easily add some parameters in your route definition :
```php
<?php
    $router->get('/user/{id}', 'user', function ($id) {
       echo 'welcome user ' . $id;
    });
```

## Matching a route from the Request
To match a [PSR-7](https://www.php-fig.org/psr/psr-7/) _ServerRequestInterface_,
you can call the `matcher()` from `Router`
```php
<?php
    $route = $router->match($request);
```
The method will return a `Route` object or null if no available route is found.

[Here](https://packagist.org/providers/psr/http-message-implementation) some packages that you can implement.

## Full example

This example below comes from an index.php that implements the router :

```php
<?php

use Piface\Router\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();

$router->get('/home/{id}/{foo}', 'home', function ($id) {
   echo 'hi from home ' . $id;
})->where(['id' => '[0-9]+']);

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

$route = $router->match($request);

// call the action route
\call_user_func_array($route->getAction(), $route->getParameters()); // hi from home $id
```

## Contributing

If you want to try in development environnement :

```bash
$ composer install
$ php -S localhost:8080 -d display_error=1 -t examples/
$ open http://localhost:8080/home/13/bar
```

## Go further

These topics cover the advanced usages of the router :
* [Building Routes](https://github.com/pifaace/please-route-me/blob/master/docs/building-routes.md)
WORK IN PROGRESS
