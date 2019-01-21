# Building Routes

Each time you declare a new route, the `Router` gets you back a `Route` object. Thanks to that,
you can also chain some methods that `Route` gives you.

## Add rules to your routes

`Route` object has much methods in its tools box. One of them is `where()` method. It allows you to type 
arguments that you add to your route : 

```php
<?php
    $router->get('/user/{id}', 'user', function ($id) {
       echo 'welcome user ' . $id;
    })->where(['id' => '[0-9]+']);
?>
```
At this point, the id parameter will only accept numbers from 0 to 9.

WORK IN PROGRESS
