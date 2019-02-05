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
        $route = new Route('/foo', 'foo', function () {});

        $routeReturned = $this->routerContainer->addRoute($route);

        $this->assertSame($route, $routeReturned);
    }

    /**
     * @expectedException \Piface\Router\Exception\DuplicateRoutePathException
     */
    public function testDuplicatePath()
    {
        $route1 = new Route('/foo', 'foo', function () {});
        $route2 = new Route('/foo', 'bar', function () {});

        $this->routerContainer->addRoute($route1);
        $this->routerContainer->addRoute($route2);
    }

    /**
     * @expectedException \Piface\Router\Exception\DuplicateRouteNameException
     */
    public function testDuplicateRouteName()
    {
        $route1 = new Route('/foo', 'foo', function () {});
        $route2 = new Route('/bar', 'foo', function () {});

        $this->routerContainer->addRoute($route1);
        $this->routerContainer->addRoute($route2);
    }

    public function testMatch()
    {
        $route = new Route('/user/{id}', 'user', function () {});
        $request = new ServerRequest('GET', '/user/3');
        $match = $this->routerContainer->match($request, $route);

        $this->assertEquals(true, $match);
    }

    public function testRouteDoesntMatchWithRouteCalled()
    {
        $route = new Route('/user/{id}', 'user', function () {});
        $request = new ServerRequest('GET', '/foo');
        $match = $this->routerContainer->match($request, $route);

        $this->assertFalse($match);
    }

    public function testMatchWithCustomRegex()
    {
        $route = new Route('/user/{id}', 'user', function () {});
        $route->where(['id' => '[a-z]+']);
        $invalidRequest = new ServerRequest('GET', '/user/3');
        $validRequest = new ServerRequest('GET', '/user/zd');

        $noResult = $this->routerContainer->match($invalidRequest, $route);
        $result = $this->routerContainer->match($validRequest, $route);

        $this->assertEquals(false, $noResult);
        $this->assertEquals(true, $result);
    }

    public function testGetRoutes()
    {
        foreach ($this->getRouteNames() as $path => $routeName) {
            $route = new Route($path, $routeName, function () {});
            $this->routerContainer->addRoute($route);
        }

        $this->assertCount(4, $this->routerContainer->getRoutes());
    }

    private function getRouteNames()
    {
        return [
            '/foo' => 'foo',
            '/bar' => 'bar',
            '/baar' => 'baar',
            '/doe' => 'doe',
        ];
    }
}
