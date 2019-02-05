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
