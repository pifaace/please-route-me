<?php

namespace Tests\framework\Routing;

use Piface\Routing\Controller;
use Piface\Routing\Router;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
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
     * @expectedException \Piface\Routing\Exceptions\DuplicateRouteException
     */
    public function testDuplicateUri()
    {
        $this->router->get('/foo', 'foo', function () {return 'hello from foo'; });
        $this->router->get('/foo', 'bar', function () {return 'bar'; });
    }

    /**
     * @expectedException \Piface\Routing\Exceptions\DuplicateRouteException
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

    public function testGetAndCallAController()
    {
        $route = $this->router->get('/', 'index', 'IndexController@index');
        $controller = new Controller();

        $this->assertEquals('App\\Controller\\IndexController@index', $route->getAction());
        $this->assertInstanceOf(Response::class, $controller->callAction($route));
    }
}
