<?php

namespace Tests\Router;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Piface\Router\Router;

class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testRegisterRoute()
    {
        $this->router->get('/foo', 'foo', function () {return 'hello from foo'; });
        $this->router->get('/blo', 'blo', function () {return 'blo'; });

        $this->assertCount(2, $this->router->getAllRoutes());
    }

    public function testGetMethodWithNoParameter()
    {
        $this->router->get('/foo', 'foo', function () {return 'hello from foo'; });

        $request = new ServerRequest('GET', '/foo');
        $route = $this->router->match($request);

        $this->assertEquals('foo', $route->getName());
        $this->assertEquals('hello from foo', \call_user_func_array($route->getAction(), []));
    }

    public function testGetWithParameters()
    {
        $this->router->get('/profile/{id}', 'profile', function ($id) {return 'profile '.$id; });

        $request = new ServerRequest('GET', '/profile/3');
        $route = $this->router->match($request);

        $this->assertEquals('profile', $route->getName());
        $this->assertEquals('profile 3', \call_user_func_array($route->getAction(), $route->getParameters()));
    }

    public function testInvalidRoute()
    {
        $request = new ServerRequest('GET', '/ferfeffe');
        $route = $this->router->match($request);

        $this->assertEquals(null, $route);
    }

    /**
     * @expectedException \Piface\Router\Exception\DuplicateRouteUriException
     */
    public function testDuplicateUri()
    {
        $this->router->get('/foo', 'foo', function () {return 'hello from foo'; });
        $this->router->get('/foo', 'bar', function () {return 'bar'; });
    }

    /**
     * @expectedException \Piface\Router\Exception\DuplicateRouteNameException
     */
    public function testDuplicateRouteName()
    {
        $this->router->get('/foofoo', 'foo', function () {return 'hello from foo'; });
        $this->router->get('/foo', 'foo', function () {return 'foo'; });
    }

    public function testGetWithOneParameterAndWhere()
    {
        $this->router->get('/profile/{id}', 'profile', function ($id) {return 'profile '.$id; })->where(['id' => '[0-9]+']);

        $validRequest = new ServerRequest('GET', '/profile/34');
        $invalidRequest = new ServerRequest('GET', '/profile/foo');

        $validRoute = $this->router->match($validRequest);
        $invalidRoute = $this->router->match($invalidRequest);

        $this->assertEquals('profile 34', \call_user_func_array($validRoute->getAction(), $validRoute->getParameters()));
        $this->assertEquals(null, $invalidRoute);
    }

    public function testGetWithParametersAndWheres()
    {
        $this->router->get('/profile/{id}/{foo}', 'profile', function ($id, $foo) {return 'profile '.$id.$foo; })->where(['id' => '[0-9]+', 'foo' => '[a-zA-Z]+']);

        $validRequest = new ServerRequest('GET', '/profile/34/Az');
        $invalidRequest = new ServerRequest('GET', '/profile/2f/4');

        $validRoute = $this->router->match($validRequest);
        $invalidRoute = $this->router->match($invalidRequest);

        $this->assertEquals('profile 34Az', \call_user_func_array($validRoute->getAction(), $validRoute->getParameters()));
        $this->assertEquals(null, $invalidRoute);
    }
}
