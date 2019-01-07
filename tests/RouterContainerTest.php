<?php

namespace Tests\Router;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Piface\Router\Route;
use Piface\Router\RouterContainer;

class RouterContainerTest extends TestCase
{
    /**
     * @var RouterContainer
     */
    private $routerContainer;

    public function setUp()
    {
        $this->routerContainer = new RouterContainer();
    }

    public function testAddRoute()
    {
        $route = new Route('GET', '/foo', 'foo', function (){});

        $routeReturned = $this->routerContainer->addRoute($route);

        $this->assertSame($route, $routeReturned);
    }

    /**
     * @expectedException \Piface\Router\Exception\DuplicateRouteUriException
     */
    public function testDuplicateUri()
    {
        $route1 = new Route('GET', '/foo', 'foo', function (){});
        $route2 = new Route('GET', '/foo', 'bar', function (){});

        $this->routerContainer->addRoute($route1);
        $this->routerContainer->addRoute($route2);
    }

    /**
     * @expectedException \Piface\Router\Exception\DuplicateRouteNameException
     */
    public function testDuplicateRouteName()
    {
        $route1 = new Route('GET', '/foo', 'foo', function (){});
        $route2 = new Route('GET', '/bar', 'foo', function (){});

        $this->routerContainer->addRoute($route1);
        $this->routerContainer->addRoute($route2);
    }

    public function testMatch()
    {
        $route = new Route('GET', '/user/{id}', 'user', function () {});
        $request = new ServerRequest('GET', '/user/3');
        $match = $this->routerContainer->match($request, $route);

        $this->assertEquals(true, $match);
    }

    public function testRouteDoesntMatchWithRouteCalled()
    {
        $route = new Route('GET', '/user/{id}', 'user', function () {});
        $request = new ServerRequest('GET', '/foo');
        $match = $this->routerContainer->match($request, $route);

        $this->assertFalse($match);
    }

    public function testMatchWithCustomRegex()
    {
        $route = new Route('GET', '/user/{id}', 'user', function () {});
        $route->where(['id' => '[a-z]+']);
        $invalidRequest = new ServerRequest('GET', '/user/3');
        $validRequest = new ServerRequest('GET', '/user/zd');

        $noResult = $this->routerContainer->match($invalidRequest, $route);
        $result = $this->routerContainer->match($validRequest, $route);

        $this->assertEquals(false, $noResult);
        $this->assertEquals(true, $result);
    }

    public function testGetRoutesForGetMethod()
    {
        $route1 = new Route('GET', '/user/{id}', 'user', function () {});
        $route2 = new Route('POST', '/submit', 'submit', function () {});
        $route3 = new Route('POST', '/foo', 'foo', function () {});

        $this->routerContainer->addRoute($route1);
        $this->routerContainer->addRoute($route2);
        $this->routerContainer->addRoute($route3);

        $routes = $this->routerContainer->getRoutesForSpecificMethod('GET');

        $this->assertCount(1, $routes);
    }
}
