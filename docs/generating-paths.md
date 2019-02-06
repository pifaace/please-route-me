# Generating Paths

The router hides another cool feature in its tool box, **Path generation**.
You can generate a path from a registered route like this :
```php
<?php
$router->get('/home/my/path', 'home', function () {});
$path = $router->generate('home'); #$path = /home/my/path
```

Also, you can define parameter value thanks to this following array :
```php
<?php
$router->get('/home/{foo}/{bar}', 'home', function () {});
$path = $router->generate('home', ['foo' => '23', 'bar' => '123']); #$path = /home/23/123
```

